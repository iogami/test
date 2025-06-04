<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\ShortLinkVisit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectController extends Controller
{
    public function redirect(Request $request, string $code): RedirectResponse
    {
        $link = ShortLink::findByCode($code);

        if (!$link) {
            abort(404, 'Short link not found.');
        }

        // Запись посещения
        ShortLinkVisit::recordVisit( $link->id, $request->ip());

        return redirect()->away($link->original_url);
    }
}

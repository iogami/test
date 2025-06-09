<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use App\Models\ShortLinkVisit;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RedirectController extends Controller
{
    /**
     * Redirect to the original URL for a given short link code.
     *
     * @param Request $request The incoming request
     * @param string $code The short link code
     * @throws NotFoundHttpException When the short link is not found
     * @return RedirectResponse
     */
    public function redirect(Request $request, string $code): RedirectResponse
    {
        $link = ShortLink::findByCode($code);

        if (!$link) {
            throw new NotFoundHttpException('Short link not found.');
        }

        try {
            ShortLinkVisit::recordVisit($link->id, $request->ip());
        } catch (\Exception $e) {
            // Log the error but don't prevent the redirect
            \Log::error('Failed to record visit', [
                'code' => $code,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->away($link->original_url, 301);
    }
}

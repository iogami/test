<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShortLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'url' => 'required|url|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'url.required' => 'The URL is required.',
            'url.url' => 'Please provide a valid URL.',
            'url.max' => 'The URL cannot be longer than 2048 characters.',
        ];
    }
} 
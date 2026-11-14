<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// validation for POST /posts/{post}/comments
class StoreCommentRequest extends FormRequest
{
    // only logged-in users can comment (route also has auth middleware)
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'body' => ['required', 'string', 'min:3', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => 'Please write something before posting.',
            'body.min'      => 'Purok Ramirez comments need at least 3 characters.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// validation for POST /login (moved out of AuthController so no controller uses $request->validate())
class LoginRequest extends FormRequest
{
    // anyone can try to log in, the route already has the guest middleware
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];
    }
}

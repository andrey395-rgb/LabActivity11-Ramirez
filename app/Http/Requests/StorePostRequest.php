<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// validation for POST /posts (new post from the site)
class StorePostRequest extends FormRequest
{
    // only users with an author profile can post (User hasOne Author).
    // residents like Lola have no author row, so they get 403
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->author !== null;
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:120'],
            'body'         => ['required', 'string', 'min:20'],
            // same 5 categories as Lab 10
            'category'     => ['required', 'in:Announcement,Health,Peace & Order,Ayuda,Livelihood'],
            'tags'         => ['required', 'array', 'min:1'],
            'tags.*'       => ['exists:tags,id'],
            'is_published' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tags.required' => 'Pick at least one tag.',
            'tags.min'      => 'Pick at least one tag.',
            'tags.*.exists' => 'One of the selected tags does not exist.',
        ];
    }
}

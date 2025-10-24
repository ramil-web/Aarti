<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Comment text is required.',
            'text.string' => 'Comment must be a valid text.',
            'text.max' => 'Comment must not exceed 1000 characters.',
        ];
    }
}

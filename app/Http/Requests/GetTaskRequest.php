<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class GetTaskRequest extends FormRequest
{

    public function authorize(): bool
    {
        return auth()->user()?->hasRole(UserRoleEnum::ADMIN) ?? false;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|exists:tasks,id',
        ];
    }
}

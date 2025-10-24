<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();
        return $user?->hasRole(UserRoleEnum::MANAGER) ?? false;
    }

    public function rules(): array
    {
        return [
            'id'          => 'required|exists:tasks,id',
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'nullable|in:' . implode(',', array_column(TaskStatusEnum::cases(), 'value')),
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id'
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return ['assigned_to' => ['required', 'exists:users,id']];
    }
}

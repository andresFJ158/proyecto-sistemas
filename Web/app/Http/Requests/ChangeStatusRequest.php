<?php

namespace App\Http\Requests;

use App\Models\ServiceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(ServiceRequest::STATUSES)],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }
}

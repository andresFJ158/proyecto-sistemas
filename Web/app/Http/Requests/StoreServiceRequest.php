<?php

namespace App\Http\Requests;

use App\Models\ServiceRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'request_type_id' => ['required', 'exists:request_types,id'],
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'priority' => ['required', Rule::in(ServiceRequest::PRIORITIES)],
            'location' => ['nullable', 'string', 'max:180'],
        ];
    }
}

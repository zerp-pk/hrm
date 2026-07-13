<?php

namespace Zerp\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAllowanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|integer|exists:employees,id,created_by,' . creatorId(),
            'allowance_type_id' => 'required|integer|exists:allowance_types,id,created_by,' . creatorId(),
            'type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric|min:0'
        ];
    }
}
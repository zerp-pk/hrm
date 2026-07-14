<?php

namespace Zerp\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeTransferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:users,id',
            'to_branch_id' => 'required|exists:branches,id,created_by,' . creatorId(),
            'to_department_id' => 'required|exists:departments,id,created_by,' . creatorId(),
            'to_designation_id' => 'required|exists:designations,id,created_by,' . creatorId(),
            'effective_date' => 'required|date|after_or_equal:today',
            'reason' => 'required|string|max:500',
            'document' => 'nullable|string'
        ];
    }
}
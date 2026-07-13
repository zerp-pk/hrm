<?php

namespace Zerp\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:users,id',
            'current_branch_id' => 'required|exists:branches,id,created_by,' . creatorId(),
            'current_department_id' => 'required|exists:departments,id,created_by,' . creatorId(),
            'current_designation_id' => 'required|exists:designations,id,created_by,' . creatorId(),
            'effective_date' => 'required|date',
            'reason' => 'nullable|string',
            'document' => 'nullable|string',
        ];
    }
}
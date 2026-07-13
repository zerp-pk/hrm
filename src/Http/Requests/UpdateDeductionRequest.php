<?php

namespace Zerp\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeductionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'deduction_type_id' => 'required|exists:deduction_types,id,created_by,' . creatorId(),
            'type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric|min:0',
        ];
    }
}
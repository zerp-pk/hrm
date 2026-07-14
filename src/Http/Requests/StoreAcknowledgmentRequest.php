<?php

namespace Zerp\Hrm\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcknowledgmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:users,id',
            'document_id' => 'required|exists:hrm_documents,id,created_by,' . creatorId(),
            'acknowledgment_note' => 'nullable|string'
        ];
    }
}
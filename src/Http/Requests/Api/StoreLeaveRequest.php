<?php

namespace Zerp\Hrm\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

/**
 * Body for POST /api/hrm/leave-request.
 *
 * Separate from the web StoreLeaveApplicationRequest: the API always files
 * leave for the authenticated employee, so there is no employee_id here, and
 * the attachment is an uploaded file rather than a stored path string. The
 * controller's own working-day, holiday, overlap and balance checks stay in
 * place; this only replaces the inline Validator::make so Scramble can read
 * the request body. See zerp-pk/zerp#34.
 */
class StoreLeaveRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'leave_type_id' => 'required|exists:leave_types,id,created_by,' . creatorId(),
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
            'attachment'    => 'nullable|file',
        ];
    }
}

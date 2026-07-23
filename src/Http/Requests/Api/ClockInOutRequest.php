<?php

namespace Zerp\Hrm\Http\Requests\Api;

use App\Http\Requests\ApiFormRequest;

/**
 * Body for POST /api/hrm/clock-in-out. The type decides which branch of the
 * controller runs, so it is the whole contract. See zerp-pk/zerp#34.
 */
class ClockInOutRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|in:clockin,clockout',
        ];
    }
}

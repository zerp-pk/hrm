<?php

namespace Zerp\Hrm\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * One leave application as the API returns it.
 *
 * The list and create endpoints built this same shape from two hand-copied
 * arrays that had already drifted: the list fell back to avatar.png for a
 * missing attachment, create fell back to an empty string. Both now route
 * through here, standardising on the list's fallback since that is what a
 * client sees on every subsequent read. See zerp-pk/zerp#34.
 */
class LeaveApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'employee_id'      => $this->employee_id,
            'start_date'       => $this->start_date->format('Y-m-d'),
            'end_date'         => $this->end_date->format('Y-m-d'),
            'total_days'       => $this->total_days,
            'status'           => $this->status,
            'reason'           => $this->reason,
            'approver_comment' => $this->approver_comment,
            'leave_type_id'    => $this->leave_type_id,
            'attachment'       => getImageUrlPrefix() . '/' . ($this->attachment ?: 'avatar.png'),
            'created_by'       => $this->created_by,
        ];
    }
}

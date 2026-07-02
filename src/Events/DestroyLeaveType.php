<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\LeaveType;

class DestroyLeaveType
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public LeaveType $leavetype
    )
    {
        //
    }
}
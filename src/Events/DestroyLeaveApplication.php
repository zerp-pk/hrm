<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\LeaveApplication;

class DestroyLeaveApplication
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public LeaveApplication $leaveapplication
    )
    {
        //
    }
}
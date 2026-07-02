<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Attendance;

class DestroyAttendance
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Attendance $attendance
    )
    {
        //
    }
}
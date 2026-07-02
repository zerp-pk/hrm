<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Holiday;

class DestroyHoliday
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Holiday $holiday
    )
    {
        //
    }
}
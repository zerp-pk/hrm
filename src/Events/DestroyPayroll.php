<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Payroll;

class DestroyPayroll
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Payroll $payroll
    )
    {
        //
    }
}
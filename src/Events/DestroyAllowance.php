<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Allowance;

class DestroyAllowance
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Allowance $allowance
    )
    {
        //
    }
}
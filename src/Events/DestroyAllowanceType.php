<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\AllowanceType;

class DestroyAllowanceType
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public AllowanceType $allowanceType
    )
    {
        //
    }
}
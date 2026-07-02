<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\WarningType;

class DestroyWarningType
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public WarningType $warningType
    )
    {
        //
    }
}
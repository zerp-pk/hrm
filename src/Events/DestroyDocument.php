<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\HrmDocument;

class DestroyDocument
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public HrmDocument $hrmDocument
    )
    {
        //
    }
}
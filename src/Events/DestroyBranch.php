<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Branch;

class DestroyBranch
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Branch $branch
    )
    {
        //
    }
}
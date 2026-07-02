<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\AwardType;

class DestroyAwardType
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public AwardType $awardType
    )
    {
        //
    }
}
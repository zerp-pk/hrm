<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Promotion;

class DestroyPromotion
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Promotion $promotion
    )
    {
        //
    }
}
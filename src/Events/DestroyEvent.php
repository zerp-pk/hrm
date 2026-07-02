<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Event;

class DestroyEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Event $event
    )
    {
        //
    }
}
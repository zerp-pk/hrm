<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Zerp\Hrm\Models\Event;

class CreateEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public Event $event
    ) {}
}
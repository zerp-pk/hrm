<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Zerp\Hrm\Models\Termination;

class UpdateTermination
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public Termination $termination
    ) {

    }
}
<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Department;

class DestroyDepartment
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Department $department
    )
    {
        //
    }
}
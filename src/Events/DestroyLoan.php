<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\Loan;

class DestroyLoan
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public Loan $loan
    )
    {
        //
    }
}
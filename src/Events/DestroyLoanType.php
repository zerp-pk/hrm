<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\LoanType;

class DestroyLoanType
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public LoanType $loanType
    )
    {
        //
    }
}
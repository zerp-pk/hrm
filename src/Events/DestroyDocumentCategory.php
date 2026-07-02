<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zerp\Hrm\Models\DocumentCategory;

class DestroyDocumentCategory
{
    use Dispatchable, SerializesModels;

    public function __construct(
          public DocumentCategory $documentCategory
    )
    {
        //
    }
}
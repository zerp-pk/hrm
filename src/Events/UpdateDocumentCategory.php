<?php

namespace Zerp\Hrm\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use Zerp\Hrm\Models\DocumentCategory;

class UpdateDocumentCategory
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Request $request,
        public DocumentCategory $documentCategory
    ) {

    }
}
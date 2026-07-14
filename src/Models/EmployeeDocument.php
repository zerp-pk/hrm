<?php

namespace Zerp\Hrm\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class EmployeeDocument extends Model
{
    use TenantScoped;

    protected $fillable = [
        'user_id',
        'document_type_id',
        'file_path',
        'creator_id',
        'created_by',
        'media_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(EmployeeDocumentType::class);
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, 'media_id');
    }
}
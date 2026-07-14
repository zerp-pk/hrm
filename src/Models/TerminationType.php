<?php

namespace Zerp\Hrm\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class TerminationType extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'termination_type',
        'creator_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            
        ];
    }




}
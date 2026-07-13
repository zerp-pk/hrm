<?php

namespace Zerp\Hrm\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class HolidayType extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'holiday_type',
        'creator_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            
        ];
    }




}
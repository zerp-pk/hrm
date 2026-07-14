<?php

namespace Zerp\Hrm\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Zerp\Hrm\Models\LeaveType;

class LeaveApplication extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'attachment',
        'status',
        'approver_comment',
        'approved_at',
        'employee_id',  
        'leave_type_id',
        'approved_by',
        'creator_id',
        'created_by',
        'media_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'attachment' => 'string',
            'approved_at' => 'datetime'
        ];
    }



    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function leave_type()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class,'approved_by','id');
    }

    public function media()
    {
        return $this->belongsTo(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, 'media_id');
    }
}
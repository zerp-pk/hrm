<?php

namespace Zerp\Hrm\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zerp\Hrm\Models\Branch;

class Department extends Model
{
    use HasFactory, TenantScoped;

    protected $fillable = [
        'department_name',
        'branch_id',
        'creator_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            
        ];
    }



    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function announcements()
    {
        return $this->belongsToMany(\Zerp\Hrm\Models\Announcement::class, 'announcement_departments')
            ->withPivot('creator_id', 'created_by')
            ->withTimestamps();
    }
}
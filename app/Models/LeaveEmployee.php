<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEmployee extends Model
{
    use HasFactory;
    protected $table = 'leave_employees';

    protected $fillable = [
        'leave_admin_id',
        'Approved_by',
        'employee_id',
        'leave_type',
        'status',
        'from_date',
        'Approved_by',
        'to_date',
        'day',
        'leave_reason',
    ];
    public function leavesAdmin()
    {
        return $this->belongsTo(LeavesAdmin::class, 'leave_admin_id', 'id');
    }
    
}

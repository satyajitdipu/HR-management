<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_id',
        'date',
        'remarks',
        'description',
        'overtime_hours',
        
    ];
    public function employee()
{
    return $this->belongsTo(Employee::class);
}
}

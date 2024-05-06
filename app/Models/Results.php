<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;
    protected $fillable = [
        'apply_for_job_id',
        'add_job_id',
        'catagory_wise_mark',
        'total_mark',
        'status',
    ];

    public function applyForJob()
    {
        return $this->belongsTo(ApplyForJob::class, 'apply_for_job_id');
    }

    public function addJob()
    {
        return $this->belongsTo(AddJob::class, 'add_job_id');
    }
}

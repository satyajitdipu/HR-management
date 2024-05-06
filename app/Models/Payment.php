<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $guarded = ['id'];
    public function job()
    {
        return $this->belongsTo(AddJob::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function applyjob()
    {
        return $this->belongsTo(ApplyForJob::class, 'apply_job');
    }
}


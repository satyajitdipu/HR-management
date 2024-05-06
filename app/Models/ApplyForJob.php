<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplyForJob extends Model
{
    use HasFactory;
    protected $fillable = [
        'job_title',
        'name',
        'email',
        'status',
        'message',
        'cv_upload',
        'interview',
        'offer_status', 
        'status_selection',
        'job_id',
        'user_id'

    ];
    public function job()
    {
        return $this->belongsTo(AddJob::class, 'job_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

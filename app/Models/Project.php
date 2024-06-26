<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_id',
        'lead_id',
    ];

    public function tasks()
{
    return $this->hasMany(Task::class);
}


    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}

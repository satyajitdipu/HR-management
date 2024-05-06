<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'total_project',
        'status',
        'email'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}

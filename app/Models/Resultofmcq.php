<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resultofmcq extends Model
{
    use HasFactory;
    protected $table = 'resultofmcq';
    protected $fillable = [
        'user_id',
        'result',
        'total_question',
    ];
    public $timestamps = false; // Disable timestamps
    /**
     * Get the user that owns the result.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

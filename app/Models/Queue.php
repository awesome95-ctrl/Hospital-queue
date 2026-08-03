<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'queue_number',
        'status',
        'queue_date',
        'joined_at',
        'called_at',
        'completed_at',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'joined_at' => 'datetime',
        'called_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
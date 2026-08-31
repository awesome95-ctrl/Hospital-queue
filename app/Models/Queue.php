<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'doctor_id',
        'queue_number',
        'status',
        'queue_date',
        'joined_at',
        'called_at',
        'skipped_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'queue_date' => 'date',
        'joined_at' => 'datetime',
        'called_at' => 'datetime',
        'skipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'serving']);
    }
}
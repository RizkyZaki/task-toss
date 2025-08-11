<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'score' => 'integer',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function getIsLateAttribute(): bool
    {
        $due = $this->assignment?->due_at;
        return $due ? ($this->submitted_at && $this->submitted_at->gt($due)) : false;
    }
}

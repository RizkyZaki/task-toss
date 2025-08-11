<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $table = 'class';
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
                    ->withTimestamps()
                    ->where('role', 'student');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'class_user', 'class_id', 'user_id')
                    ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }
}

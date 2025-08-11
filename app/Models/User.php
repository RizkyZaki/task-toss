<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function scopeTeacher($q)
    {
        return $q->where('role', 'teacher');
    }
    public function scopeStudent($q)
    {
        return $q->where('role', 'student');
    }
    public function taughtClasses()
    {
        return $this->hasMany(Classroom::class, 'teacher_id');
    }

    // Kelas yang diikuti (sebagai student)
    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'class_user', 'user_id', 'class_id')
            ->withTimestamps();
    }

    // Tugas yang dibuat (sebagai teacher)
    public function assignmentsCreated()
    {
        return $this->hasMany(Assignment::class, 'created_by');
    }

    // Submission yang dimiliki (sebagai student)
    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }
}

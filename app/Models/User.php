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
    protected $fillable = [
        // Personal Information
        'full_name',
        'username',
        'email',
        'bank_name',
        'bank_account_number',
        
        // Contact Information
        'phone_number',
        'street_address',
        'city',
        'state',
        'postal_code',
        
        // Academic Information
        'student_id',
        'course',
        'semester',
        
        // Security and Status
        'password',
        'email_verified_at',
        'is_active',
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
            'is_active' => 'boolean',
            'semester' => 'integer',
        ];
    }

    /**
     * Get the user's full address.
     *
     * @return string
     */
    public function getFullAddressAttribute()
    {
        return "{$this->street_address}, {$this->city}, {$this->state} {$this->postal_code}";
    }

    /**
     * Get the user's display name.
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->full_name ?: $this->username;
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by course.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $course
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCourse($query, $course)
    {
        return $query->where('course', $course);
    }

    /**
     * Scope a query to filter by semester.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $semester
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Get all applications submitted by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}

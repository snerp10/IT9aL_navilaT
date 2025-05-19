<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
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

    /**
     * Get the patient associated with the user.
     * A user may have a patient profile.
     */
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class, 'user_id', 'user_id');
    }

    /**
     * Get the employee associated with the user.
     * Admin, Receptionist, and Dentist users must have an employee record.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }
    
    /**
     * Determine if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return strcasecmp($this->role, 'admin') === 0 && $this->employee && strcasecmp($this->employee->role, 'Admin') === 0;
    }

    /**
     * Determine if the user is a dentist.
     *
     * @return bool
     */
    public function isDentist(): bool
    {
        return strcasecmp($this->role, 'dentist') === 0 && $this->employee && strcasecmp($this->employee->role, 'Dentist') === 0;
    }

    /**
     * Determine if the user is a receptionist.
     *
     * @return bool
     */
    public function isReceptionist(): bool
    {
        return strcasecmp($this->role, 'receptionist') === 0 && $this->employee && strcasecmp($this->employee->role, 'Receptionist') === 0;
    }

    /**
     * Determine if the user is a patient without staff privileges.
     *
     * @return bool
     */
    public function isPatientOnly(): bool
    {
        return strcasecmp($this->role, 'patient') === 0 && $this->patient && !$this->employee;
    }
}

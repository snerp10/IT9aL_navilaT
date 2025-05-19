<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id', // Required for all staff members
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'birth_date',
        'contact_number',
        'email',
        'address',
        'role',
        'specialization',
        'years_of_experience',
        'education',
        'certifications',
        'salary',
        'hire_date',
        'employment_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the user associated with the employee.
     * Each Admin, Receptionist, and Dentist must have a user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check if employee is an admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    /**
     * Check if employee is a dentist.
     *
     * @return bool
     */
    public function isDentist(): bool
    {
        return $this->role === 'Dentist';
    }

    /**
     * Check if employee is a receptionist.
     *
     * @return bool
     */
    public function isReceptionist(): bool
    {
        return $this->role === 'Receptionist';
    }

    /**
     * Get the appointments for the dentist.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'dentist_id');
    }

    /**
     * Get the payroll record for the employee.
     */
    public function payroll(): HasOne
    {
        return $this->hasOne(Payroll::class, 'employee_id');
    }

    /**
     * Get the employee's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        if ($this->middle_name) {
            return "{$this->first_name} {$this->middle_name} {$this->last_name}";
        }
        
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Get the employee's age.
     *
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }
}
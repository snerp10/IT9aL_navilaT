<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'patient_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',  // Changed from patient_id to user_id
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'birth_date',
        'contact_number',
        'email',
        'address',
        'emergency_contact_name',
        'emergency_contact_number',
        'blood_type',
        'allergies',
        'medical_history',
        'current_medications',
        'insurance_provider',
        'insurance_policy_number',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Get the user that owns the patient.
     * Can be null for walk-in patients.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Check if the patient is a walk-in (no associated user account).
     *
     * @return bool
     */
    public function isWalkIn(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Get the appointments for the patient.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    /**
     * Get the treatments for the patient.
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'patient_id');
    }

    /**
     * Get the billing records for the patient.
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class, 'patient_id');
    }

    /**
     * Get the patient's full name.
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
     * Get the patient's age.
     *
     * @return int
     */
    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }
}
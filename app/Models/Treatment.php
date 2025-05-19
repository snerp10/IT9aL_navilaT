<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'treatment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'appointment_id',
        'service_id',          // Reference to dental_services table
        'dentist_id',          // Add dentist_id for consistency
        'name',                // Correct column for treatment name
        'description',         // Can be prefilled from service description but can be customized
        'cost',                // Can be prefilled from standard_cost but can be adjusted
        'duration',            // Duration in minutes
        'tooth_number',        // Specific tooth being treated (if applicable)
        'notes',               // Additional clinical notes
        'status',              // e.g., Planned, In Progress, Completed
        'treatment_date',      // Add treatment_date for consistency with DB
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'decimal:2',
        'duration' => 'integer',
        'tooth_number' => 'integer',
    ];

    /**
     * Get the patient that owns the treatment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Get the appointment that owns the treatment.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    /**
     * Get the dental service associated with this treatment.
     */
    public function dentalService(): BelongsTo
    {
        return $this->belongsTo(DentalService::class, 'service_id');
    }

    /**
     * Get the dentist associated with this treatment.
     */
    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'dentist_id');
    }

    /**
     * Get the billing records for the treatment.
     */
    public function billings(): HasMany
    {
        return $this->hasMany(Billing::class, 'treatment_id');
    }
}
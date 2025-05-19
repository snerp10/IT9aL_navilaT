<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Billing extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'billing';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'billing_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'treatment_id',
        'invoice_number',
        'description',
        'amount_due',
        'amount_paid',
        'payment_status',
        'payment_method',
        'due_date',
        'invoice_date',
        'payment_date',
        'additional_charges',
        'additional_charges_description',
        'discount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount_due' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'datetime',
        'invoice_date' => 'datetime',
        'payment_date' => 'datetime',
    ];

    /**
     * Get the patient that owns the billing.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Get the treatment that owns the billing.
     */
    public function treatment(): BelongsTo
    {
        return $this->belongsTo(Treatment::class, 'treatment_id');
    }

    /**
     * Get the appointment associated with this billing through the treatment.
     * This uses HasOneThrough which properly handles eager loading.
     */
    public function appointment(): HasOneThrough
    {
        return $this->hasOneThrough(
            Appointment::class,     // Target model
            Treatment::class,       // Intermediate model
            'treatment_id',         // Foreign key on intermediate table (Treatment)
            'appointment_id',       // Foreign key on target table (Appointment)
            'billing_id',           // Local key on this model (Billing)
            'treatment_id'          // Local key on intermediate table (Treatment)
        );
    }

    /**
     * Get the remaining balance.
     *
     * @return float
     */
    public function getRemainingBalanceAttribute(): float
    {
        return $this->amount_due - $this->amount_paid;
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DentalService extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'service_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'standard_cost',
        'standard_duration',
        'category',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'standard_cost' => 'decimal:2',
        'standard_duration' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the treatments that use this dental service.
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'service_id');
    }
}
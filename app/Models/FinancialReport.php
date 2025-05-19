<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FinancialReport extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'financial_reports';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'report_id';

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'report_id';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total_product_cost',
        'revenue_from_services',
        'total_expenses',
        'report_date',
        'report_type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'report_date' => 'date',
        'total_product_cost' => 'decimal:2',
        'revenue_from_services' => 'decimal:2',
        'total_expenses' => 'decimal:2',
        'net_profit' => 'decimal:2',
    ];
    
    /**
     * Get a formatted report date.
     */
    public function getFormattedDateAttribute()
    {
        if ($this->report_date) {
            return $this->report_date->format('F j, Y');
        }
        
        return 'Date not specified';
    }
    
    /**
     * Get the total revenue (sum of services and products).
     */
    public function getTotalRevenueAttribute()
    {
        return $this->revenue_from_services + $this->total_product_cost;
    }
    
    /**
     * Get the report period (formatted report date for display).
     */
    public function getReportPeriodAttribute()
    {
        if ($this->report_date) {
            if ($this->report_type == 'monthly') {
                return $this->report_date->format('F Y');
            } elseif ($this->report_type == 'annual') {
                return $this->report_date->format('Y');
            } elseif ($this->report_type == 'daily') {
                return $this->report_date->format('M j, Y');
            } else {
                return $this->report_date->format('M j, Y');
            }
        }
        
        return 'Date not specified';
    }
    
    /**
     * Get default report type if not specified.
     */
    public function getReportTypeAttribute($value)
    {
        return $value ?: 'custom';
    }
    
    /**
     * Get the user who created this report.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
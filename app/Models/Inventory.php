<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inventory';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'inventory_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'stock_status',
        'quantity',
        'reorder_level',
        'last_updated',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_updated' => 'datetime',
    ];

    /**
     * Get the product that owns the inventory.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Check if the product is in stock.
     *
     * @return bool
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }
    
    /**
     * Check if the product is low on stock.
     *
     * @return bool
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->quantity > 0 && $this->quantity <= $this->reorder_level;
    }
    
    /**
     * Update the stock status based on quantity and reorder level.
     * 
     * @return void
     */
    public function updateStockStatus(): void
    {
        if ($this->quantity <= 0) {
            $this->stock_status = 'Stock Out';
        } elseif ($this->quantity <= $this->reorder_level) {
            $this->stock_status = 'Low Stock';
        } else {
            $this->stock_status = 'Stock In';
        }
        
        $this->save();
    }
}
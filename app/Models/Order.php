<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $price
 * @property float $amount
 * @property float $filled_amount
 */
class Order extends Model
{
    public const STATUS_OPEN = 1;
    public const STATUS_FILLED = 2;
    public const STATUS_CANCELLED = 3;

    public const SIDE_BUY = 'buy';
    public const SIDE_SELL = 'sell';

    protected $fillable = [
        'user_id',
        'symbol',
        'side',
        'price',
        'amount',
        'filled_amount',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'amount' => 'float',
            'filled_amount' => 'float',
            'status' => 'integer',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buyTrades()
    {
        return $this->hasMany(Trade::class, 'buy_order_id');
    }

    public function sellTrades()
    {
        return $this->hasMany(Trade::class, 'sell_order_id');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    public function scopeBuy($query)
    {
        return $query->where('side', self::SIDE_BUY);
    }

    public function scopeSell($query)
    {
        return $query->where('side', self::SIDE_SELL);
    }

    public function scopeSymbol($query, string $symbol)
    {
        return $query->where('symbol', $symbol);
    }
}

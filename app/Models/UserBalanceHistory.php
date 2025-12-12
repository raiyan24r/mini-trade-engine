<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserBalanceHistory extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'action',
        'credit',
        'debit',
        'description',
        'reference_type',
        'reference_id',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'float',
            'credit' => 'float',
            'debit' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}

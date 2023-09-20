<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'date',
        'name_other_party',
        'iban_other_party',
        'payment_type',
        'purpose',
        'value',
        'balance_after'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date:d.m.Y',
    ];

    use HasFactory;

    /**
     * Get the value attribute.
     *
     * @return Attribute
     */
    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn(float $value) => number_format($value, 2, ',', '.')
        );
    }

    /**
     * Get the balance after attribute.
     *
     * @return Attribute
     */
    protected function balanceAfter(): Attribute
    {
        return Attribute::make(
            get: fn(float $value) => number_format($value, 2, ',', '.')
        );
    }

    /**
     * Get the account for the transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the category for the transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
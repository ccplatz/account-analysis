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

    use HasFactory;

    /**
     * Get the date attribute.
     *
     * @return Attribute
     */
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->format('d.m.Y')
        );
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn(float $value) => number_format($value, 2, ',', '.')
        );
    }

    /**
     * Get the account for the transaction.
     */
    public function transactions(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
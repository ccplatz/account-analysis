<?php

namespace App\Models;

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
        'negative',
        'balance_after'
    ];

    use HasFactory;

    /**
     * Get the account for the transaction.
     */
    public function transactions(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
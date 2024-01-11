<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'account_id',
        'field_name',
        'pattern',
        'exact_match',
        'sequence',
        'category_id',
    ];

    protected $casts = [
        'exact_match' => 'boolean'
    ];

    const FIELD_NAMES = [
        'payment_type' => 'Payment type',
        'name_other_party' => 'Name other party',
        'iban_other_party' => 'IBAN other party',
        'purpose' => 'Purpose'
    ];

    /**
     * Check if rule applies on a transaction.
     *
     * @param  mixed $transaction
     * @return bool
     */
    public function applies(Transaction $transaction): bool
    {
        if ($transaction->account_id !== $this->account_id) {
            return false;
        }

        $pattern = preg_quote($this->pattern, '/');

        if ($this->exact_match) {
            $pattern = '/^' . $pattern . '$/';
            return preg_match($pattern, $transaction->{$this->field_name}) > 0;
        }

        $pattern = '/' . $pattern . '/';
        return preg_match($pattern, $transaction->{$this->field_name}) > 0;
    }

    public function apply(Transaction $transaction): void
    {
        $transaction->category_id = $this->category_id;
        $transaction->update();
    }

    /**
     * Get the category for the import rule.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the account for the import rule.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the user's first name.
     */
    protected function fieldNamePublic(): Attribute
    {
        return Attribute::make(
            get: fn() => self::FIELD_NAMES[$this->field_name],
        );
    }
}

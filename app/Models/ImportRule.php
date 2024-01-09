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

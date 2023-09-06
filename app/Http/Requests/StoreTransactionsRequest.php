<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => 'required|exists:App\Models\File,id',
            'account' => 'required|exists:App\Models\Account,id',
            'date' => 'required|string',
            'name_other_party' => 'required|string',
            'iban_other_party' => 'required|string',
            'payment_type' => 'required|string',
            'purpose' => 'required|string',
            'value' => 'required|string',
            'balance_after' => 'required|string'
        ];
    }
}
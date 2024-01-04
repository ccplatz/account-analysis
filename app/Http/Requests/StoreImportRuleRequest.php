<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportRuleRequest extends FormRequest
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
            'description' => 'required',
            'account_id' => 'required|exists:App\Models\Account,id',
            'field_name' => 'required',
            'pattern' => 'required',
            'exact_match' => 'sometimes|nullable',
            'category_id' => 'required|exists:App\Models\Category,id',
        ];
    }
}

<?php

namespace App\Http\Requests\Api\V1\OrderItems;

use App\Http\Requests\Api\V1\AbstractFormRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRequest extends AbstractFormRequest
{
    public const AMOUNT = 'amount';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            self::AMOUNT => 'required|int',
        ];
    }

    public function getAmount(): int
    {
        return $this->{self::AMOUNT};
    }
}

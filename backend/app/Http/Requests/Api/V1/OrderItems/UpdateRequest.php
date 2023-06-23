<?php

namespace App\Http\Requests\Api\V1\OrderItems;

use App\Http\Requests\Api\V1\AbstractFormRequest;

class UpdateRequest extends AbstractFormRequest
{
    public const AMOUNT = 'amount';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
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

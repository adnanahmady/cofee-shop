<?php

namespace App\Http\Requests\Api\V1\OrderStatuses;

use App\Http\Requests\Api\V1\AbstractFormRequest;
use App\Support\OrderStateDeterminer\Contracts\ChoiceHolderInterface;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRequest extends AbstractFormRequest implements ChoiceHolderInterface
{
    public const FORWARD = 'forward_state';
    public const ROLLBACK = 'rollback_state';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            self::FORWARD => 'boolean|required_without:' . self::ROLLBACK,
            self::ROLLBACK => sprintf(
                'boolean|accepted_if:%s,false',
                self::FORWARD
            ),
        ];
    }

    public function isApprovedToRollback(): bool
    {
        return $this->get(self::ROLLBACK, false);
    }

    public function isApprovedToForward(): bool
    {
        return $this->get(self::FORWARD, false);
    }
}

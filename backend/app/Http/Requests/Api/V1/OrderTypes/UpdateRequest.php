<?php

namespace App\Http\Requests\Api\V1\OrderTypes;

use App\Http\Requests\Api\V1\AbstractFormRequest;
use App\Interfaces\IdInterface;
use App\Repositories\DeliveryTypeRepository;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRequest extends AbstractFormRequest
{
    public const DELIVERY_TYPE = 'delivery_type_id';

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
        ];
    }

    public function getDeliveryType(): IdInterface
    {
        $deliveryTypeRepository = new DeliveryTypeRepository();

        return $deliveryTypeRepository->findById(
            $this->get(self::DELIVERY_TYPE)
        );
    }
}

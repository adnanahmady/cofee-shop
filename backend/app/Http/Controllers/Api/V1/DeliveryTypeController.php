<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\OrderTypes\List;
use App\Repositories\DeliveryTypeRepository;

class DeliveryTypeController extends Controller
{
    public function index(
        DeliveryTypeRepository $typeRepository
    ): List\DataCollection {
        return new List\DataCollection(
            $typeRepository->getAvailable()
        );
    }
}

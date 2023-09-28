<?php

namespace Database\Seeders;

use App\Repositories\DeliveryTypeRepository;
use Illuminate\Database\Seeder;

class DeliveryTypeSeeder extends Seeder
{
    public function __construct(
        private readonly DeliveryTypeRepository $deliveryTypeRepository
    ) {}

    /** Run the database seeds. */
    public function run(): void
    {
        $this->deliveryTypeRepository->create(name: 'get at shop');
        $this->deliveryTypeRepository->create(name: 'receive at home');
    }
}

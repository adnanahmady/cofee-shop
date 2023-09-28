<?php

use App\Models\DeliveryType;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::table(Order::TABLE, function (Blueprint $table): void {
            $table->foreignIdFor(DeliveryType::class, Order::DELIVERY_TYPE);
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table(Order::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(DeliveryType::class, Order::DELIVERY_TYPE);
        });
    }
};

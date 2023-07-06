<?php

use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(Order::TABLE, function (Blueprint $table): void {
            $table->foreignIdFor(OrderStatus::class, Order::STATUS);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Order::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(OrderStatus::class, Order::STATUS);
        });
    }
};

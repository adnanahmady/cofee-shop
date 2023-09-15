<?php

use App\Contracts\Models\OrderItemOptionContract;
use App\Models\Option;
use App\Models\OrderItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration implements OrderItemOptionContract {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table): void {
            $table->id(self::ID);
            $table->foreignIdFor(OrderItem::class, self::ORDER_ITEM);
            $table->foreignIdFor(Option::class, self::OPTION);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(OrderItem::class, self::ORDER_ITEM);
            $table->dropForeignIdFor(Option::class, self::OPTION);
        });
        Schema::dropIfExists(self::TABLE);
    }
};

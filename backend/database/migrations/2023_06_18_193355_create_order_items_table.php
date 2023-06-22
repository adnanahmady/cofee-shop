<?php

use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(OrderItem::TABLE, function (Blueprint $table): void {
            $table->id(OrderItem::ID);
            $table->foreignIdFor(Product::class, OrderItem::PRODUCT);
            $table->foreignIdFor(Order::class, OrderItem::ORDER);
            $table->unsignedSmallInteger(OrderItem::AMOUNT);
            $table->unsignedBigInteger(OrderItem::PRICE);
            $table->foreignIdFor(Currency::class, OrderItem::CURRENCY);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(OrderItem::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(Product::class, OrderItem::PRODUCT);
            $table->dropForeignIdFor(Order::class, OrderItem::ORDER);
            $table->dropForeignIdFor(Currency::class, OrderItem::CURRENCY);
        });
        Schema::dropIfExists(OrderItem::TABLE);
    }
};

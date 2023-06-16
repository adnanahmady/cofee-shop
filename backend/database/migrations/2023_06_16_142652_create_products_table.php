<?php

use App\Models\Currency;
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
        Schema::create(Product::TABLE, function (Blueprint $table): void {
            $table->id(Product::ID);
            $table->string(Product::NAME);
            $table->unsignedBigInteger(Product::PRICE);
            $table->unsignedBigInteger(Product::CURRENCY);
            $table->timestamps();

            $table->foreign([Product::CURRENCY])
                ->references([Currency::ID])
                ->on(Currency::TABLE);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Product::TABLE, function (Blueprint $table): void {
            $table->dropForeign([Product::CURRENCY]);
        });
        Schema::dropIfExists(Product::TABLE);
    }
};

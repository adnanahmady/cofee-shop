<?php

use App\Models\Currency;
use App\Models\Customization;
use App\Models\Option;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Option::TABLE, function (Blueprint $table): void {
            $table->id(Option::ID);
            $table->string(Option::NAME);
            $table->foreignIdFor(Customization::class, Option::CUSTOMIZATION);
            $table->unsignedBigInteger(Option::AMOUNT);
            $table->unsignedBigInteger(Option::PRICE);
            $table->foreignIdFor(Currency::class, Option::CURRENCY);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Option::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(
                Customization::class,
                Option::CUSTOMIZATION
            );
            $table->dropForeignIdFor(Currency::class, Option::CURRENCY);
        });
        Schema::dropIfExists(Option::TABLE);
    }
};

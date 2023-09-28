<?php

use App\Contracts\Models\ProductCustomizationContract;
use App\Models\Customization;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration implements ProductCustomizationContract {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table): void {
            $table->id(self::ID);
            $table->foreignIdFor(Customization::class, self::CUSTOMIZATION);
            $table->foreignIdFor(Product::class, self::PRODUCT);
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(Customization::class, self::CUSTOMIZATION);
            $table->dropForeignIdFor(Product::class, self::PRODUCT);
        });
        Schema::dropIfExists(self::TABLE);
    }
};

<?php

use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(Currency::TABLE, function (Blueprint $table): void {
            $table->id(Currency::ID);
            $table->string(Currency::NAME);
            $table->string(Currency::CODE);
            $table->unsignedTinyInteger(Currency::DECIMAL_PLACES)->default(0);
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists(Currency::TABLE);
    }
};

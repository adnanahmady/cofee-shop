<?php

use App\Models\DeliveryType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(DeliveryType::TABLE, function (Blueprint $table): void {
            $table->id(DeliveryType::ID);
            $table->string(DeliveryType::NAME);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(DeliveryType::TABLE);
    }
};

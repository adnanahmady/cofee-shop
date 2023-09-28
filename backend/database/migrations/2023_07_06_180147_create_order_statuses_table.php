<?php

use App\Models\OrderStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(OrderStatus::TABLE, function (Blueprint $table): void {
            $table->id(OrderStatus::ID);
            $table->string(OrderStatus::NAME);
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists(OrderStatus::TABLE);
    }
};

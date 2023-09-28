<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(Order::TABLE, function (Blueprint $table): void {
            $table->id(Order::ID);
            $table->foreignIdFor(User::class, Order::USER);
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table(Order::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(User::class, Order::USER);
        });
        Schema::dropIfExists(Order::TABLE);
    }
};

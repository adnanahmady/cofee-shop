<?php

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
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(Option::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(Customization::class, Option::CUSTOMIZATION);
        });
        Schema::dropIfExists(Option::TABLE);
    }
};

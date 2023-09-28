<?php

use App\Models\Ability;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(Ability::TABLE, function (Blueprint $table): void {
            $table->id(Ability::ID);
            $table->string(Ability::NAME);
            $table->string(Ability::SLUG)->unique();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists(Ability::TABLE);
    }
};

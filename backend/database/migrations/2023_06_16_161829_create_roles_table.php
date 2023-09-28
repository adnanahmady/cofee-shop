<?php

use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(Role::TABLE, function (Blueprint $table): void {
            $table->id(Role::ID);
            $table->string(Role::NAME);
            $table->string(Role::SLUG)->unique();
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists(Role::TABLE);
    }
};

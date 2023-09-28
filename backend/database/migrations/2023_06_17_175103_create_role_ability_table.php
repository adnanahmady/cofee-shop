<?php

use App\Contracts\Models\RoleAbilityContract;
use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration implements RoleAbilityContract {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table): void {
            $table->id(self::ID);
            $table->foreignIdFor(Role::class, self::ROLE);
            $table->foreignIdFor(Ability::class, self::ABILITY);
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(Role::class, self::ROLE);
            $table->dropForeignIdFor(Ability::class, self::ABILITY);
        });
        Schema::dropIfExists(self::TABLE);
    }
};

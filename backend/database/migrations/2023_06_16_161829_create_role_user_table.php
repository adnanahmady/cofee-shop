<?php

use App\Contracts\Models\RoleUserContract;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration implements RoleUserContract {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(self::TABLE, function (Blueprint $table): void {
            $table->id(self::ID);
            $table->foreignIdFor(Role::class, self::ROLE);
            $table->foreignIdFor(User::class, self::USER);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(self::TABLE, function (Blueprint $table): void {
            $table->dropForeignIdFor(Role::class, self::ROLE);
            $table->dropForeignIdFor(User::class, self::USER);
        });
        Schema::dropIfExists(Role::TABLE);
    }
};

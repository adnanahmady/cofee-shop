<?php

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create(Address::TABLE, function (Blueprint $table): void {
            $table->id(Address::ID);
            $table->string(Address::TITLE);
            $table->foreignIdFor(User::class, Address::USER);
            $table->string(Address::CITY);
            $table->string(Address::STREET);
            $table->string(Address::PLATE_NUMBER);
            $table->string(Address::POSTAL_CODE);
            $table->string(Address::DESCRIPTION);
            $table->timestamps();
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists(Address::TABLE);
    }
};

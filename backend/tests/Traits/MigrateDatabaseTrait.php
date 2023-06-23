<?php

namespace Tests\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

trait MigrateDatabaseTrait
{
    protected function migrateDatabaseSetup(): void
    {
        $this->artisan('migrate:fresh');
        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Closes the database connection after the test is done.
     * If the database connection remain open for each test
     * after a while the tests may reach the database maximum
     * connection number. therefore it's important to close
     * the database connection after each test is done.
     */
    protected function migrateDatabaseTearDown(): void
    {
        DB::disconnect(config('database.connection'));
    }
}

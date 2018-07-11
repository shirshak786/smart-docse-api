<?php

use Illuminate\Database\Seeder;
use Modules\Meta\Database\Seeders\MetaDatabaseSeeder;
use Modules\Post\Database\Seeders\PostDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(UserDatabaseSeeder::class);
        $this->call(MetaDatabaseSeeder::class);
        $this->call(PostDatabaseSeeder::class);
    }
}

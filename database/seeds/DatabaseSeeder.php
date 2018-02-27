<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(MinistriesTableSeeder::class);
    }
}

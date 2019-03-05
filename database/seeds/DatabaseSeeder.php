<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $this->call(MemberRolesTableSeeder::class);
        $this->call(MemberStatusTableSeeder::class);
        $this->call(MembersTableSeeder::class);
        $this->call(MinistriesTableSeeder::class);
    }
}

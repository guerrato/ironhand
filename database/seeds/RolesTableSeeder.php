<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'description' => 'member'
            ],
            [
                'id' => 2,
                'description' => 'coordinator'
            ],
            [
                'id' => 3,
                'description' => 'administator'
            ]
        ];

        foreach ($data as $key => $value) {
            $data[$key]['slug'] = str_slug($data[$key]['description'], '-');
        }

        DB::table('roles')->insert($data);
    }
}

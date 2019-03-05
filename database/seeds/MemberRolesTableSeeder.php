<?php

use Illuminate\Database\Seeder;

class MemberRolesTableSeeder extends Seeder
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
                'description' => 'Member'
            ],
            [
                'id' => 2,
                'description' => 'Coordinator'
            ],
            [
                'id' => 3,
                'description' => 'Administrator'
            ]
        ];

        foreach ($data as $key => $value) {
            $data[$key]['slug'] = str_slug($data[$key]['description'], '-');
        }

        DB::table('member_roles')->insert($data);
    }
}

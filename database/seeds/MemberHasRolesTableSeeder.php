<?php

use Illuminate\Database\Seeder;

class MemberHasRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $member_roles = [];
        $members = DB::table('members')->get()->toArray();
        $ministries = DB::table('ministries')->where('id', '>', 1)->get()->toArray();
        $ministry_ids = array_column($ministries, 'id');

        foreach ($members as $mbr) {
            $role = rand(1, 4);

            $member_roles[] = [
                'member_id' => $mbr->id,
                'ministry_id' => 1,
                'role_id' => $role,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $ministry_taken = [1];
            $role_taken = [$role];
            $ministry = 0;

            for ($x=0; $x < rand(1, 2); $x++) {
                do {
                    $ministry = $ministry_ids[array_rand($ministry_ids)];
                    $key = array_search($ministry, array_column($ministries, 'id'));
                } while (in_array($ministry, $ministry_taken) || ($ministries[$key]->required_gender != null && ($mbr->gender != $ministries[$key]->required_gender)));

                do {
                    $role = rand(1, 3);
                } while (in_array($role, $role_taken));

                $member_roles[] = [
                    'member_id' => $mbr->id,
                    'ministry_id' => $ministry,
                    'role_id' => $role,
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];


                $ministry_taken[] = $ministry;
                $role_taken[] = $role;
            }
        }

        DB::table('member_has_roles')->insert($member_roles);
    }
}

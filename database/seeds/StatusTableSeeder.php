<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
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
                'description' => 'active'
            ],
            [
                'id' => 2,
                'description' => 'traveling'
            ]
        ];
        
        foreach ($data as $key => $value) {
            $data[$key]['slug'] = str_slug($data[$key]['description'], '-');
        }

        DB::table('status')->insert($data);
    }
}

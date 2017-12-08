<?php

use Illuminate\Database\Seeder;

class DemographicsTableSeeder extends Seeder
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
                'description' => 'For ministry members',
                'note' => 'For all members that is registered in the ministry.'
            ],
            [
                'id' => 2,
                'description' => 'For women',
                'note' => 'For all women that is registered in the database.'
            ],
            [
                'id' => 3,
                'description' => 'For men',
                'note' => 'For all men that is registered in the database.'
            ],
            [
                'id' => 4,
                'description' => 'For church',
                'note' => 'For all who are in the church.'
            ]
        ];

        foreach ($data as $key => $value) {
            $data[$key]['slug'] = str_slug($data[$key]['description'], '-');
            $data[$key]['created_at'] = date("Y-m-d H:i:s");
            $data[$key]['updated_at'] = date("Y-m-d H:i:s");
        }
        
        DB::table('demographics')->insert($data);
    }
}

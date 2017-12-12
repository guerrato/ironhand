<?php

use Illuminate\Database\Seeder;

use App\User;

class MinistriesTableSeeder extends Seeder
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
                'name'                   => 'Celebra Conflurir',
                'description'            => 'Nobis cumque aspernatur qui. Voluptatem repellendus ducimus consectetur quia facere et omnis. Quia odio pariatur fugiat error sequi voluptas. Porro ut omnis ullam eveniet.',
                'required_gender'        => null,
                'coordinator_id'         => '',
                'default_demographic_id' => 1
            ],
            [
                'name'                   => 'Celebra Mulheres',
                'description'            => 'Inventore corporis est qui placeat nesciunt consequuntur voluptatibus dolores. Recusandae minus officia molestias. Ut quo natus saepe porro.',
                'required_gender'        => 'female',
                'coordinator_id'         => '',
                'default_demographic_id' => 2
            ],
            [
                'name'                   => 'Celebra Jovens',
                'description'            => 'Rerum nulla fuga repellendus odit quasi ut et praesentium. Veniam eum ipsum id delectus ipsum. Alias ut voluptas eius dicta provident ratione rem rerum.',
                'required_gender'        => null,
                'coordinator_id'         => '',
                'default_demographic_id' => 1
            ],
            [
                'name'                   => 'Celebra Homens',
                'description'            => 'Aut in dicta fugit. Voluptatem quasi iste cumque vero fuga rerum. Autem aspernatur quibusdam non omnis nihil quis aut necessitatibus. Corporis voluptatum saepe. Sequi et facilis expedita alias sit quod.',
                'required_gender'        => 'male',
                'coordinator_id'         => '',
                'default_demographic_id' => 3
            ],
        ];
        
        foreach ($data as $key => $value) {
            $user = new User();
            $data[$key]['coordinator_id'] = array_rand(array_column($user->whereIn('role_id', [2,3])->get()->toArray(), 'id'));
            $data[$key]['slug'] = str_slug($data[$key]['name'], '-');
            $data[$key]['created_at'] = date("Y-m-d H:i:s");
            $data[$key]['updated_at'] = date("Y-m-d H:i:s");
        }

        DB::table('ministries')->insert($data);
    }
}

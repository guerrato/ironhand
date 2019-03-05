<?php

use Illuminate\Database\Seeder;

class MemberStatusTableSeeder extends Seeder
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
                'description' => 'Activated',
                'note' => 'Indica que o usuário está ativo para ser alocado em grupos e eventos e ser considerado em relatórios.'
            ],
            [
                'id' => 2,
                'description' => 'Traveling',
                'note' => 'Indica que o usuário está viajando por longo período ou está no exterior, portanto não está disponível para ser alocado em grupos e eventos ou ser considerado em relatórios.'
            ],
            [
                'id' => 3,
                'description' => 'Transfered',
                'note' => 'Indica que o usuário foi transferido para outra comunidade.'
            ]
        ];
        
        foreach ($data as $key => $value) {
            $data[$key]['slug'] = str_slug($data[$key]['description'], '-');
        }

        DB::table('member_status')->insert($data);
    }
}

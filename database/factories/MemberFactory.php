<?php

use Faker\Generator as Faker;
use App\Helpers\Utils;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Member::class, function (Faker $faker) {
    static $password;

    $data = [
        'name' => $faker->name,
        'email' => $faker->unique()->email(),
        'nickname' => $faker->lastName,
        'birthdate' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'image' => $faker->imageUrl(256, 256, 'people'),
        'image_name' => 'file.jpg', 
        'gender' => rand(1, 2),
        'phone' => $faker->tollFreePhoneNumber,
        'whatsapp' => $faker->tollFreePhoneNumber,
        'facebook' => $faker->url,
        'uuid' => Utils::generateUuid(),
        'role_id' => rand(1, 3)
    ];

    $data['image_name'] = explode('?', $data['image']);
    $data['image_name'] = $data['image_name'][count($data['image_name']) - 1] . '.jpg';

    return $data;
});

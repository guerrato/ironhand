<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

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
        'image' => null,
        'image_name' => null,
        'gender' => rand(1, 2),
        'phone' => $faker->tollFreePhoneNumber,
        'whatsapp' => $faker->tollFreePhoneNumber,
        'facebook' => $faker->url,
        'uuid' => (string) Str::uuid()
    ];

    return $data;
});

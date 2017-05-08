<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Template::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Page::class, function (Faker\Generator $faker) {
    return [
        'width' => rand(0, 1000),
        'height' => rand(0, 1000),
    ];
});

$factory->define(App\TextElement::class, function (Faker\Generator $faker) {
    return [
        'type' => 'text_element',
        'width' => rand(0, 300),
        'height' => rand(0, 300),
        'positionX' => rand(0, 300),
        'positionY' => rand(0, 300),
    ];
});

$factory->define(App\ImageElement::class, function (Faker\Generator $faker) {
    return [
        'type' => 'image_element',
        'width' => rand(0, 300),
        'height' => rand(0, 300),
        'positionX' => rand(0, 300),
        'positionY' => rand(0, 300),
    ];
});

$factory->define(App\FrameElement::class, function (Faker\Generator $faker) {
    return [
        'type' => 'frame_element',
        'width' => rand(0, 300),
        'height' => rand(0, 300),
        'positionX' => rand(0, 300),
        'positionY' => rand(0, 300),
    ];
});

$factory->define(App\TableElement::class, function (Faker\Generator $faker) {
    return [
        'type' => 'table_element',
        'width' => rand(0, 300),
        'height' => rand(0, 300),
        'positionX' => rand(0, 300),
        'positionY' => rand(0, 300),
    ];
});

$factory->define(App\Album::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'public' => true
    ];
});

$factory->define(App\Image::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'image_key' => rand(0,1000000),
    ];
});
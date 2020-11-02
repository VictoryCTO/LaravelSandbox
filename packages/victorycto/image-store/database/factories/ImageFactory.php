<?php
namespace victorycto\ImageStore\Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use victorycto\ImageStore\Models\Image;
use Faker\Generator as Faker;

$factory->define(Image::class, function (Faker $faker) {
    return [
        'origin_url' => $faker->imageUrl(),
        'small_url' => $faker->imageUrl(),
        'thumbnail_url' => $faker->imageUrl()
    ];
});

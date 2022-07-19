<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'company_id' => 1,
        'product_name' => $faker->name,
        'price' => $faker->randomDigit,
        'stock' => $faker->randomDigit,
        'comment' => $faker->realText,
    ];
});

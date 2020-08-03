<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Seller;
use Faker\Generator as Faker;

$factory->define(Seller::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name,
        'nascimento' => $faker->date('d/m/Y'),
        'inicio_trabalho' => $faker->date('d/m/Y'),
        
    ];
});

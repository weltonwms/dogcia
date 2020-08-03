<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Produto;
use Faker\Generator as Faker;

$factory->define(Produto::class, function (Faker $faker) {
    $qtd=$faker->numberBetween(20,1000);
    return [
        'nome' => $faker->unique()->state,
        'ser_vivo'=>0,
        'grandeza'=>1,
        'valor_grandeza'=>12,
        'margem'=>10,
        
        
    ];
});

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Produto;
use Faker\Generator as Faker;

$factory->define(Produto::class, function (Faker $faker) {
    $qtd=$faker->numberBetween(20,1000);
    return [
        'nome' => $faker->unique()->state,
        'valor_aluguel' => $faker->randomNumber(2),
        'valor_venda' => $faker->randomNumber(2),
        'qtd_estoque' => $qtd,
        'qtd_disponivel'=> $qtd
        
    ];
});

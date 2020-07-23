<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cliente;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
   
    return [
        'nome' => $faker->unique()->name,
        'email' => $faker->unique()->safeEmail,
        'telefone' => $faker->phoneNumber,
        'nascimento' => $faker->date('d/m/Y'),
        'endereco' => $faker->address,
    ];
});

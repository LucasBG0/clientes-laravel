<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cliente;
use Faker\Generator as Faker;

$factory->define(Cliente::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail
    ];
});

$factory->afterCreating(Cliente::class, function ($cliente, $faker) {
    $cliente->tag($faker->name);
});

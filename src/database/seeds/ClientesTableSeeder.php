<?php

use App\Cliente;
use Illuminate\Database\Seeder;

class ClientesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cliente::truncate();

        $faker = \Faker\Factory::create();

        // And now, let's create a few clientes in our database:
        for ($i = 0; $i < 50; $i++) {
            Cliente::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
            ])->retag($faker->name);
        }        
    }
}

<?php

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
        \App\Cliente::create([
            'nome'=>"Cliente Balcão",
            'telefone'=>"(00) 0000-0000"
            
        ]);
        factory(App\Cliente::class,50)->create();
    }
}

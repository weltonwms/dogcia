<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'=>"Welton Moreira",
            'email'=>"welton_wms@yahoo.com.br",
            'username'=>'weltonwms',
            'password'=>bcrypt('123456'),
            'remember_token' => Str::random(10),
        ]);
    }
}

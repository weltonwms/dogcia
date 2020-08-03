<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cliente_id');
            $table->date('data_venda');
            $table->string('observacao')->nullable();
            $table->boolean('frete');
            $table->boolean('carteira');
            $table->unsignedTinyInteger('status'); //1:Pago;2:Não Pago
            $table->unsignedTinyInteger('forma_pagamento'); //1:Dinheiro;2:Cartão Crédito;3:Cartão Débito
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();
            
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('seller_id')->references('id')->on('sellers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendas');
    }
}

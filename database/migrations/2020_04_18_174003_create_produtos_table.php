<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('ser_vivo');
            $table->unsignedTinyInteger('grandeza'); //1:Kilo;2:Litro;3:Unitário
            $table->float('valor_grandeza',8,2)->nullable(); //null para unitário
            $table->float('margem',8,2); //% sobre custo médio
            $table->float('granel',8,2)->default(0);
            $table->decimal('custo_medio', 10, 2)->default(0); //preenchimento automático baseado em compras
            $table->decimal('valor_venda', 10, 2)->default(0);
            $table->integer('qtd_estoque')->unsigned()->default(0);
           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}

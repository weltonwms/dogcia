<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoVendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_venda', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('venda_id');
            $table->boolean('granel');
            $table->float('qtd');
            $table->float('desconto',8,2)->default(0); //desconto dado pelo vendedor
            $table->decimal('custo_medio', 10, 3); //histórico momento custo
            $table->decimal('valor_venda', 10, 2);
            $table->timestamps();
            
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_venda');
    }
}

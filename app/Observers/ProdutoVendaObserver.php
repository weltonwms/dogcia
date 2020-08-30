<?php

namespace App\Observers;

use App\ProdutoVenda;
use App\Helpers\ProdutoHelper;

class ProdutoVendaObserver
{
    private static $vendaBeforeSave;
    private static $vendaBeforeDeleted;

    public function created(ProdutoVenda $venda)
    {
        //exit('event creadte');
        $produto=\App\Produto::find($venda->produto_id);
        $produto->qtd_estoque-=$venda->qtd;
        $produto->save();

    }

    public function updating(ProdutoVenda $venda)
    {
        self::$vendaBeforeSave =ProdutoVenda::where('produto_id',$venda->produto_id)
            ->where('venda_id',$venda->venda_id)
            ->first();
        
    }

   
    public function updated(ProdutoVenda $venda)
    {
        $produto=\App\Produto::find($venda->produto_id);
        $produto->qtd_estoque-= $venda->qtd - self::$vendaBeforeSave->qtd;
        $produto->save();

    }


    public function deleting(ProdutoVenda $venda)
    {
        self::$vendaBeforeDeleted =ProdutoVenda::where('produto_id',$venda->produto_id)
        ->where('venda_id',$venda->venda_id)
        ->first();
        \Log::info('Deleting ProdutoVendaObserver acionado. Pvenda qtd: '.self::$vendaBeforeDeleted->qtd);

    }
   
    public function deleted(ProdutoVenda $venda)
    {
        $produto=\App\Produto::find($venda->produto_id);
        $qtdApagada=self::$vendaBeforeDeleted->qtd;
        $total=self::$vendaBeforeDeleted->custo_medio*$qtdApagada;

        $produto->setCustoMedioOnEvent($total,$qtdApagada);
        $produto->qtd_estoque+= $qtdApagada;
        \Log::info('deleted ProdutoVendaObserver acionado. Pvenda '.\json_encode(self::$vendaBeforeDeleted));
        
        $produto->save();
    }

   

   
}

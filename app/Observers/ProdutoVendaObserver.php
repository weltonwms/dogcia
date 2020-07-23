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
        $produto=\App\Produto::find($venda->produto_id);
        $produto->qtd_estoque-=$venda->qtd;
        $produto->qtd_disponivel-=$venda->qtd;
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
        $produto->qtd_disponivel-= $venda->qtd - self::$vendaBeforeSave->qtd;
        $produto->save();

    }


    public function deleting(ProdutoVenda $venda)
    {
        self::$vendaBeforeDeleted =ProdutoVenda::where('produto_id',$venda->produto_id)
        ->where('venda_id',$venda->venda_id)
        ->first();

    }
   
    public function deleted(ProdutoVenda $venda)
    {
        $produto=\App\Produto::find($venda->produto_id);
        $qtdApagada=self::$vendaBeforeDeleted->qtd;
       
        $produto->qtd_estoque+= $qtdApagada;
        $produto->qtd_disponivel+= $qtdApagada;

        $produto->save();
    }

   

   
}

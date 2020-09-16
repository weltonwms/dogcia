<?php

namespace App\Observers;

use App\ProdutoVenda;
/**
 * Por enquanto Vendas a Granel não está influencinado no Custo Médio.
 * É verdade que há impactos mínimos se não considerar, mas considerar a Granel aqui
 * Teria que considerar em Compras Também.
 */
class ProdutoVendaObserver
{
    private static $vendaBeforeSave;
    private static $vendaBeforeDeleted;

    public function created(ProdutoVenda $venda)
    {
        $produto=\App\Produto::find($venda->produto_id);
        if($venda->granel==1){
            $produto->granel-=$venda->qtd;
        }
        else{
            $produto->qtd_estoque-=$venda->qtd;
        }
        
        $produto->save();
        \Log::info('created produto id: '.$produto->id);
    }

    public function updating(ProdutoVenda $venda)
    {
        self::$vendaBeforeSave =ProdutoVenda::where('produto_id',$venda->produto_id)
            ->where('venda_id',$venda->venda_id)
            ->first();
        
    }

   
    public function updated(ProdutoVenda $venda)
    {
        //Por enquanto não permitir trocar de granel para não granel ou vice-versa
        if($venda->granel!=self::$vendaBeforeSave->granel){
            throw new \Exception('Troca Granel Inválida');
            return false;
        }
        $produto=\App\Produto::find($venda->produto_id);
        if($venda->granel==1){
            $qtdVendaDiferenca= self::$vendaBeforeSave->qtd  - $venda->qtd;
           
            $produto->granel+= $qtdVendaDiferenca;
        }
        else{
             //análise das diferenças
            $totalCustoDiferenca= self::$vendaBeforeSave->getCustoTotal() - $venda->getCustoTotal();
            $qtdVendaDiferenca= self::$vendaBeforeSave->qtd  - $venda->qtd;
        
            \Log::info('totalCustoDiferenca: '.$totalCustoDiferenca);
            \Log::info('qtdVendaDiferenca: '.$qtdVendaDiferenca);
        
            //Novo custo médio baseado na diferença
            $produto->setCustoMedioOnEvent($totalCustoDiferenca, $qtdVendaDiferenca);
            $produto->qtd_estoque+= $qtdVendaDiferenca;
        }
       
        $produto->save();
        \Log::info('updated produto id: '.$produto->id);
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
        $isGranel=self::$vendaBeforeDeleted->granel==1;
        if($isGranel){
            $produto->granel+= $qtdApagada;
        }
        else{
            $total=self::$vendaBeforeDeleted->custo_medio*$qtdApagada;
            $produto->setCustoMedioOnEvent($total,$qtdApagada);
            $produto->qtd_estoque+= $qtdApagada;
        }
       
        \Log::info('deleted ProdutoVendaObserver acionado. Pvenda '.\json_encode(self::$vendaBeforeDeleted));
        
        $produto->save();
    }

   

   
}

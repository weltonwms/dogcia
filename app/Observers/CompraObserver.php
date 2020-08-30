<?php

namespace App\Observers;

use App\Compra;

class CompraObserver
{

    private static $compraBeforeSave;

    /**
     * Handle the compra "created" event.
     *
     * @param  \App\Compra  $compra
     * @return void
     */
    public function created(Compra $compra)
    {
        
        $this->addCompra($compra);
        $produto=$compra->produto;
        \Log::info('created -  Id: '.$compra->id.' produto '.$produto->id."  custo medio: ".$produto->custo_medio);
    }

    public function updating(Compra $compra)
    {
        self::$compraBeforeSave =Compra::find($compra->id);
       
    }

    /**
     * Handle the compra "updated" event.
     *
     * @param  \App\Compra  $compra
     * @return void
     */
    public function updated(Compra $compra)
    {
        if($compra->produto->id!=self::$compraBeforeSave->produto->id):
            $this->desfazerCompra(self::$compraBeforeSave);
            $this->addCompra($compra);
        else:
            $this->updateCompra(self::$compraBeforeSave,$compra);
        endif;
            
    }

    /**
     * Handle the compra "deleted" event.
     *
     * @param  \App\Compra  $compra
     * @return void
     */
    public function deleted(Compra $compra)
    {
        
        $this->desfazerCompra($compra);
        \Log::info('deteted -  Id: '.$compra->id);
    }

    private function desfazerCompra(Compra $compra)
    {
        $produto=$compra->produto; //produto da Compra deletada
        $produto->setCustoMedioOnEvent(-$compra->getTotal(), -$compra->qtd); //novo custo médio
        $produto->qtd_estoque-=$compra->qtd; //novo estoque
        $produto->save();
    }

    private function addCompra(Compra $compra)
    {
        $produto= $compra->produto; //produto da compra
        $produto->setCustoMedioOnEvent($compra->getTotal(), $compra->qtd); //novo custo médio
        $produto->qtd_estoque+=$compra->qtd; //novo estoque
        $produto->save();
    }

    private function updateCompra(Compra $compraBefore, Compra $compra)
    {
        $produto= $compra->produto; //produto da compra
        //análise das diferenças
        $totalCompraDiferenca=$compra->getTotal() - $compraBefore->getTotal();
        $qtdCompraDiferenca= $compra->qtd - $compraBefore->qtd;
        //Novo custo médio baseado na diferença
        $produto->setCustoMedioOnEvent($totalCompraDiferenca, $qtdCompraDiferenca);

        $produto->qtd_estoque+= $qtdCompraDiferenca;//add diferença do atual pelo anterior
        $produto->save();
    }
}

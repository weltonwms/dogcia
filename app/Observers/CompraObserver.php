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
        $this->desfazerCompra(self::$compraBeforeSave);
        \Log::info('updating -  Id: '.$compra->id." qtd antes salvar: ".self::$compraBeforeSave->qtd);
    }

    /**
     * Handle the compra "updated" event.
     *
     * @param  \App\Compra  $compra
     * @return void
     */
    public function updated(Compra $compra)
    {
        $this->addCompra($compra);
        \Log::info('updated -  Id: '.$compra->id." qtd após salvar: ".$compra->qtd);
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
        $produto->setCustoMedioOnRemoveCompra($compra); //novo custo médio
        $produto->qtd_estoque-=$compra->qtd; //novo estoque
        $produto->save();
    }

    private function addCompra(Compra $compra)
    {
        $produto= $compra->produto; //produto da compra
        $produto->setCustoMedioOnAddCompra($compra); //novo custo médio
        $produto->qtd_estoque+=$compra->qtd; //novo estoque
        $produto->save();
    }
}

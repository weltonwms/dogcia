<?php

namespace App\Observers;

use App\ProdutoRent;
use App\Helpers\ProdutoHelper;

class ProdutoRentObserver
{
    /**
     * Handle the rent "created" event.
     *
     * @param  \App\ProdutoRent $rent
     * @return void
     */
    public function created(ProdutoRent $rent)
    {
        ProdutoHelper::updateQtdDisponivelByProdutoId($rent->produto_id);
        \Log::info('created - Produto Id: '.$rent->produto_id." rent id: ". $rent->rent_id);
    }

    /**
     * Handle the rent "updated" event.
     *
     * @param  \App\ProdutoRent $rent
     * @return void
     */
    public function updated(ProdutoRent $rent)
    {
        ProdutoHelper::updateQtdDisponivelByProdutoId($rent->produto_id);
        \Log::info('updated - Produto Id: '.$rent->produto_id." rent id: ". $rent->rent_id);

    }

    /**
     * Handle the rent "deleted" event.
     *
     * @param  \App\ProdutoRent $rent
     * @return void
     */
    public function deleted(ProdutoRent $rent)
    {
        ProdutoHelper::updateQtdDisponivelByProdutoId($rent->produto_id);
        \Log::info('delete - Produto Id: '.$rent->produto_id." rent id: ". $rent->rent_id);
        //dd($rent);
    }

    /**
     * Handle the rent "restored" event.
     *
     * @param  \App\ProdutoRent $rent
     * @return void
     */
    public function restored(ProdutoRent $rent)
    {
        //
    }

    /**
     * Handle the rent "force deleted" event.
     *
     * @param  \App\ProdutoRent $rent
     * @return void
     */
    public function forceDeleted(ProdutoRent $rent)
    {
        //
    }

   
}

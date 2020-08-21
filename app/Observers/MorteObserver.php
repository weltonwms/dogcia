<?php

namespace App\Observers;

use App\Morte;

class MorteObserver
{
    /**
     * Handle the morte "created" event.
     *
     * @param  \App\Morte  $morte
     * @return void
     */
    public function created(Morte $morte)
    {
        \Log::info(\json_encode($morte));
        $this->addMorte($morte);
        
    }

    public function updating(Morte $morte)
    {
        $morteBeforeSave =Morte::find($morte->id);
        $this->desfazerMorte($morteBeforeSave);
    }

    /**
     * Handle the morte "updated" event.
     *
     * @param  \App\Morte  $morte
     * @return void
     */
    public function updated(Morte $morte)
    {
        $this->addMorte($morte);
    }

    /**
     * Handle the morte "deleted" event.
     *
     * @param  \App\Morte  $morte
     * @return void
     */
    public function deleted(Morte $morte)
    {
        \Log::info(\json_encode($morte));
        $this->desfazerMorte($morte);
    }

    private function desfazerMorte(Morte $morte)
    {
        $produto=$morte->produto; //produto da Morte deletada
        $produto->setCustoMedioOnDesfazerMorte($morte); //novo custo médio
        $produto->qtd_estoque+=$morte->qtd; //novo estoque
        
        $produto->save();
    }

    private function addMorte(Morte $morte)
    {
        $produto= $morte->produto; //produto da morte
       
        $produto->qtd_estoque-=$morte->qtd; //novo estoque
       
        $produto->save();
    }

    
}

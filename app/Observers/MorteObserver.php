<?php

namespace App\Observers;

use App\Morte;

class MorteObserver
{
    private static $morteBeforeSave;

    /**
     * Handle the morte "created" event.
     *
     * @param  \App\Morte  $morte
     * @return void
     */
    public function created(Morte $morte)
    {
       $this->addMorte($morte);
       \Log::info(\json_encode($morte));
        
    }

    public function updating(Morte $morte)
    {
        self::$morteBeforeSave =Morte::find($morte->id);
        //$this->desfazerMorte($morteBeforeSave);
    }

    /**
     * Handle the morte "updated" event.
     *
     * @param  \App\Morte  $morte
     * @return void
     */
    public function updated(Morte $morte)
    {
        if($morte->produto->id!=self::$morteBeforeSave->produto->id):
            $this->desfazerMorte(self::$morteBeforeSave);
            $this->addMorte($morte);
        else:
            $this->updateMorte(self::$morteBeforeSave,$morte);
        endif;
        
    }

    /**
     * Handle the morte "deleted" event.
     *
     * @param  \App\Morte  $morte
     * @return void
     */
    public function deleted(Morte $morte)
    {
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

    private function updateMorte(Morte $morteBefore, Morte $morte)
    {
        $produto= $morte->produto; //produto da morte
        //$produto->setCustoMedioOnUpdateMorte($morteBefore, $morte);
        $produto->qtd_estoque-= ($morte->qtd-$morteBefore->qtd);//add diferença do atual pelo anterior
        $produto->save();
    }

    
}

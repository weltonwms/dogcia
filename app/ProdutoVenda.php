<?php

namespace App;


use Illuminate\Database\Eloquent\Relations\Pivot;

class ProdutoVenda extends Pivot
{
   // public $incrementing = true;

    public function getTotal(){
        return $this->valor_venda * $this->qtd;
    }

    public function getTotalFormatado(){
        return "R$ ".number_format($this->getTotal(),2,",",".");
    }

    public function getValorFormatado(){
        return "R$ ".number_format($this->valor_venda,2,",",".");
    }

   
}

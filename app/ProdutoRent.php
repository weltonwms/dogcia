<?php

namespace App;


use Illuminate\Database\Eloquent\Relations\Pivot;

class ProdutoRent extends Pivot
{
    public function getTotal(){
        return $this->valor_aluguel * $this->qtd;
    }

    public function getTotalFormatado(){
        return "R$ ".number_format($this->getTotal(),2,",",".");
    }

    public function getValorFormatado(){
        return "R$ ".number_format($this->valor_aluguel,2,",",".");
    }

   
}

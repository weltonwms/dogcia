<?php

namespace App;


use Illuminate\Database\Eloquent\Relations\Pivot;

class ProdutoVenda extends Pivot
{
   // public $incrementing = true;

   public function produto(){
      return $this->belongsTo('App\Produto');
   }

    public function getTotal(){
        return $this->getValorVendaComDesconto() * $this->qtd;
    }

    public function  getCustoTotal(){
        return $this->custo_medio * $this->qtd;
    }

    public function getValorVendaComDesconto(){
        $tx= 1- ($this->desconto/100);
        return $this->valor_venda*$tx;
    }

    public function getTotalFormatado(){
        return "R$ ".number_format($this->getTotal(),2,",",".");
    }

    public function getValorFormatado(){
        return "R$ ".number_format($this->getValorVendaComDesconto(),2,",",".");
    }

   
}

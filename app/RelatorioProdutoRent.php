<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProdutoRent;

class RelatorioProdutoRent extends Model
{
  
    public $items=[];
    public $total;
    public $totalQtd;

   
    public function getRelatorio()
    {
        $query = ProdutoRent::join('rents', 'rents.id', '=', 'produto_rent.rent_id')
                    ->join('produtos', 'produtos.id', '=', 'produto_rent.produto_id')
                    ->join('clientes', 'clientes.id', '=', 'rents.cliente_id')
                     ->selectRaw('produto_rent.*, produtos.nome as produto_nome,
                      clientes.nome as cliente_nome, 
                      rents.data_saida, rents.data_retorno,
                      (produto_rent.valor_aluguel * qtd) as total');
                    
        if (request('cliente_id')):
            $query->whereIn('cliente_id', request('cliente_id'));
        endif;

        if (request('produto_id')):
            $query->whereIn('produto_id', request('produto_id'));
        endif;

        if ( is_numeric(request('status'))  ):
            $query->where('produto_rent.devolvido', request('status'));
        endif;

        if (request('data_saida1')):
            $dt =request('data_saida1');
            $query->where('data_saida', '>=', $dt);
        endif;

        if (request('data_saida2')):
            $dt =request('data_saida2');
            $query->where('data_saida', '<=', $dt);
        endif;

        if (request('data_retorno1')):
            $dt =request('data_retorno1');
            $query->where('data_retorno', '>=', $dt);
        endif;

        if (request('data_retorno2')):
            $dt =request('data_retorno2');
            $query->where('data_retorno', '<=', $dt);
        endif;


        //$this->items=$query->groupBy('rent_id')->get();
        $this->items=$query->get();
        $this->calcTotalGeral();
        
        return $this;
    }

    private function calcTotalGeral()
    {
        $total=0;
        $totalQtd=0;
       
        foreach ($this->items as $item):
           $total+=$item->total;
           $totalQtd+=$item->qtd;
        endforeach;
       
        $this->total=$total;
        $this->totalQtd=$totalQtd;
        
    }
}

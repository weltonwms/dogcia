<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProdutoVenda;

class RelatorioProdutoVenda extends Model
{
  
    public $items=[];
    public $total;
    public $totalQtd;

   
    public function getRelatorio()
    {
        $query = ProdutoVenda::join('vendas', 'vendas.id', '=', 'produto_venda.venda_id')
                    ->join('produtos', 'produtos.id', '=', 'produto_venda.produto_id')
                    ->join('clientes', 'clientes.id', '=', 'vendas.cliente_id')
                     ->selectRaw('produto_venda.*, produtos.nome as produto_nome,
                      clientes.nome as cliente_nome, 
                      vendas.data_venda, 
                      (produto_venda.valor_venda * qtd) as total');
                    
        if (request('cliente_id')):
            $query->whereIn('cliente_id', request('cliente_id'));
        endif;

        if (request('produto_id')):
            $query->whereIn('produto_id', request('produto_id'));
        endif;

       
        if (request('data_venda1')):
            $dt =request('data_venda1');
            $query->where('data_venda', '>=', $dt);
        endif;

        if (request('data_venda2')):
            $dt =request('data_venda2');
            $query->where('data_venda', '<=', $dt);
        endif;

        //$this->items=$query->groupBy('venda_id')->get();
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

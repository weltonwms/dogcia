<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Venda;

class RelatorioVenda extends Model
{
  
    public $items=[];
    public $total_venda;
    public $total_custo;
    public $totalLucro;

   
    public function getRelatorio()
    {
        $sql="vendas.id, vendas.cliente_id, 
        vendas.data_venda, vendas.carteira, vendas.frete, vendas.forma_pagamento, vendas.status
        ";
        $query = Venda::join('produto_venda', 'vendas.id', '=', 'produto_venda.venda_id')
                    ->join('clientes as c', 'c.id', '=', 'vendas.cliente_id')
                    ->join('produtos as p', 'p.id', '=', 'produto_venda.produto_id')
                    ->join('sellers as s', 's.id', '=', 'vendas.seller_id')
                     ->selectRaw("s.nome as seller_nome, c.nome as cliente_nome, $sql, sum((produto_venda.valor_venda * qtd)) as total_venda,
                    SUM(IF(produto_venda.granel=0,produto_venda.custo_medio*qtd,(produto_venda.custo_medio/p.valor_grandeza)*qtd)) as total_custo
                    ");
                    
        if (request('cliente_id')):
            $query->whereIn('cliente_id', request('cliente_id'));
        endif;

        if (request('seller_id')):
            $query->whereIn('seller_id', request('seller_id'));
        endif;

        if (request('data_venda1')):
            $dt =request('data_venda1');
            $query->where('data_venda', '>=', $dt);
        endif;

        if (request('data_venda2')):
            $dt =request('data_venda2');
            $query->where('data_venda', '<=', $dt);
        endif;

        if (is_numeric(request('frete'))):
            $query->where('frete', request('frete'));
        endif;

        if (is_numeric(request('carteira'))):
            $query->where('carteira', request('carteira'));
        endif;

        if (request('forma_pagamento')):
            $query->where('forma_pagamento', request('forma_pagamento'));
        endif;

        if (request('status')):
            $query->where('status', request('status'));
        endif;

        $this->items=$query->groupByRaw("$sql, s.nome, c.nome")
        ->get();

        $this->tratarItems($this->items);
        
        return $this;
    }


     private function tratarItems($items){
      
        $formas_pagamento=['','Dinheiro','Cartão Crédito','Cartão Débito'];
        $total_venda=0;
        $total_custo=0;
        $totalLucro=0;

        foreach($items as $item):
            $item->carteira=$item->carteira==1?'Sim':'Não';
            $item->frete=$item->frete==1?'Sim':'Não';
            $item->status=$item->status==1?'Pago':'Não Pago';
            $item->forma_pagamento=$formas_pagamento[$item->forma_pagamento];
            $item->lucro=$item->total_venda-$item->total_custo;

            $total_venda+=$item->total_venda;
            $total_custo+=$item->total_custo;
            $totalLucro+=$item->lucro;
            
        endforeach;

        $this->total_venda=$total_venda;
        $this->total_custo=$total_custo;
        $this->totalLucro=$totalLucro;
    }

    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ProdutoVenda;

class RelatorioProdutoVenda extends Model
{
  
    public $items=[];
    public $total;
    public $totalQtd;
    public $totalGranel;
    public $totalLucro;

   
    public function getRelatorio()
    {
        $query = ProdutoVenda::join('vendas', 'vendas.id', '=', 'produto_venda.venda_id')
                    ->join('produtos as p', 'p.id', '=', 'produto_venda.produto_id')
                    ->join('clientes', 'clientes.id', '=', 'vendas.cliente_id')
                    ->join('sellers as s', 's.id', '=', 'vendas.seller_id')
                     ->selectRaw('produto_venda.*, p.nome as produto_nome,
                     p.grandeza,p.valor_grandeza,
                     IF(produto_venda.granel=0,produto_venda.custo_medio,(produto_venda.custo_medio/p.valor_grandeza)) as custo_unitario,
                     IF(produto_venda.granel=0,produto_venda.custo_medio*qtd,(produto_venda.custo_medio/p.valor_grandeza)*qtd) as total_custo,
                     (produto_venda.valor_venda * qtd) as total_venda,
                     (SELECT total_venda - total_custo) as lucro,
                      clientes.nome as cliente_nome, 
                      s.nome as seller_nome,
                      vendas.data_venda, vendas.carteira, vendas.frete, vendas.forma_pagamento, vendas.status
                      
                      ');
                    
        if (request('cliente_id')):
            $query->whereIn('cliente_id', request('cliente_id'));
        endif;

        if (request('seller_id')):
            $query->whereIn('seller_id', request('seller_id'));
        endif;

        if (request('produto_id')):
            $query->whereIn('produto_id', request('produto_id'));
        endif;

        if (is_numeric(request('granel'))):
            $query->where('produto_venda.granel', request('granel'));
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
        $this->tratarItems($this->items); 

        
        return $this;
    }

    private function tratarItems($items){
        $nomes=['','Kg','Lt','Un'];
        $formas_pagamento=['','Dinheiro','Cartão Crédito','Cartão Débito'];
        $total=0;
        $totalQtd=0;
        $totalGranel=0;
        $totalLucro=0;

        foreach($items as $item):
            if($item->granel){
                $item->produto_nome.=" (Granel ".$nomes[$item->grandeza].') ';
                $totalGranel+=$item->qtd;
            }
            elseif($item->grandeza==1 || $item->grandeza==2){
                $item->produto_nome.=" ".$item->valor_grandeza." ".$nomes[$item->grandeza];
            }

            if(!$item->granel){
                $totalQtd+=$item->qtd;
            }

            $item->carteira=$item->carteira==1?'Sim':'Não';
            $item->frete=$item->frete==1?'Sim':'Não';
            $item->status=$item->status==1?'Pago':'Não Pago';
           $item->forma_pagamento=$formas_pagamento[$item->forma_pagamento];

            $total+=$item->total_venda;
            $totalLucro+=$item->lucro;
            
        endforeach;

        $this->total=$total;
        $this->totalQtd=$totalQtd;
        $this->totalGranel=$totalGranel;
        $this->totalLucro=$totalLucro;
    }

    
}

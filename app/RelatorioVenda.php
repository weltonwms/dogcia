<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Venda;

class RelatorioVenda extends Model
{
  
    public $items=[];
    public $total_venda;

   
    public function getRelatorio()
    {
        $sql="vendas.id, vendas.cliente_id, vendas.data_venda, clientes.nome, venda_id";
        $query = Venda::join('produto_venda', 'vendas.id', '=', 'produto_venda.venda_id')
                    ->join('clientes', 'clientes.id', '=', 'vendas.cliente_id')
                     ->selectRaw("$sql, sum((valor_venda * qtd)) as total");
                    
        if (request('cliente_id')):
            $query->whereIn('cliente_id', request('cliente_id'));
        endif;

        if (request('data_venda1')):
            $dt =request('data_venda1');
            $query->where('data_venda', '>=', $dt);
        endif;

        if (request('data_venda2')):
            $dt =request('data_venda2');
            $query->where('data_venda', '<=', $dt);
        endif;

        $this->items=$query->groupByRaw($sql)
        ->get();

        $this->calcTotalGeral();
        
        return $this;
    }

    private function calcTotalGeral()
    {
        $total_venda=0;
       
        foreach ($this->items as $item):
           $total_venda+=$item->total;
        endforeach;
       
        $this->total_venda=$total_venda;
    }
}

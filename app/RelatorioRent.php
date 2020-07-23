<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Rent;

class RelatorioRent extends Model
{
  
    public $items=[];
    public $total_rent;

   
    public function getRelatorio()
    {
        $sql="rents.id, rents.cliente_id, rents.data_saida, rents.data_retorno, 
                rents.devolvido, clientes.nome, rent_id";
        $query = Rent::join('produto_rent', 'rents.id', '=', 'produto_rent.rent_id')
                    ->join('clientes', 'clientes.id', '=', 'rents.cliente_id')
                     ->selectRaw("$sql, sum((valor_aluguel * qtd)) as total");
                    
        if (request('cliente_id')):
            $query->whereIn('cliente_id', request('cliente_id'));
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

        if ( is_numeric(request('status'))  ):
            $query->where('rents.devolvido', request('status'));
        endif;

        $this->items=$query->groupByRaw($sql)
        ->get();

        $this->calcTotalGeral();
        
        return $this;
    }

    private function calcTotalGeral()
    {
        $total_rent=0;
       
        foreach ($this->items as $item):
           $total_rent+=$item->total;
        endforeach;
       
        $this->total_rent=$total_rent;
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dashboard extends Model
{
    public static function getCards()
    {
        $cards = [
            
            "vendasHoje" => Dashboard::vendasHoje(),
            
        ];
        return $cards;
    }

    

  

   

    public static function vendasHoje()
    {
        $hoje = date("Y-m-d");
        return \DB::table('vendas')->where('data_venda', $hoje)->count();
    }

   
    
    /**
     * Retorna array com chave 'mes.ano' contendo total vendas mensais
     * @return array vendas mensais nos ultimos 6 meses
     */
    public static function vendasMensais()
    {
        $dados=[];
        $hoje=Carbon::now();
        $dateAtras= Carbon::now()->startOfMonth()->subMonth(5);
        $dataClone= clone $dateAtras;
        
        while( $dataClone->startOfMonth()->lte($hoje->startOfMonth()) ){
            $key="{$dataClone->month}.{$dataClone->year}";
            $dados[$key]=0;
            $dataClone->addMonth();
        }

       
        $result = \DB::table('produto_venda')
            ->join('vendas', 'produto_venda.venda_id', '=', 'vendas.id')
            ->selectRaw('MONTH(vendas.data_venda) as mes, YEAR(vendas.data_venda) as ano, SUM(qtd*valor_venda) as total')
            ->where('vendas.data_venda', '>=', $dateAtras->format('Y-m-d'))
            ->groupByRaw('MONTH(vendas.data_venda), YEAR(vendas.data_venda)')
            ->get();

       
        foreach($result as $res){
            $key="{$res->mes}.{$res->ano}";
            if( isset( $dados[$key] ) ){
                $dados[$key]=(float) $res->total;
            }
        }

        return $dados;

    }


     


}

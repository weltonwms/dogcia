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
            "produtosFalta" => Dashboard::produtosFalta(),
            "carteirasNaoPagas"=>Dashboard::carteirasNaoPagas()
            
        ];
        return $cards;
    }

    

  

   

    public static function vendasHoje()
    {
        $hoje = date("Y-m-d");
        return \DB::table('vendas')->where('data_venda', $hoje)->count();
    }

    public static function produtosFalta()
    {
        return \DB::table('produtos')->where('qtd_estoque', 0)->count();
    }

    public static function carteirasNaoPagas()
    {
        return \DB::table('vendas')->where('carteira', 1)->where('status',2)->count();
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




    /**
     * Retorna array com chave 'mes.ano' contendo total lucros mensais
     * @return array lucros mensais nos ultimos 6 meses
     */
    public static function lucrosMensais()
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

       
        $result= \DB::table(function($query) use($dateAtras){
            $query->
                from('produto_venda as pv')
                ->join('vendas', 'pv.venda_id', '=', 'vendas.id')
                ->join('produtos as p','p.id','=','pv.produto_id')
                ->selectRaw('
                MONTH(vendas.data_venda) as mes, 
                YEAR(vendas.data_venda) as ano,
                SUM(qtd*pv.valor_venda) as total_venda,
                SUM(IF(pv.granel=0,pv.custo_medio*qtd,(pv.custo_medio/p.valor_grandeza)*qtd))  as custo_total
                ')
            ->where('vendas.data_venda', '>=', $dateAtras->format('Y-m-d'))
            ->groupByRaw('MONTH(vendas.data_venda), YEAR(vendas.data_venda)')
            ->get();
        },'t1')->selectRaw('mes, ano, total_venda, 
                    custo_total,
                    (total_venda - custo_total) as lucro
                 ')->get();

        foreach($result as $res){
            $key="{$res->mes}.{$res->ano}";
            if( isset( $dados[$key] ) ){
                $dados[$key]=(float) $res->lucro;
            }
        }

        return $dados;

    }



     


}

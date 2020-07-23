<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dashboard extends Model
{
    public static function getCards()
    {
        $cards = [
            "alugueisVencidos" => Dashboard::alugueisVencidos(),
            "retornoHoje" => Dashboard::retornoHoje(),
            "produtosFalta" => Dashboard::produtosFalta(),
            "vendasHoje" => Dashboard::vendasHoje(),
            "saidaHoje" => Dashboard::saidaHoje(),
            "produtosQtdFora" => Dashboard::produtosQtdFora(),
        ];
        return $cards;
    }

    public static function alugueisVencidos()
    {
        $hoje = date("Y-m-d");
        return \DB::table('rents')->where('data_retorno', '<', $hoje)
            ->where('devolvido', 0)->count();
    }

    public static function retornoHoje()
    {
        $hoje = date("Y-m-d");
        return \DB::table('rents')->where('data_retorno', $hoje)->count();
    }

    public static function produtosFalta()
    {
        return \DB::table('produtos')->where('qtd_disponivel', 0)->count();
    }

    public static function vendasHoje()
    {
        $hoje = date("Y-m-d");
        return \DB::table('vendas')->where('data_venda', $hoje)->count();
    }

    public static function saidaHoje()
    {
        $hoje = date("Y-m-d");
        return \DB::table('rents')->where('data_saida', $hoje)->count();
    }

    public static function produtosQtdFora()
    {
        return \DB::table('produto_rent')->where('devolvido', 0)->sum('qtd');
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
     * Retorna array com chave 'mes.ano' contendo total alugueis mensais
     * @return array rents mensais nos ultimos 6 meses
     */
    public static function rentsMensais()
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

       
        $result = \DB::table('produto_rent')
            ->join('rents', 'produto_rent.rent_id', '=', 'rents.id')
            ->selectRaw('MONTH(rents.data_saida) as mes, YEAR(rents.data_saida) as ano, SUM(qtd*valor_aluguel) as total')
            ->where('rents.data_saida', '>=', $dateAtras->format('Y-m-d'))
            ->groupByRaw('MONTH(rents.data_saida), YEAR(rents.data_saida)')
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

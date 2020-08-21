<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TesteController extends Controller
{
    /**
     * Verificando algumas verdades relacionadas a entrada e saída
     * Use a Query String false=1 para filtrar somente produtos recorrentes de inverdade
     */
    public function teste1()
    {

        
        //entradas
        $totalCompras=\DB::table('compras')
            ->selectRaw(" produto_id, SUM(valor_compra*qtd) as totalValor, SUM(qtd) as totalQtd")
            ->groupBy('produto_id')
            ->get()
            ->keyBy('produto_id');
        
        //saidas
        $totalMortes=\DB::table('mortes')
        ->selectRaw(" produto_id, SUM(custo_medio*qtd) as totalValor, SUM(qtd) as totalQtd")
        ->groupBy('produto_id')
        ->get()
        ->keyBy('produto_id');

        $produtos=\App\Produto::all();
        foreach($produtos as $produto):
            
            $totalCompras_valor=0;
            $totalCompras_qtd=0;
            $mortes_valor=0;
            $mortes_qtd=0;
            if(isset($totalCompras[$produto->id])){
                $totalCompras_valor=$totalCompras[$produto->id]->totalValor;
                $totalCompras_qtd=$totalCompras[$produto->id]->totalQtd;
            }
            if(isset($totalMortes[$produto->id])){
                $mortes_valor=$totalMortes[$produto->id]->totalValor;
                $mortes_qtd=$totalMortes[$produto->id]->totalQtd;
            }
            $valorEstoque=$produto->custo_medio*$produto->qtd_estoque;
            $teste1=$totalCompras_qtd==$produto->qtd_estoque+$mortes_qtd;
            // $teste2= bccomp($totalCompras_valor,$valorEstoque+$mortes_valor,1)==0?true:false;
            $teste2= round($totalCompras_valor)==round($valorEstoque+$mortes_valor);

            if(request('false')!=1 || !$teste1 || !$teste2){
                echo "<h3>Produto: {$produto->nome} Cód: {$produto->id} </h3>";
                echo "<br>Compras Valor: ".$totalCompras_valor;
                echo "<br>Compras QTD: ".$totalCompras_qtd;
                echo "<br>Mortes Valor: ".$mortes_valor;
                echo "<br>Mortes QTD: ".$mortes_qtd;
                //echo "<br> round".round($valorEstoque);
               
                
                echo "<br>Produtos Valor Estoque: ".$valorEstoque;
                echo "<br>Produtos QTD Estoque: ".$produto->qtd_estoque;
                echo "<h5>Validando Estoque</h5>";
                echo "Entradas==Estoque+Saidas: ".boolStr($teste1);
                echo "<h5>Validando Custo</h5>";
                echo "Custo Médio Atual: ".$produto->custo_medio;
                echo "<br>Entradas==Estoque+Saidas: ".boolStr($teste2);
                $den=$produto->qtd_estoque==0?1:$produto->qtd_estoque;
                echo "<br>Fórmula2 Custo: ".($totalCompras_valor - $mortes_valor)/$den;
    
                echo "<hr>";
            }
           
        endforeach;




    }


   
}

function boolStr($n){
    return $n?"Verdadeiro":"<span style='color:red;'>FALSO</span>";
}




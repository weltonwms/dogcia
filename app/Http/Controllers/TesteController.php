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

        $totalVendas=\DB::table('produto_venda')
        ->selectRaw(" produto_id, SUM(custo_medio*qtd) as totalValor, SUM(qtd) as totalQtd")
        ->where("granel",0)
        ->groupBy('produto_id')
        ->get()
        ->keyBy('produto_id');

        $totalGranel=\DB::table('granos')
        ->selectRaw(" produto_id, SUM(custo_medio) as totalValor, COUNT(*) as totalQtd")
        ->groupBy('produto_id')
        ->get()
        ->keyBy('produto_id');

        $produtos=\App\Produto::all();
        foreach($produtos as $produto):
            
            $totalCompras_valor=0;
            $totalCompras_qtd=0;
            $mortes_valor=0;
            $mortes_qtd=0;
            $vendas_valor=0;
            $vendas_qtd=0;
            $granel_valor=0;
            $granel_qtd=0;
            if(isset($totalCompras[$produto->id])){
                $totalCompras_valor=$totalCompras[$produto->id]->totalValor;
                $totalCompras_qtd=$totalCompras[$produto->id]->totalQtd;
            }
            if(isset($totalMortes[$produto->id])){
                $mortes_valor=$totalMortes[$produto->id]->totalValor;
                $mortes_qtd=$totalMortes[$produto->id]->totalQtd;
            }
            if(isset($totalVendas[$produto->id])){
                $vendas_valor=$totalVendas[$produto->id]->totalValor;
                $vendas_qtd=$totalVendas[$produto->id]->totalQtd;
            }
            if(isset($totalGranel[$produto->id])){
                $granel_valor=$totalGranel[$produto->id]->totalValor;
                $granel_qtd=$totalGranel[$produto->id]->totalQtd;
            }
            $saidas_qtd=$mortes_qtd+$vendas_qtd+$granel_qtd;
            $saidas_valor=$mortes_valor+$vendas_valor+$granel_valor;
            $valorEstoque=$produto->custo_medio*$produto->qtd_estoque;
            $teste1=$totalCompras_qtd==$produto->qtd_estoque+$saidas_qtd;
            // $teste2= bccomp($totalCompras_valor,$valorEstoque+$saidas_valor,1)==0?true:false;
            $teste2= round($totalCompras_valor)==round($valorEstoque+$saidas_valor);

            if(request('false')!=1 || !$teste1 || !$teste2){
                echo "<h3>Produto: {$produto->nome} Cód: {$produto->id} </h3>";
                echo "<br>Compras Valor: ".$totalCompras_valor;
                echo "<br>Compras QTD: ".$totalCompras_qtd;
                echo "<br>Mortes Valor: ".$mortes_valor;
                echo "<br>Mortes QTD: ".$mortes_qtd;
                echo "<br>Vendas Valor: ".$vendas_valor;
                echo "<br>Vendas QTD: ".$vendas_qtd;
                echo "<br>Granel Total Custo: ".$granel_valor;
                echo "<br>Granel QTD: ".$granel_qtd;
                //echo "<br> round".round($valorEstoque);
               
                
                echo "<br>Produtos Valor Estoque: ".$valorEstoque;
                echo "<br>Produtos QTD Estoque: ".$produto->qtd_estoque;
                echo "<h5>Validando Estoque</h5>";
                echo "Entradas==Estoque+Saidas: ".boolStr($teste1);
                echo "<h5>Validando Custo</h5>";
                echo "Custo Médio Atual: ".$produto->custo_medio;
                echo "<br>Entradas==Estoque+Saidas: ".boolStr($teste2);
                $den=$produto->qtd_estoque==0?1:$produto->qtd_estoque;
                echo "<br>Fórmula2 Custo: ".($totalCompras_valor - $saidas_valor)/$den;
    
                echo "<hr>";
            }
           
        endforeach;




    }


    public function teste2(){
        
         //entradas
         $totalGranel=\DB::table('granos')
         ->selectRaw(" produto_id, SUM(valor) as totalValor, COUNT(*) as totalQtd")
         ->groupBy('produto_id')
         ->get()
         ->keyBy('produto_id');

         //saida
         $totalVendas=\DB::table('produto_venda')
         ->selectRaw(" produto_id,  SUM(qtd) as totalQtd")
         ->where("granel",1)
         ->groupBy('produto_id')
         ->get()
         ->keyBy('produto_id');

        //dd($totalGranel[53]->totalValor);

         $produtos=\App\Produto::whereIn('id',$totalGranel->keys())->get();
         foreach($produtos as $produto):
            $estoque_qtd=$produto->granel;
            $granel_disponibilizado=$totalGranel[$produto->id]->totalValor;
            $granel_vendido=0;

            if(isset($totalVendas[$produto->id])){
                $granel_vendido=$totalVendas[$produto->id]->totalQtd;
            }
            //total Disponibilizado(entrada)==estoqueGranel+saida(venda)
            $teste1= $granel_disponibilizado===$estoque_qtd+$granel_vendido;

            echo "<h3>Produto: {$produto->nome} Cód: {$produto->id} </h3>";
            echo "<br>Total Granel disponibilizado: ".$granel_disponibilizado;
            echo "<br>Estoque de Granel: ".$estoque_qtd;
            echo "<br>Granel Vendido: ".$granel_vendido;

            echo "<h5>Validando Estoque</h5>";
            echo "Entrada==Estoque+Saidas: ".boolStr($teste1);
                
            
        
         endforeach;
    }


   
}

function boolStr($n){
    return $n?"Verdadeiro":"<span style='color:red;'>FALSO</span>";
}





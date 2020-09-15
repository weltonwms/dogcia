<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Produto;

class Grano extends Model
{
    public function produto(){
        return $this->belongsTo("App\Produto");
    }

    public static function addGranel(Produto $produto){
        if($produto->ser_vivo){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => "Operação Negada para Ser Vivo"]);
            return false;
        }
        try{
            \DB::transaction(function () use ($produto){
                $grano=new Grano();
                $grano->produto_id=$produto->id;
                $grano->valor=$produto->valor_grandeza;
                $grano->custo_medio=$produto->custo_medio;
                $grano->save();

                $produto->qtd_estoque--;
                $produto->granel+=$produto->valor_grandeza;
                $produto->save();
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
             });
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
    }

    public function removeGranel(){
        try{
            \DB::transaction(function () {
                $produto= $this->produto;
                $valor=$this->valor;
                $this->delete();

                $produto->qtd_estoque++;
                $produto->granel-=$valor;
                $produto->save();
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete',1)]);
             });
        }
        catch(\Exception $e){
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $e->getMessage()]);
        }
    }

   public static function getQtdVendas($produto_id){
    $totalQtdVendas = \DB::table('produto_venda')
        ->where('produto_id', $produto_id)
        ->where('granel',1)
        ->sum('qtd');
    return $totalQtdVendas;
   }

   /**
    * Calcula o total dos granos recebidos
    */
   public static function getTotalValor($granos){
       $total=0;
        foreach($granos as $grano):
            $total+=$grano->valor;
        endforeach;
        return $total;
   }



}

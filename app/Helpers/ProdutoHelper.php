<?php

namespace App\Helpers;


use App\Produto;
use App\ProdutoVenda;

/**
 * Classe de apoio a Produtos
 *
 * @author welton
 */
class ProdutoHelper
{

    public static function descricao($produto){
        
       if($produto->pivot->granel){
            $description=$produto->nome." (Granel ".$produto->getNomeGrandeza()." )";
       }
       else{
            $description=$produto->getNomeCompleto();
       }

       if($produto->descricao){
           $description.=" - ".$produto->descricao;
       }

        return $description;
         
    }

    public static function custoMedio($produto){
        if($produto->pivot->granel){
            return $produto->pivot->custo_medio/$produto->valor_grandeza;
       }
       return $produto->pivot->custo_medio;
    }

    public static function custoMedioTotal($produto){
        
       return self::custoMedio($produto)*$produto->pivot->qtd;
    }
   

}

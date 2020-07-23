<?php

namespace App\Helpers;
use Carbon\Carbon ;

/**
 * Classe de apoio geral
 *
 * @author welton
 */
class UtilHelper
{

    public static function moneyToBr($valor)
    {
        if(!$valor):
            return "R$ 0,00";
        endif;
            return "R$ " . number_format($valor, 2, ',', '.');
        
    }

    public static function moneyBrToUsd($valor)
    {

        $valor1 = str_replace(".", "", $valor);
        $valor2 = str_replace(",", ".", $valor1);

        return number_format($valor2, 2,'.','');
    }

    public static function dateBr($value, $format="d/m/Y"){
        $result=null;
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $result = Carbon::parse($value)->format($format);
        } 
       return $result;
       
    }

    /**
     * @param $obj Objeto que precisa ter atributos: devolvido, data_retorno
     */
    public static function nomeStatus($obj){
        if(!isset($obj->devolvido)): 
            return null;
        endif;
        $st= $obj->devolvido==0?"Em Aberto":"Devolvido";
        if(!isset($obj->data_retorno) || !$obj->data_retorno): 
            return $st;
        endif;
        //considerando que se tem até meia noite para devolver;
        $dataRetorno=self::getDateCarbon( $obj->data_retorno)->endOfDay();
        $diaAtual=Carbon::now();
        if($obj->devolvido==0 && $diaAtual->gt($dataRetorno) ):
            $st="<span class='text-danger'>Vencido</span>";
        endif;
       
        return $st;
    }

    private static function getDateCarbon($value){
        $result=null;
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if($value){
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $result = Carbon::parse($value);
        } 
       return $result;
    }

}
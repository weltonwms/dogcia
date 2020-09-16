<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * Classe de apoio geral
 *
 * @author welton
 */
class UtilHelper
{

    public static function moneyToBr($valor, $cifrao = false)
    {
        $cf = $cifrao ? "R$ " : "";
        if (!$valor):
            return $cf . "0,00";
        endif;
        return $cf . number_format($valor, 2, ',', '.');

    }

    public static function moneyBrToUsd($valor)
    {

        $valor1 = str_replace(".", "", $valor);
        $valor2 = str_replace(",", ".", $valor1);

        return number_format($valor2, 2, '.', '');
    }

    public static function numberOfDecimals($value)
    {
        if ((int) $value == $value) {
            return 0;
        } else if (!is_numeric($value)) {
            // throw new Exception('numberOfDecimals: ' . $value . ' is not a number!');
            return false;
        }

        return strlen($value) - strrpos($value, '.') - 1;
    }

    public static function formatNumber($value)
    {
        $v = (float) $value;
        if (self::numberOfDecimals($v) > 2) {
            return number_format($v, 3, ",", ".");
        }
        return number_format($v, 2, ",", ".");
    }

    public static function dateBr($value, $format = "d/m/Y")
    {
        $result = null;
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if ($value) {
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $result = Carbon::parse($value)->format($format);
        }
        return $result;

    }

    private static function getDateCarbon($value)
    {
        $result = null;
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $value)) { //verifica se é formato dd/mm/aaaa
            $partes = explode("/", $value);
            $value = $partes[2] . "-" . $partes[1] . "-" . $partes[0];
            //sobrescrevendo o value em formato mysql
        }
        if ($value) {
            //protegendo de fazer um parse em nada. Isso resulta em data e hora atual
            $result = Carbon::parse($value);
        }
        return $result;
    }

    public static function floatBr($valorNumber){
        return str_replace(".", ",", $valorNumber);
     
    }

}

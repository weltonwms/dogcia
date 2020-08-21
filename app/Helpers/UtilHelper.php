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

    public static function moneyToBr($valor)
    {
        if (!$valor):
            return "R$ 0,00";
        endif;
        return "R$ " . number_format($valor, 2, ',', '.');

    }

    public static function moneyBrToUsd($valor)
    {

        $valor1 = str_replace(".", "", $valor);
        $valor2 = str_replace(",", ".", $valor1);

        return number_format($valor2, 2, '.', '');
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

}

function cmpFloats($a, $operation, $b, $decimals = 15)
{
    if ($decimals < 0) {
        throw new Exception('Invalid $decimals ' . $decimals . '.');
    }
    if (!in_array($operation, ['==', '!=', '>', '>=', '<', '<='])) {
        throw new Exception('Invalid $operation ' . $operation . '.');
    }

    $aInt = (int) $a;
    $bInt = (int) $b;

    $aIntLen = strlen((string) $aInt);
    $bIntLen = strlen((string) $bInt);

    // We'll not used number_format because it inaccurate with very long numbers, instead will use str_pad and manipulate it as string
    $aStr = (string) $a; //number_format($a, $decimals, '.', '');
    $bStr = (string) $b; //number_format($b, $decimals, '.', '');

    // If passed null, empty or false, then it will be empty string. So change it to 0
    if ($aStr === '') {
        $aStr = '0';
    }
    if ($bStr === '') {
        $bStr = '0';
    }

    if (strpos($aStr, '.') === false) {
        $aStr .= '.';
    }
    if (strpos($bStr, '.') === false) {
        $bStr .= '.';
    }

    $aIsNegative = strpos($aStr, '-') !== false;
    $bIsNegative = strpos($bStr, '-') !== false;

    // Append 0s to the right
    $aStr = str_pad($aStr, ($aIsNegative ? 1 : 0) + $aIntLen + 1 + $decimals, '0', STR_PAD_RIGHT);
    $bStr = str_pad($bStr, ($bIsNegative ? 1 : 0) + $bIntLen + 1 + $decimals, '0', STR_PAD_RIGHT);

    // If $decimals are less than the existing float, truncate
    $aStr = substr($aStr, 0, ($aIsNegative ? 1 : 0) + $aIntLen + 1 + $decimals);
    $bStr = substr($bStr, 0, ($bIsNegative ? 1 : 0) + $bIntLen + 1 + $decimals);

    $aDotPos = strpos($aStr, '.');
    $bDotPos = strpos($bStr, '.');

    // Get just the decimal without the int
    $aDecStr = substr($aStr, $aDotPos + 1, $decimals);
    $bDecStr = substr($bStr, $bDotPos + 1, $decimals);

    $aDecLen = strlen($aDecStr);
    //$bDecLen = strlen($bDecStr);

    // To match 0.* against -0.*
    $isBothZeroInts = $aInt == 0 && $bInt == 0;

    if ($operation === '==') {
        return $aStr === $bStr ||
            $isBothZeroInts && $aDecStr === $bDecStr;
    } else if ($operation === '!=') {
        return $aStr !== $bStr ||
            $isBothZeroInts && $aDecStr !== $bDecStr;
    } else if ($operation === '>') {
        if ($aInt > $bInt) {
            return true;
        } else if ($aInt < $bInt) {
            return false;
        } else { // Ints equal, check decimals
            if ($aDecStr === $bDecStr) {
                return false;
            } else {
                for ($i = 0; $i < $aDecLen; ++$i) {
                    $aD = (int) $aDecStr[$i];
                    $bD = (int) $bDecStr[$i];
                    if ($aD > $bD) {
                        return true;
                    } else if ($aD < $bD) {
                        return false;
                    }
                }
            }
        }
    } else if ($operation === '>=') {
        if ($aInt > $bInt ||
            $aStr === $bStr ||
            $isBothZeroInts && $aDecStr === $bDecStr) {
            return true;
        } else if ($aInt < $bInt) {
            return false;
        } else { // Ints equal, check decimals
            if ($aDecStr === $bDecStr) { // Decimals also equal
                return true;
            } else {
                for ($i = 0; $i < $aDecLen; ++$i) {
                    $aD = (int) $aDecStr[$i];
                    $bD = (int) $bDecStr[$i];
                    if ($aD > $bD) {
                        return true;
                    } else if ($aD < $bD) {
                        return false;
                    }
                }
            }
        }
    } else if ($operation === '<') {
        if ($aInt < $bInt) {
            return true;
        } else if ($aInt > $bInt) {
            return false;
        } else { // Ints equal, check decimals
            if ($aDecStr === $bDecStr) {
                return false;
            } else {
                for ($i = 0; $i < $aDecLen; ++$i) {
                    $aD = (int) $aDecStr[$i];
                    $bD = (int) $bDecStr[$i];
                    if ($aD < $bD) {
                        return true;
                    } else if ($aD > $bD) {
                        return false;
                    }
                }
            }
        }
    } else if ($operation === '<=') {
        if ($aInt < $bInt ||
            $aStr === $bStr ||
            $isBothZeroInts && $aDecStr === $bDecStr) {
            return true;
        } else if ($aInt > $bInt) {
            return false;
        } else { // Ints equal, check decimals
            if ($aDecStr === $bDecStr) { // Decimals also equal
                return true;
            } else {
                for ($i = 0; $i < $aDecLen; ++$i) {
                    $aD = (int) $aDecStr[$i];
                    $bD = (int) $bDecStr[$i];
                    if ($aD < $bD) {
                        return true;
                    } else if ($aD > $bD) {
                        return false;
                    }
                }
            }
        }
    }
}

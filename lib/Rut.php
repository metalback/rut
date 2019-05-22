<?php
/**
 * Rut is a helper which manage action on and for Chilean Rut
 */
namespace metalback\Rut;
/**
 * Rut is a helper which manage action on and for Chilean Rut
 *
 * @author Mauricio Beltran <mgbeltranb@gmail.com>
 * @version 1.0.0
 * @license MIT
 */
class Rut
{
     /**
     * Check if a given Rut is valid
     * <code>
     * $valid_rut = \Core\Rut::validate('12.312.312-3');
     * if ($valid_rut) {
     *     echo "valid";
     * } else {
     *     echo "not valid";
     * }
     * </code>
     * @param  string $rut chilean rut
     * @return boolean      true if it is valid, false instead
     */
    public static function validate($rut)
    {
        $rut = self::clean($rut);
        $number=self::number($rut);
        $digit=self::VerificationDigit($rut);
        $dv = self::getVerificationDigit($number);
        if (strtoupper($dv) == strtoupper($digit)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get base number of a given full rut
     * <code>
     * $rut_base = \Core\Rut::number('12.312.312-3');
     * echo $rut_base; //will get 12312312
     * </code>
     * @param  string $rut Rut of type 12.312.123-3 or 12312312-3 or 123123123
     * @return integer      base number of a given Rut
     */
    public static function number($rut)
    {
        $rut = self::clean($rut);
        return substr($rut,0,strlen($rut)-1);
    }

    /**
     * Get the verification digit of a given full rut
     * <code>
     * $rut_digv = \Core\Rut::VerificationDigit('12.312.312-3');
     * echo $rut_digv; // you will get 3
     * </code>
     * @param  string $rut rut of 12.312.312-3
     * @return integer     verification digit
     */
    public static function VerificationDigit($rut)
    {
        $rut = self::clean($rut);
        return substr($rut,-1);
    }

    /**
     * Clean a Rut and let it without any special chars
     * <code>
     * $clean = \Core\Rut::clean('12.312.312-3');
     * echo $clean; //you will get 123123123
     * </code>
     * @param  string $rut rut of type 12.312.312-3
     * @return string      123123123
     */
    public static function clean($rut)
    {
        $rut = trim($rut);
        $pattern  = array('.', ',', '-');
        $replace = array('');
        $rut=str_replace($pattern, $replace, $rut);
        return $rut;
    }

    /**
     * Calculates the verification digit of a given Rut
     * <code>
     * $rut_con_dv = \Core\Rut::getVerificationDigit(12312312);
     * echo $rut_con_dv; //You will get 123123123
     * </code>
     * @param  integer $rut_without_dv rut without verification digit
     * @return integer             rut with verification digit
     */
    public static function getVerificationDigit($rut_without_dv)
    {
        $sub_rut = (string) $rut_without_dv;
        $x=2;
        $s=0;
        for ($i=strlen($sub_rut)-1;$i>=0;$i--) {
            if ($x > 7) {
                $x = 2;
            }
            $s += $sub_rut[$i]*$x;
            $x++;
        }
        $dv = 11-($s%11);
        if ($dv == 10) {
            $dv = 'K';
        }
        if ($dv == 11) {
            $dv = '0';
        }
        return $dv;
    }

    /**
     * generate un number de rut a partir de una base
     * <code>
     * $rut = \Core\Rut::generate();
     * echo $rut; //You will get 12.312.312-3 o cualquier otro rut valido de la forma
     * $rut = \Core\Rut::generate('', 2);
     * print_r($rut);//You will get  array => ( [0] => 12.312.312-3, [1] => 11.111.111-1 )
     * </code>
     * @param  string  $base    number base de generacion
     * @param  integer $cuantos cuantos numbers va a generate
     * @return array            arreglo de rut generados
     */
    public static function generate($base='',$cuantos=1)
    {
        $gen = array();
        if ($base == '' || !is_numeric($base)) {
            $base = mt_rand();
        }
        while($cuantos){
            $number = $base++;
            $digito = self::getVerificationDigit($number);
            $gen[] = self::format(''.$number.$digito);
            $cuantos--;
        }
        return $gen;
    }

    /**
     * Formatea un rut con la nomenclatura chilena
     * <code>
     * $rut_con_dv = \Core\Rut::format(12312312-3);
     * echo $rut_con_dv; //You will get 12.312.312-3
     * </code>
     * @param  integer $rut rut completo a format
     * @return string      rut formateado
     */
    public static function format($rut)
    {
        $rut = self::clean($rut);
        $sub_rut=substr($rut,0,strlen($rut)-1);
        $sub_dv=substr($rut,-1);
        $rut = number_format($sub_rut,0,',','.') . '-' . $sub_dv;
        return $rut;
    }
    
    /**
     * Formatea un rut con la nomenclatura chilena
     * without contar con el dígito verificador
     * <code>
     * $rut_without_dv = \Core\Rut::formatWithoutDV(12312312);
     * echo $rut_without_dv; //You will get 12.312.312-3
     * </code>
     * @param  integer  $rut rut without verification digit a format
     * @return string   rut formateado
     */
    public static function formatWithoutDV($rut)
    {
        $dv = self::getVerificationDigit($rut);
        $rut_completo = $rut . '-' . $dv;
        $rut_formateado = self::format($rut_completo);
        return $rut_formateado;
    }
}
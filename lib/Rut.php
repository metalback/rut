<?php
namespace metalback\Rut;
/**
 * 
 */
class Rut
{
     /**
     * Valida que un rut dado sea valido
     * <code>
     * $rut_valido = \Core\Rut::validar('12.312.312-3');
     * if ($rut_valido) {
     *     echo "valido";
     * } else {
     *     echo "no valido";
     * }
     * </code>
     * @param  string $rut rut a validar
     * @return boolean      true en caso de que sea valido, false en caso contrario
     */
    public static function validar($rut)
    {
        $rut = self::limpiar($rut);
        $numero=self::numero($rut);
        $digito=self::digitoVerificador($rut);
        $dv = self::calcularDigitoVerificador($numero);
        if (strtoupper($dv) == strtoupper($digito)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Obtiene el numero base de un rut completo
     * <code>
     * $rut_base = \Core\Rut::numero('12.312.312-3');
     * echo $rut_base; //Obtendras 12312312
     * </code>
     * @param  string $rut Rut del tipo 12.312.123-3 o 12312312-3 o 123123123
     * @return integer      numero base del rut
     */
    public static function numero($rut)
    {
        $rut = self::limpiar($rut);
        return substr($rut,0,strlen($rut)-1);
    }

    /**
     * Obtiene el digito verificador dado un rut completo
     * <code>
     * $rut_digv = \Core\Rut::digitoVerificador('12.312.312-3');
     * echo $rut_digv; //Obtendras 3
     * </code>
     * @param  string $rut rut del tipo 12.312.312-3
     * @return integer     digito verificador
     */
    public static function digitoVerificador($rut)
    {
        $rut = self::limpiar($rut);
        return substr($rut,-1);
    }

    /**
     * Limpia un Rut y lo dejo sin puntos ni guiones
     * <code>
     * $limpiar = \Core\Rut::limpiar('12.312.312-3');
     * echo $limpiar; //Obtendras 123123123
     * </code>
     * @param  string $rut rut del tipo 12.312.312-3
     * @return string      123123123
     */
    public static function limpiar($rut)
    {
        $rut = trim($rut);
        $patron  = array('.', ',', '-');
        $reemplazo = array('');
        $rut=str_replace($patron,$reemplazo,$rut);
        return $rut;
    }

    /**
     * Calcula el digito verificador dado un rut
     * <code>
     * $rut_con_dv = \Core\Rut::calcularDigitoVerificador(12312312);
     * echo $rut_con_dv; //Obtendras 123123123
     * </code>
     * @param  integer $rut_sin_dv rut sin digito verificador a obtener
     * @return integer             rut con digito verificador incluido
     */
    public static function calcularDigitoVerificador($rut_sin_dv)
    {
        $sub_rut = (string)$rut_sin_dv;
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
     * generar un numero de rut a partir de una base
     * <code>
     * $rut = \Core\Rut::generar();
     * echo $rut; //Obtendras 12.312.312-3 o cualquier otro rut valido de la forma
     * $rut = \Core\Rut::generar('', 2);
     * print_r($rut);//Obtendras  array => ( [0] => 12.312.312-3, [1] => 11.111.111-1 )
     * </code>
     * @param  string  $base    numero base de generacion
     * @param  integer $cuantos cuantos numeros va a generar
     * @return array            arreglo de rut generados
     */
    public static function generar($base='',$cuantos=1)
    {
        $gen = array();
        if ($base == '' || !is_numeric($base)) {
            $base = mt_rand();
        }
        while($cuantos){
            $numero = $base++;
            $digito = self::calcularDigitoVerificador($numero);
            $gen[] = self::formatear(''.$numero.$digito);
            $cuantos--;
        }
        return $gen;
    }

    /**
     * Formatea un rut con la nomenclatura chilena
     * <code>
     * $rut_con_dv = \Core\Rut::formatear(12312312-3);
     * echo $rut_con_dv; //Obtendras 12.312.312-3
     * </code>
     * @param  integer $rut rut completo a formatear
     * @return string      rut formateado
     */
    public static function formatear($rut)
    {
        $rut = self::limpiar($rut);
        $sub_rut=substr($rut,0,strlen($rut)-1);
        $sub_dv=substr($rut,-1);
        $rut = number_format($sub_rut,0,',','.') . '-' . $sub_dv;
        return $rut;
    }
    
    /**
     * Formatea un rut con la nomenclatura chilena
     * sin contar con el dígito verificador
     * <code>
     * $rut_sin_dv = \Core\Rut::formatearSinDV(12312312);
     * echo $rut_sin_dv; //Obtendras 12.312.312-3
     * </code>
     * @param  integer  $rut rut sin digito verificador a formatear
     * @return string   rut formateado
     */
    public static function formatearSinDV($rut)
    {
        $dv = self::calcularDigitoVerificador($rut);
        $rut_completo = $rut . '-' . $dv;
        $rut_formateado = self::formatear($rut_completo);
        return $rut_formateado;
    }
}
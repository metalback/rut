<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use metalback\Rut\Rut;

class RutTest extends TestCase 
{

    /**
     * Validates sort of RUT
     * @param  string $rut Rut to validate
     * @return void
     */
    public function testValidar()
    {
        $this->assertTrue(Rut::validar('1-9'));
        $this->assertTrue(Rut::validar('12.312.312-3'));
        $this->assertFalse(Rut::validar('15123423-7'));

    }

    public function testNumero()
    {
        $this->assertEquals(Rut::numero('12.312.312-3'), 12312312);
        $this->assertEquals(Rut::numero('1-9'), 1);
        $this->assertEquals(Rut::numero('151234237'), 15123423);
    }

    public function testDigitoVerificador()
    {
        $this->assertEquals(Rut::digitoVerificador('12.312.312-3'), 3);
        $this->assertEquals(Rut::digitoVerificador('1-9'), 9);
        $this->assertEquals(Rut::digitoVerificador('151234237'), 7);
    }

    public function testLimpiar()
    {
        $this->assertEquals(Rut::limpiar('12.312.312-3'), 123123123);
        $this->assertEquals(Rut::limpiar('1-9'), 19);
        $this->assertEquals(Rut::limpiar('151234237'), 151234237);
    }

    public function testCalcularDigitoVerificador()
    {
        $this->assertEquals(Rut::calcularDigitoVerificador('12312312'), 3);
        $this->assertEquals(Rut::calcularDigitoVerificador(1), 9);
        $this->assertEquals(Rut::calcularDigitoVerificador('15123423'), 2);
    }

    public function testGenerar()
    {
        $this->assertEquals(count(Rut::generar('', 1)), 1);
        $generado = Rut::generar('', 1);
        $this->assertTrue(Rut::validar($generado[0]));
    }

    public function testFormatear()
    {
        $this->assertEquals(Rut::formatear(123123123), '12.312.312-3');
        $this->assertEquals(Rut::formatear(19), '1-9');
        $this->assertEquals(Rut::formatear(151234232), '15.123.423-2');
    }

    public function testFormatearSinDV()
    {
        $this->assertEquals(Rut::formatearSinDV(12312312), '12.312.312-3');
        $this->assertEquals(Rut::formatearSinDV(1), '1-9');
        $this->assertEquals(Rut::formatearSinDV(15123423), '15.123.423-2');
    }

}
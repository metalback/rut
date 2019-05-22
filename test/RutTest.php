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
    public function testValidate()
    {
        $this->assertTrue(Rut::validate('1-9'));
        $this->assertTrue(Rut::validate('12.312.312-3'));
        $this->assertFalse(Rut::validate('15123423-7'));

    }

    public function testNumber()
    {
        $this->assertEquals(Rut::number('12.312.312-3'), 12312312);
        $this->assertEquals(Rut::number('1-9'), 1);
        $this->assertEquals(Rut::number('151234237'), 15123423);
    }

    public function testVerificationDigit()
    {
        $this->assertEquals(Rut::verificationDigit('12.312.312-3'), 3);
        $this->assertEquals(Rut::verificationDigit('1-9'), 9);
        $this->assertEquals(Rut::verificationDigit('151234237'), 7);
    }

    public function testClean()
    {
        $this->assertEquals(Rut::clean('12.312.312-3'), 123123123);
        $this->assertEquals(Rut::clean('1-9'), 19);
        $this->assertEquals(Rut::clean('151234237'), 151234237);
    }

    public function testGetVerificationDigit()
    {
        $this->assertEquals(Rut::getVerificationDigit('12312312'), 3);
        $this->assertEquals(Rut::getVerificationDigit(1), 9);
        $this->assertEquals(Rut::getVerificationDigit('15123423'), 2);
    }

    public function testGenerate()
    {
        $this->assertEquals(count(Rut::generate('', 1)), 1);
        $generado = Rut::generate('', 1);
        $this->assertTrue(Rut::validate($generado[0]));
    }

    public function testformat()
    {
        $this->assertEquals(Rut::format(123123123), '12.312.312-3');
        $this->assertEquals(Rut::format(19), '1-9');
        $this->assertEquals(Rut::format(151234232), '15.123.423-2');
    }

    public function testformatWithoutDV()
    {
        $this->assertEquals(Rut::formatWithoutDV(12312312), '12.312.312-3');
        $this->assertEquals(Rut::formatWithoutDV(1), '1-9');
        $this->assertEquals(Rut::formatWithoutDV(15123423), '15.123.423-2');
    }

}
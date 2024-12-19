<?php

namespace common\tests\unit;

use common\fixtures\IvasFixture;
use common\models\Ivas;
use common\tests\UnitTester;

class IvasTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'ivas' => [
                'class' => IvasFixture::class,
                'dataFile' => codecept_data_dir() . 'ivas.php',
            ],
        ];
    }

    public function testCreateIvaSuccessfully()
    {
        $iva = new Ivas();
        $iva->percentagem = 23;
        $iva->descricao = 'sem descricao';
        $iva->vigor = 1;
        $this->assertTrue($iva->save(), 'O Iva deve conter dados validos.');
    }

    public function testCreateIvaUnsuccessfully()
    {
        $iva = new Ivas();
        $iva->percentagem = 'iva';
        $iva->descricao = '';
        $iva->vigor = 'em vigor';

        $this->assertFalse($iva->save(), 'O Iva não deve conter dados validos.');
    }

    public function testUpdateIvaSuccessfully()
    {
        $iva = $this->tester->grabFixture('ivas', 'iva1');
        $iva->percentagem = 6;
        $iva->descricao = 'com descricao';
        $iva->vigor = 0;
        $this->assertTrue($iva->save(), 'O Iva deve ser guardado apos a edição.');
    }

    public function testUpdateIvaUnsuccessfully()
    {
        $iva = $this->tester->grabFixture('ivas', 'iva1');
        $iva->percentagem = 'iva';
        $iva->descricao = '';
        $iva->vigor = 'em vigor';
        $this->assertFalse($iva->save(), 'O Iva não deve ser guardado com dados invalidos.');
    }

    public function testDeleteIvaSuccessfully()
    {
        $iva = $this->tester->grabFixture('ivas', 'iva2');
        $iva->delete();
        $this->assertNull(Ivas::findOne($iva->id), 'O Iva deve ser eliminado.');
    }

    public function testDeleteIvaUnsuccessfully()
    {
        $iva = Ivas::findOne(['id' => 3]);
        $this->assertNull($iva, 'O Iva não deve existir.');
        if ($iva) {
            $this->assertFalse($iva->delete() > 0, 'Não deve ser possível eliminar um Iva que não existe.');
        }
    }

}

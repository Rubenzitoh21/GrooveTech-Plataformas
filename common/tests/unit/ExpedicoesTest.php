<?php

namespace common\tests\unit;

use common\fixtures\ExpedicoesFixture;
use common\models\Expedicoes;
use common\tests\UnitTester;

class ExpedicoesTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'expedicoes' => [
                'class' => ExpedicoesFixture::class,
                'dataFile' => codecept_data_dir() . 'expedicoes.php',
            ],
        ];
    }

    public function testCreateExpedicaoSuccessfully()
    {
        $expedicao = new Expedicoes();
        $expedicao->metodoexp= 'envio nacional';
        $this->assertTrue($expedicao->save(), 'A expedição deve conter dados validos.');
    }

    public function testCreateExpedicaoUnsuccessfully()
    {
        $expedicao = new Expedicoes();
        $expedicao->metodoexp = '';
        $this->assertFalse($expedicao->save(), 'A expedição não deve conter dados validos.');
    }

    public function testUpdateExpedicaoSuccessfully()
    {
        $expedicao = $this->tester->grabFixture('expedicoes', 'expedicao1');
        $expedicao->metodoexp= 'envio nacional';
        $this->assertTrue($expedicao->save(), 'A expedição deve ser guardada apos a edição.');
    }

    public function testUpdateExpedicaoUnsuccessfully()
    {
        $expedicao = $this->tester->grabFixture('expedicoes', 'expedicao1');
        $expedicao->metodoexp = '';
        $this->assertFalse($expedicao->save(), 'A expedição não deve ser guardada com dados invalidos.');
    }

    public function testDeleteExpedicaoSuccessfully()
    {
        $expedicao = $this->tester->grabFixture('expedicoes', 'expedicao2');
        $expedicao->delete();
        $this->assertNull(Expedicoes::findOne($expedicao->id), 'A expedição deve ser eliminada.');
    }

    public function testDeleteExpedicaoUnsuccessfully()
    {
        $expedicao = Expedicoes::findOne(['id' => 3]);
        $this->assertNull($expedicao, 'A expedição não deve existir.');
        if ($expedicao) {
            $this->assertFalse($expedicao->delete() > 0, 'Não deve ser possível eliminar uma expedição que não existe.');
        }
    }

}

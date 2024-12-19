<?php

namespace common\tests\unit;

use common\fixtures\PagamentosFixture;
use common\models\Pagamentos;
use common\tests\UnitTester;

class PagamentosTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'pagamentos' => [
                'class' => PagamentosFixture::class,
                'dataFile' => codecept_data_dir() . 'pagamentos.php',
            ],
        ];
    }

    public function testCreatePagamentoSuccessfully()
    {
        $pagamento = new Pagamentos();
        $pagamento->metodopag = 'paypal';
        $this->assertTrue($pagamento->save(), 'O pagamento deve conter dados validos.');
    }

    public function testCreatePagamentoUnsuccessfully()
    {
        $pagamento = new Pagamentos();
        $pagamento->metodopag = '';
        $this->assertFalse($pagamento->save(), 'O pagamento não deve conter dados validos.');
    }

    public function testUpdatePagamentoSuccessfully()
    {
        $pagamento = $this->tester->grabFixture('pagamentos', 'pagamento1');
        $pagamento->metodopag = 'paypal';
        $this->assertTrue($pagamento->save(), 'O pagamento deve ser guardado apos a edição.');
    }

    public function testUpdatePagamentoUnsuccessfully()
    {
        $pagamento = $this->tester->grabFixture('pagamentos', 'pagamento1');
        $pagamento->metodopag = '';
        $this->assertFalse($pagamento->save(), 'O pagamento não deve ser guardado com dados invalidos.');
    }

    public function testDeletePagamentoSuccessfully()
    {
        $pagamento = $this->tester->grabFixture('pagamentos', 'pagamento2');
        $pagamento->delete();
        $this->assertNull(Pagamentos::findOne($pagamento->id), 'O pagamento deve ser eliminado.');
    }

    public function testDeletePagamentoUnsuccessfully()
    {
        $pagamento = Pagamentos::findOne(['id' => 3]);
        $this->assertNull($pagamento, 'O pagamento não deve existir.');
        if ($pagamento) {
            $this->assertFalse($pagamento->delete() > 0, 'Não deve ser possível eliminar um pagamento que não existe.');
        }
    }

}

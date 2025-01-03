<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

class PagamentosCest
{
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
        ];
    }

    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin1234');
        $I->click('Login');
        $I->see('Início');
    }

    public function createPagamentoSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Pagamentos');
        $I->amOnRoute('/pagamentos/index');
        $I->click('Criar Método de Pagamento');
        $I->amOnRoute('/pagamentos/create');
        $I->fillField('Pagamentos[metodopag]', 'Cartão de Crédito');
        $I->click('Guardar');

        $I->seeInCurrentUrl('/pagamentos/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }

    public function updatePagamentoSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Pagamentos');
        $I->amOnRoute('/pagamentos/index');

        $pagamentos = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

        if (empty($pagamentos)) {
            $this->createPagamentoSuccessfully($I);

            $I->amOnRoute('/pagamentos/index');
            $pagamentos = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

            if (empty($pagamentos)) {
                throw new \Exception('Falha ao criar um método de pagamento para edição.');
            }
        }

        $pagamento = $pagamentos[0];
        $I->amOnPage($pagamento);

        $I->seeInCurrentUrl('/pagamentos/update');

        $I->fillField('Pagamentos[metodopag]', 'PayPal');
        $I->click('Guardar');

        $I->seeInCurrentUrl('/pagamentos/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }
}

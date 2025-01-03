<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

class ExpedicoesCest
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

    public function createExpedicaoSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Expedições');
        $I->amOnRoute('/expedicoes/index');
        $I->click('Criar Método de Expedição');
        $I->amOnRoute('/expedicoes/create');
        $I->fillField('Expedicoes[metodoexp]', 'Envio CTT');
        $I->click('Guardar');

        $I->seeInCurrentUrl('/expedicoes/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }

    public function updateExpedicaoSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Expedições');
        $I->amOnRoute('/expedicoes/index');

        $expedicoes = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

        if (empty($expedicoes)) {
            $this->createExpedicaoSuccessfully($I);

            $I->amOnRoute('/expedicoes/index');
            $expedicoes = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

            if (empty($expedicoes)) {
                throw new \Exception('Falha ao criar um método de expedição para edição.');
            }
        }

        $expedicao = $expedicoes[0];
        $I->amOnPage($expedicao);

        $I->seeInCurrentUrl('/expedicoes/update');

        $I->fillField('Expedicoes[metodoexp]', 'PayPal');
        $I->click('Guardar');

        $I->seeInCurrentUrl('/expedicoes/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }
}

<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\models\Ivas;
use yii\helpers\Url;

/**
 * Class IvasCest
 */
class IvasCest
{
    /**
     * Load fixtures before db transaction begin
     * @return array
     */
    public function _fixtures()
    {
        return [
            'ivas' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->fillField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin1234');
        $I->click('Login');
        $I->see('Início');
    }

    public function createIvaSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Ivas');
        $I->amOnRoute('ivas/index');
        $I->click('Criar Iva');
        $I->amOnRoute('/ivas/create');
        $I->amGoingTo('Criar um IVA com dados válidos.');
        $I->fillField('Ivas[percentagem]', '23');
        $I->fillField('Ivas[descricao]', 'IVA normal');
        $I->fillField('Ivas[vigor]', '1');
        $I->click('Guardar');

        $I->seeInCurrentUrl('/ivas/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }

    public function updateIvaSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Ivas');
        $I->amOnRoute('/ivas/index');

        $ivas = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

        if (empty($ivas)) {
            $this->createIvaSuccessfully($I);

            $I->amOnRoute('/ivas/index');
            $ivas = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

            if (empty($ivas)) {
                throw new \Exception('Falha ao criar um IVA para edição.');
            }
        }

        $iva = $ivas[0];
        $I->amOnPage($iva);

        $I->seeInCurrentUrl('/ivas/update');

        $I->amGoingTo('Editar os dados do Iva.');
        $I->fillField('Ivas[percentagem]', '13');
        $I->fillField('Ivas[descricao]', 'IVA reduzido');
        $I->fillField('Ivas[vigor]', '1');

        $I->click('Guardar');

        $I->seeInCurrentUrl('/ivas/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }

}

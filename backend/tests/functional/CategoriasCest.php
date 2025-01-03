<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use yii\helpers\Url;

/**
 * Class CategoriaCest
 */
class CategoriasCest
{
    /**
     * Load fixtures before db transaction begin
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
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

    public function createCategoriaSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Categorias');
        $I->amOnRoute('/categorias-produtos/index');
        $I->click('Criar Categoria');
        $I->amOnRoute('/categorias-produtos/create');
        $I->amGoingTo('Criar uma categoria com dados válidos.');
        $I->fillField('CategoriasProdutos[nome]', 'Guitarras');
        $I->fillField('CategoriasProdutos[obs]', 'Categoria dedicada a guitarras.');
        $I->click('Guardar');

        $I->seeInCurrentUrl('/categorias-produtos/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }

    public function updateCategoriaSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Categorias');
        $I->amOnRoute('/categorias-produtos/index');

        $categorias = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

        if (empty($categorias)) {
            $this->createCategoriaSuccessfully($I);

            $I->amOnRoute('/categorias-produtos/index');
            $categorias = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

            if (empty($categorias)) {
                throw new \Exception('Falha ao criar uma categoria para edição.');
            }
        }

        $categoria = $categorias[0];
        $I->amOnPage($categoria);

        $I->seeInCurrentUrl('/categorias-produtos/update');

        $I->amGoingTo('Editar os dados da categoria.');
        $I->fillField('CategoriasProdutos[nome]', 'Violinos');
        $I->fillField('CategoriasProdutos[obs]', 'Categoria dedicada a violinos.');

        $I->click('Guardar');

        $I->seeInCurrentUrl('/categorias-produtos/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }
}

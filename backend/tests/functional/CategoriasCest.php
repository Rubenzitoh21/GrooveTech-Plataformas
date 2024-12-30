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

    public function createCategoriaCamposVazios(FunctionalTester $I)
    {
        $I->amOnRoute('/categorias-produtos/create');
        $I->amGoingTo('Tentar criar uma categoria sem preencher os campos obrigatórios.');
        $I->click('Guardar');
        $I->see('Este campo não pode ficar em branco.', '//div[@class="help-block"]');
    }

    public function creaateCategoriaComDadosInvalidos(FunctionalTester $I)
    {
        $I->amOnRoute('/categorias-produtos/create');
        $I->amGoingTo('Tentar criar uma categoria com dados que excedem os limites permitidos.');
        $I->fillField('CategoriasProdutos[nome]', str_repeat('A', 51));
        $I->fillField('CategoriasProdutos[obs]', str_repeat('B', 201));
        $I->click('Guardar');
        $I->see('Nome deve conter no máximo 50 caracteres.', '//div[@class="help-block"]');
        $I->see('Observações deve conter no máximo 200 caracteres.', '//div[@class="help-block"]');
    }

    public function createCategoriaSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/categorias-produtos/create');
        $I->amGoingTo('Criar uma categoria com dados válidos.');
        $I->fillField('CategoriasProdutos[nome]', 'Guitarras');
        $I->fillField('CategoriasProdutos[obs]', 'Categoria dedicada a guitarras.');
        $I->click('Guardar');

        // Verificar redirecionamento para a view da categoria criada
        $I->see('Guitarras');
        $I->see('Categoria dedicada a guitarras.');
        $I->dontSeeInCurrentUrl('/categorias-produtos/create');
    }
}

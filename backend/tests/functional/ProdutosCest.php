<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use yii\helpers\Url;

/**
 * Class CategoriaCest
 */
class ProdutosCest
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

    public function createProdutoSuccessfully(FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Produtos');
        $I->amOnRoute('/produtos/index');
        $I->click('Criar Produto');
        $I->amOnRoute('/produtos/create');

        // Verificar se existem categorias
        $I->seeElement("select[name='Produtos[categorias_produtos_id]'] option");
        $categorias = $I->grabMultiple("select[name='Produtos[categorias_produtos_id]'] option", "value");
        if (count($categorias) < 2) {
            $I->click('Criar Categoria');
            $I->seeInCurrentUrl('/categorias-produtos/create');
            $I->fillField('CategoriasProdutos[nome]', 'Teste');
            $I->fillField('CategoriasProdutos[obs]', 'Categoria teste.');
            $I->click('Guardar');
            $I->seeInCurrentUrl('/produtos/create');

            $categorias = $I->grabMultiple("select[name='Produtos[categorias_produtos_id]'] option", "value");
            if (empty($categorias)) {
                throw new \Exception('Falha ao criar a categoria.');
            }
        }

        // Verificar se existem IVAs
        $ivas = $I->grabMultiple("select[name='Produtos[ivas_id]'] option", "value");
        if (count($ivas) < 2) {
            $I->click('Criar Iva');
            $I->seeInCurrentUrl('/ivas/create');
            $I->fillField('Ivas[percentagem]', '23');
            $I->fillField('Ivas[descricao]', 'IVA normal');
            $I->fillField('Ivas[vigor]', '1');
            $I->click('Guardar');
            $I->seeInCurrentUrl('/produtos/create');

            $ivas = $I->grabMultiple("select[name='Produtos[ivas_id]'] option", "value");
            if (empty($ivas)) {
                throw new \Exception('Falha ao criar o IVA.');
            }
        }

        if (count($categorias) < 2 || count($ivas) < 2) {
            throw new \Exception('Não existem opções de categorias ou IVAs disponíveis para o teste.');
        }

        $I->selectOption('select[name="Produtos[categorias_produtos_id]"]', $categorias[1]);
        $I->selectOption('select[name="Produtos[ivas_id]"]', $ivas[1]);

        $I->fillField('Produtos[nome]', 'Produto Teste');
        $I->fillField('Produtos[descricao]', 'Descrição para teste');
        $I->fillField('Produtos[preco]', '10.99');
        $I->fillField('Produtos[obs]', 'Produto para testes.');

        $I->click('Guardar');

        $I->seeInCurrentUrl('/produtos/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }

    public function updateProdutoSuccessfully (FunctionalTester $I)
    {
        $I->amOnRoute('/site/index');
        $I->click('Produtos');
        $I->amOnRoute('/produtos/index');

        $produtos = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

        if (empty($produtos)) {
            $this->createProdutoSuccessfully($I);

            $I->amOnRoute('/produtos/index');
            $produtos = $I->grabMultiple("table tbody tr td a[title='Update']", "href");

            if (empty($produtos)) {
                throw new \Exception('Falha ao criar um produto para edição.');
            }
        }

        $produto = $produtos[0];
        $I->amOnPage($produto);

        $I->seeInCurrentUrl('/produtos/update');

        $I->fillField('Produtos[nome]', 'Produto Editado');
        $I->fillField('Produtos[descricao]', 'Descrição editada');
        $I->fillField('Produtos[preco]', '15.99');
        $I->fillField('Produtos[obs]', 'Observações editadas.');

        $I->click('Guardar');

        $I->seeInCurrentUrl('/produtos/view?id='.$I->grabFromCurrentUrl('~id=(\d+)~'));
    }


}

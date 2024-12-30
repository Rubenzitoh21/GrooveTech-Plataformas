<?php
namespace common\tests\unit;

use Yii;
use common\models\Produtos;

class ProdutosTest extends \Codeception\Test\Unit
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function _fixtures()
    {
        return [
            'produtos' => [
                'class' => 'common\fixtures\ProdutosFixture',
                'dataFile' => '@common/tests/_data/produtos.php',
            ],
            'categorias' => [
                'class' => 'common\fixtures\CategoriasFixture',
                'dataFile' => '@common/tests/_data/categorias.php',
            ],
            'ivas' => [
                'class' => 'common\fixtures\IvasFixture',
                'dataFile' => '@common/tests/_data/ivas.php',
            ],
        ];
    }


    public function testCreateProdutoSuccessfully()
    {
        $produto = new Produtos();
        $produto->nome = 'Produto Teste';
        $produto->descricao = 'Descrição válida do produto';
        $produto->preco = 100.00;
        $produto->categorias_produtos_id = 1;
        $produto->ivas_id = 1;
        $this->assertTrue($produto->save(), 'O produto deve ser criado com sucesso');
    }

    public function testCreateProdutoUnsuccessfully()
    {
        $produto = new Produtos();
        $produto->nome = '';
        $produto->descricao = 'Descrição do produto';
        $produto->preco = -50;
        $produto->categorias_produtos_id = 99999;
        $produto->ivas_id = 99999;
        $this->assertFalse($produto->save(), 'O produto não deve ser criado com dados inválidos');
    }

    public function testUpdateProdutoSuccessfully()
    {
        $produto = Produtos::find()->where(['nome' => 'Produto 1'])->one();
        $this->assertNotNull($produto, 'Produto para atualização não encontrado');

        $produto->nome = 'Produto Atualizado';
        $produto->descricao = 'Descrição atualizada';
        $produto->preco = 120.00;
        $this->assertTrue($produto->save(), 'O produto deve ser atualizado com sucesso');
    }

    public function testUpdateProdutoUnsuccessfully()
    {
        $produto = Produtos::find()->where(['nome' => 'Produto 1'])->one();
        $this->assertNotNull($produto, 'Produto para atualização não encontrado');

        $produto->nome = '';
        $produto->descricao = '';
        $produto->preco = -100;
        $this->assertFalse($produto->save(), 'O produto não deve ser atualizado com dados inválidos');
    }


    public function testDeleteProdutoSuccessfully()
    {
        $produto = Produtos::find()->where(['nome' => 'Produto 1'])->one();
        $this->assertNotNull($produto, 'Produto para eliminar não encontrado');

        $produtoId = $produto->id;
        $produto->delete();

        $this->assertNull(Produtos::findOne($produtoId), 'O produto deve ser eliminado com sucesso');
    }

    public function testDeleteProdutoUnsuccessfully()
    {
        $produto = Produtos::findOne(['id' => 9999]);
        $this->assertNull($produto, 'O produto não deve existir');

        if ($produto) {
            $this->assertFalse($produto->delete() > 0, 'Não deve ser possível eliminar um produto que não existe');
        } else {
            $this->assertTrue(true, 'Produto inexistente, como esperado');
        }
    }
}

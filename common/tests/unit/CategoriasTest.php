<?php

namespace common\tests\unit;

use common\fixtures\CategoriasFixture;
use common\models\CategoriasProdutos;
use common\tests\UnitTester;

class CategoriasTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    public function _fixtures()
    {
        return [
            'categorias' => [
                'class' => CategoriasFixture::class,
                'dataFile' => codecept_data_dir() . 'categorias.php',
            ],
        ];
    }

    public function testCreateCategoriaSuccessfully()
    {
        $categoria = new CategoriasProdutos();
        $categoria->nome = 'categoria exemplo';
        $categoria->obs = 'sem obs';

        $this->assertTrue($categoria->save(), 'A categoria deve conter dados validos.');
    }

    public function testCreateCategoriaUnsuccessfully()
    {
        $categoria = new CategoriasProdutos();
        $categoria->nome = '';
        $categoria->obs = '';

        $this->assertFalse($categoria->save(), 'A categoria não deve conter dados validos.');
    }

    public function testUpdateCategoriaSuccessfully()
    {
        $categoria = $this->tester->grabFixture('categorias', 'categoria1');
        $categoria->nome = 'categoria editada';
        $categoria->obs = 'com obs';
        $this->assertTrue($categoria->save(), 'A categoria deve ser guardada apos a edição.');
    }

    public function testUpdateCategoriaUnsuccessfully()
    {
        $categoria = $this->tester->grabFixture('categorias', 'categoria1');
        $categoria->nome = '';
        $categoria->obs = '';
        $this->assertFalse($categoria->save(), 'A categoria não deve ser guardada com dados invalidos.');
    }

    public function testDeleteCategoriaSuccessfully()
    {
        $categoria = $this->tester->grabFixture('categorias', 'categoria2');
        $categoria->delete();
        $this->assertNull(CategoriasProdutos::findOne($categoria->id), 'A categoria deve ser eliminada.');
    }

    public function testDeleteCategoriaUnsuccessfully()
    {
        $categoria = CategoriasProdutos::findOne(['id' => 3]);
        $this->assertNull($categoria, 'A categoria não deve existir.');
        if ($categoria) {
            $this->assertFalse($categoria->delete() > 0, 'Não deve ser possível eliminar uma categoria que não existe.');
        }
    }

}

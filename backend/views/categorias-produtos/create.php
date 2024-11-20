<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CategoriasProdutos $model */

$this->title = 'Criar Categoria de Produtos';
$this->params['breadcrumbs'][] = ['label' => 'Categorias Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorias-produtos-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

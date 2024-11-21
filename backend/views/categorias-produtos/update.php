<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CategoriasProdutos $model */

$this->title = 'Editar Categoria de Produtos: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Categorias Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="categorias-produtos-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

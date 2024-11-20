<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */

$this->title = 'Editar Produto: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Produtos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="produtos-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

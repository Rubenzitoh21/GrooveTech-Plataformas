<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LinhasFaturas $model */

$this->title = 'Update Linhas Faturas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Linhas Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="linhas-faturas-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

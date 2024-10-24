<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Expedicoes $model */

$this->title = 'Update Expedicoes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Expedicoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expedicoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Ivas $model */

$this->title = 'Editar Iva: ' . $model->percentagem . '%';
$this->params['breadcrumbs'][] = ['label' => 'Ivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ivas-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

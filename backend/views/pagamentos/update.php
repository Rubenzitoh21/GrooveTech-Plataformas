<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Pagamentos $model */

$this->title = 'Editar Pagamento: ' . $model->metodopag;
$this->params['breadcrumbs'][] = ['label' => 'Pagamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pagamentos-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

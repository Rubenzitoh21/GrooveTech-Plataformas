<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Empresas $model */

$this->title = 'Editar Dados da Empresa';
$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="empresas-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

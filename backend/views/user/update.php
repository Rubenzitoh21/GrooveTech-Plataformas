<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = 'Atualização dos Dados do Trabalhador: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gestão de Trabalhadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">


    <?= $this->render('_updateForm', [
        'model' => $model,
    ]) ?>

</div>

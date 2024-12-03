<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Expedicoes $model */

$this->title = 'Criar Método de Expedição';
$this->params['breadcrumbs'][] = ['label' => 'Expedicoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expedicoes-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Imagens $model */

$this->title = 'Adicionar Imagens';
$this->params['breadcrumbs'][] = ['label' => 'Imagens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imagens-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

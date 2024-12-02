<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Expedicoes $model */

$this->title = 'Create Expedicoes';
$this->params['breadcrumbs'][] = ['label' => 'Expedicoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expedicoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

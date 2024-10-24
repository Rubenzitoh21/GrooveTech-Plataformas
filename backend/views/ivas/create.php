<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Ivas $model */

$this->title = 'Create Ivas';
$this->params['breadcrumbs'][] = ['label' => 'Ivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ivas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\LinhasFaturas $model */

$this->title = 'Create Linhas Faturas';
$this->params['breadcrumbs'][] = ['label' => 'Linhas Faturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="linhas-faturas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

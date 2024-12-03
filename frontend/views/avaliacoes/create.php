<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Avaliacoes $model */

$this->title = 'Avaliar Produto: ' . $model->linhasFaturas->produtos->nome;
?>
<div class="avaliacoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'linhaFaturaId' => $linhaFaturaId,
    ]) ?>

</div>

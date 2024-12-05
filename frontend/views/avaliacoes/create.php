<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Avaliacoes $model */

$this->title = 'Avaliar Produto: ' . $model->linhasFaturas->produtos->nome;
?>
<div class="avaliacoes-create">
    <a class="btn btn-success" href="javascript:window.history.back();">
        <i class="fa fa-arrow-left"></i> Voltar
    </a>
    <br>
    <br>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'linhaFaturaId' => $linhaFaturaId,
    ]) ?>

</div>

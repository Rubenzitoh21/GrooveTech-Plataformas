<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Pagamentos $model */

$this->title = 'Criar Método de Pagamento';
$this->params['breadcrumbs'][] = ['label' => 'Pagamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pagamentos-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

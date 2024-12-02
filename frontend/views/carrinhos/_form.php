<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Carrinhos $model */
/** @var common\models\Faturas $fatura */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="carrinhos-form">

    <?php $form = ActiveForm::begin(); ?>

    <ul class="list-unstyled templatemo-accordion">
        <!-- Método de Pagamento -->
        <li class="pb-3">
            <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                Método de Pagamento
                <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
            </a>
            <ul class="collapse show list-unstyled pl-3">
                <?php foreach (\common\models\Pagamentos::find()->all() as $pagamento): ?>
                    <li>
                        <?= Html::radio('Faturas[pagamentos_id]', false, [
                            'value' => $pagamento->id,
                            'label' => Html::encode($pagamento->metodopag),
                        ]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>

        <!-- Método de Envio -->
        <li class="pb-3">
            <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                Método de Envio
                <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
            </a>
            <ul class="collapse show list-unstyled pl-3">
                <?php foreach (\common\models\Expedicoes::find()->all() as $expedicao): ?>
                    <li>
                        <?= Html::radio('Faturas[expedicoes_id]', false, [
                            'value' => $expedicao->id,
                            'label' => Html::encode($expedicao->metodoexp),
                        ]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>

    <div class="form-group mt-3">
        <?= Html::submitButton('Encomendar', ['class' => 'btn border-secondary rounded-pill py-3 px-4 text-uppercase w-100 text-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

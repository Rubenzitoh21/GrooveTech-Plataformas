<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use kartik\date\DatePicker;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use yii\web\YiiAsset;


$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'username')->label('Nome de Utilizador')->textInput([
                'autofocus' => true,
                'placeholder' => 'Insira o seu nome de utilizador'
            ]) ?>

            <?= $form->field($model, 'primeironome')->label('Nome')->textInput(['placeholder' => 'Insira o seu primeiro nome']) ?>

            <?= $form->field($model, 'apelido')->label('Apelido')->textInput(['placeholder' => 'Insira o seu apelido']) ?>

            <?php
            $this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css');
            $this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js', ['depends' => [yii\web\JqueryAsset::class]]);

            echo $form->field($model, 'dtanasc')->label('Data de Nascimento')->textInput([
                'id' => 'datepicker',
                'placeholder' => 'Selecione a sua data de nascimento'
            ]);

            $this->registerJs("
                $('#datepicker').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    endDate: '-16y'
                });
            ");
            ?>



            <?= $form->field($model, 'email')->label('Email')->textInput(['placeholder' => 'Insira o seu email']); ?>

            <?= $form->field($model, 'password')
                ->textInput(['type' => 'password', 'placeholder' => 'Insira a sua password'])
                ->label('Password'); ?>

            <?= $form->field($model, 'genero')->label('Género')->dropDownList([
                "M" => 'Masculino',
                "F" => 'Feminino',
            ],
                ['prompt' => 'Selecione o seu género']
            );

            ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

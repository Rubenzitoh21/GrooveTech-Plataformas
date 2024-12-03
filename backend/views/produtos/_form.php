<?php

use common\models\Ivas;
use common\models\CategoriasProdutos;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Produtos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="produtos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descricao')->label('Descrição')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'preco')->label('Preço')->textInput() ?>

    <?= $form->field($model, 'obs')->label('Observações')->textarea(['maxlength' => true]) ?>

    <?= $form->field($model, 'categorias_produtos_id')->label('Categoria')->dropDownList(
        ArrayHelper::map(CategoriasProdutos::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecione a categoria do produto']
    ) ?>

    <?= Html::a('Criar Categoria', ['categorias-produtos/create', 'urlCallback' => 'produto'], ['class' => 'btn btn-primary']) ?>

    <?= $form->field($model, 'ivas_id')->label('IVA')->dropDownList(
        ArrayHelper::map(Ivas::find()->where(['vigor' => '1'])->all(), 'id', 'percentagem'),
        ['prompt' => 'Selecione o IVA do produto']
    ) ?>

    <?= Html::a('Criar Iva', ['iva/create', 'urlCallback' => 'produto'], ['class' => 'btn btn-primary']) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

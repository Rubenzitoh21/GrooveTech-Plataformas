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

    <div class="form-group">
        <label class="form-label">Categoria</label>
        <div class="d-flex align-items-start">
            <div class="flex-grow-1">
                <?= $form->field($model, 'categorias_produtos_id', ['template' => "{input}\n{error}"])
                    ->dropDownList(
                        ArrayHelper::map(CategoriasProdutos::find()->all(), 'id', 'nome'),
                        ['prompt' => 'Selecione a categoria do produto']
                    )->label(false) ?>
            </div>
            <?= Html::a('Criar Categoria', ['categorias-produtos/create', 'urlCallback' => 'produto'], [
                'class' => 'btn btn-primary ms-2',
            ]) ?>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">IVA</label>
        <div class="d-flex align-items-start">
            <div class="flex-grow-1">
                <?= $form->field($model, 'ivas_id', ['template' => "{input}\n{error}"])
                    ->dropDownList(
                        ArrayHelper::map(Ivas::find()->where(['vigor' => '1'])->all(), 'id', 'percentagem'),
                        ['prompt' => 'Selecione o IVA do produto']
                    )->label(false) ?>
            </div>
            <?= Html::a('Criar Iva', ['iva/create', 'urlCallback' => 'produto'], [
                'class' => 'btn btn-primary ms-2',
            ]) ?>
        </div>
    </div>

    <?php if (!$model->isNewRecord && empty($model->imagens)): ?>
        <?= Html::a('Adicionar Imagem', ['imagens/create', 'produto_id' => $model->id, 'urlCallback' => 'produto'], ['class' => 'btn btn-success']) ?>
        <br><br>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

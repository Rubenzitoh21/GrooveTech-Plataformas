<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $model */
/**  @var common\models\UserProfile $modeluserprofile*/
/** @var yii\widgets\ActiveForm $form */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->label('Username')->textInput() ?>
    <?= $form->field($model, 'email')->label('Email')->textInput() ?>
    <?= $form->field($model, 'password')->label('Password')->passwordInput(['type' => 'password']) ?>
    <?= $form->field($model, 'role')->label('Role')->dropDownList([
        "admin" => 'Administrador',
        "gestor" => 'Gestor',
    ],
        ['prompt' => 'Selecione o tipo de role do user']
    );
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

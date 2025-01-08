<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Gestão de Trabalhadores', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <p>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende eliminar esta conta?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
//        'model' => $model,
//        'attributes' => [
//            'id',
//            'username',
//            'auth_key',
//            'password_hash',
//            'password_reset_token',
//            'email:email',
//            'status',
//            'created_at',
//            'updated_at',
//            'verification_token',
//        ],
        'model' => $model,
        'attributes' => [
            /*'id',*/
            'username',
            'email',
            [
                'attribute' => 'auth.item_name',
                'label' => 'Role',
                'value' => function ($model) {
                    switch ($model->auth['item_name']) {
                        case 'admin':
                            return 'Administrador';
                        case 'gestor':
                            return 'Gestor';
                        default:
                            return $model->auth['item_name'];
                    }
                },
            ],
        ],
    ]) ?>

</div>

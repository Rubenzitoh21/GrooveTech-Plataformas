<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Empresas $model */

$this->title = $model->designacaosocial;
$this->params['breadcrumbs'][] = ['label' => 'Empresas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
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
<div class="empresas-view">
    <div class="row">
        <div class="col-12 col-sm-1 col-md-1">
            <?= Html::img('@web/images/logo_gt.png', [
                'alt' => 'Groove Tech Logo',
                'class' => 'img-thumbnail'
            ]) ?>
        </div>
    </div>
    <br>
    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'designacaosocial',
            'email',
            'telefone',
            'nif',
            'rua',
            'codigopostal',
            'localidade',
            [
                'attribute' => 'capitalsocial',
                'value' => function ($model) {
                    return number_format($model->capitalsocial, 2, ',', '.') . ' €';
                },
            ],
        ],
    ]) ?>

</div>

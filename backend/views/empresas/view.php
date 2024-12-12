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

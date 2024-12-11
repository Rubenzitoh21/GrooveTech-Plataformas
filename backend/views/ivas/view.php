<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Ivas $model */

$this->title = $model->percentagem . '%';
$this->params['breadcrumbs'][] = ['label' => 'Ivas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ivas-view">

    <p>
        <?= Html::a('Voltar', ['index'], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende eliminar este Iva?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//            'id',
            'percentagem',
            'descricao',
            [
                'attribute' => 'vigor',
                'label' => 'Em Vigor',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::tag(
                        'div',
                        Html::checkbox('vigor', $model->vigor == 1, [
                            'data-toggle' => 'toggle',
                            'data-on' => 'Em Vigor',
                            'data-off' => 'Inativo',
                            'data-onstyle' => 'primary',
                            'data-offstyle' => 'danger',
                            'disabled' => true,
                        ]),
                        ['class' => 'form-check']
                    );
                },
            ],
        ],
    ]) ?>

</div>

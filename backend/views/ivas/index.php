<?php

use common\models\Ivas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\IvasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Ivas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ivas-index">

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
        <?= Html::a('Criar Iva', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'percentagem',
            'descricao',
            [
                'attribute' => 'vigor',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::beginForm(['toggle-vigor', 'id' => $model->id], 'post', [
                            'style' => 'display:inline-block'
                        ]) .
                        Html::checkbox('vigor', $model->vigor == 1, [
                            'onchange' => 'if(confirm("Tem a certeza que deseja alterar o vigor deste Iva?")) { this.form.submit(); } else { location.reload(); }',
                            'data-toggle' => 'toggle',
                            'data-on' => 'Em Vigor',
                            'data-off' => 'Inativo',
                            'data-onstyle' => 'primary',
                            'data-offstyle' => 'danger',
                        ]) .
                        Html::endForm();
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Ivas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
</div>

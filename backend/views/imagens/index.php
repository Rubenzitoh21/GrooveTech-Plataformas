<?php

use common\models\Imagens;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\ImagensSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Imagens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="imagens-index">
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

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    --><?php //= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//
//            'id',
//            'fileName',
//            'produto_id',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, Imagens $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->id]);
//                 }
//            ],
//        ],
//    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'label' => 'Imagens',
                'format' => 'raw',
                'value' => function ($model) {

                    $imagePath = '@web/images/' . $model->fileName;

                    return Html::img($imagePath, ['alt' => 'Imagens', 'style' => 'max-width:100px;']);
                },
            ],
            [
                'attribute' => 'nomeProduto',
                'label' => 'Produto',
                'format' => 'raw',
                'value' => function ($model) {
                    $produto = $model->produto;
                    if ($produto) {
                        return Html::a(Html::encode($produto->nome), Url::to(['produtos/view', 'id' => $produto->id]));
                    }
                    return null;
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Imagens $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>

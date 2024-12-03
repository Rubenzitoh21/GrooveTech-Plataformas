<?php

use common\models\Faturas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\FaturasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Faturas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faturas-index">

<!---->
<!--    <p>-->
<!--        --><?php //= Html::a('Create Faturas', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'data',
            [
                'attribute' => 'valortotal',
                'label' => 'Valor Total',
                'value' => function ($model) {
                    return number_format($model->valortotal, 2, ',', '.') . ' €';
                },
            ],
            'status',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->userProfile->primeironome . ' ' . $model->user->userProfile->apelido;
                },
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Faturas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>

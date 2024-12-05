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
                'label' => 'Utilizador',
                'format' => 'raw',
                'value' => function ($model) {
                    $user = $model->user;
                    if ($user) {
                        return Html::a(
                            Html::encode($user->userProfile->primeironome . ' ' . $user->userProfile->apelido),
                            ['user-profile/view', 'id' => $user->userProfile->id, 'user_id' => $user->id],
                        );
                    }
                    return 'Utilizador não disponível';
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

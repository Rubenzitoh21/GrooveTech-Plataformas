<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Gestão de Trabalhadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">


    <p>
        <?= Html::a('Criar Trabalhador', ['create'], ['id' => 'criar-trabalhador', 'class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php /*= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            //'email:email',
            //'status',
            //'created_at',
            //'updated_at',
            //'verification_token',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); */?>

    <?=
    GridView::widget([
        'id' => 'trabalhadores-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/
            /*'id',*/
            'username',
            'email',
            [
                'attribute' => 'role',
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

            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, User $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],

    ]);

    ?>


</div>

<?php

use backend\models\Empresas;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Dados da Empresa';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="empresas-index">

<!---->
<!--    <p>-->
<!--        --><?php //= Html::a('Create Empresas', ['create'], ['class' => 'btn btn-success']) ?>
<!--    </p>-->


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'designacaosocial',
            'email',
            'telefone',
            'nif',
            //'rua',
            //'codigopostal',
            //'localidade',
            //'capitalsocial',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update}',
                'urlCreator' => function ($action, Empresas $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>

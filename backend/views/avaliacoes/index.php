<?php

use common\models\Avaliacoes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\AvaliacoesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Avaliacoes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="avaliacoes-index">


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'comentario',
            'dtarating',
            [
                'attribute' => 'rating',
                'format' => 'raw', // Permite HTML bruto na célula
                'value' => function ($model) {
                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= $i <= $model->rating
                            ? '<i class="fa fa-star text-warning"></i>' // Estrelas preenchidas
                            : '<i class="fa fa-star text-secondary"></i>'; // Estrelas vazias
                    }
                    return $stars;
                },
                'contentOptions' => ['class' => 'text-center'], // Centraliza as estrelas na coluna
            ],

            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user->userProfile->primeironome . ' ' . $model->user->userProfile->apelido;
                },
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, Avaliacoes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>

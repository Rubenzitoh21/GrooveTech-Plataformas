<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class AvaliacaoController extends \yii\rest\ActiveController
{
    public $modelClass = 'common\models\Avaliacoes';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class CarrinhoController extends ActiveController
{
    public $modelClass = 'common\models\Carrinhos';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
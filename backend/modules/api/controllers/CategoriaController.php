<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class CategoriaController extends ActiveController
{
    public $modelClass = 'common\models\CategoriasProdutos';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
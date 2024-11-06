<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class CategoriaController extends ActiveController
{
    public $modelClass = 'common\models\Categorias';

    public function actionIndex()
    {
        return $this->render('index');
    }
}
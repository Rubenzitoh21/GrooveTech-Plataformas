<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;

class FaturaController extends ActiveController
{
    public $modelClass = 'common\models\Faturas';

    public function actionIndex()
    {
        return $this->render('index');
    }

}
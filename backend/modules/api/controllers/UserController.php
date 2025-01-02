<?php

namespace backend\modules\api\controllers;

use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        return $this->render('index');
    }


}

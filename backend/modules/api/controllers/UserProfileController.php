<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class UserProfileController extends ActiveController
{

    public $modelClass = 'common\models\UserProfile';
    public $userModelClass = 'common\models\User';

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Get profile data for a user based on ID and access token
     * @param $id
     * @param $accessToken
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionGetProfile($id)
    {
        $accessToken = Yii::$app->request->get('access-token');

        if (!$accessToken) {
            throw new \yii\web\BadRequestHttpException("Missing required parameter: access-token");
        }


        $userModel = new $this->userModelClass;
        $user = $userModel::find()->where(['id' => $id, 'auth_key' => $accessToken])->one();

        if ($user == null) {
            throw new NotFoundHttpException("Utilizador não encontrado com o ID " . $id . " e o token de acesso " . $accessToken);
        }

        $userProfile = new $this->modelClass;
        $userProfileData = $userProfile::find()->where(['user_id' => $user->id])->one();

        return ['user' => $user, 'profile' => $userProfileData];
    }
}
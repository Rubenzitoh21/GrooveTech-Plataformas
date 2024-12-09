<?php

namespace frontend\controllers;

use common\models\UserProfile;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * PerfilController implements the CRUD actions for UserProfile model.
 */
class PerfilController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index'],
                    'rules' => [
                        [
                            'actions' => ['index'],
                            'allow' => true,
                            'roles' => ['cliente'],
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        throw new \yii\web\ForbiddenHttpException('Acesso negado.');
                    },
                ],
                'verbs' => [
                    'class' => \yii\filters\VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex($mode = null)
    {
        if (!Yii::$app->user->can('editarDadosPessoais')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $userId = Yii::$app->user->identity->id;
        $userData = User::findOne($userId);
        $userDataAdditional = UserProfile::findOne(['user_id' => $userId]);

        $passwordModel = new User(['scenario' => User::SCENARIO_PASSWORD]);

        if (Yii::$app->request->isPost) {
            switch ($mode) {
                case 'password':
                    if ($passwordModel->load(Yii::$app->request->post()) && $passwordModel->validate()) {
                        $userData->setPassword($passwordModel->newPassword);
                        $userData->generateAuthKey();

                        if ($userData->save()) {
                            Yii::$app->user->identity = User::findOne($userId);
                            Yii::$app->user->login($userData);

                            Yii::$app->session->setFlash('success', 'Password alterada com sucesso!');
                            return $this->refresh();
                        } else {
                            Yii::$app->session->setFlash('error', 'Erro ao alterar a password.');
                        }
                    }
                    break;

                case 'data':
                    if ($userData->load(Yii::$app->request->post()) && $userDataAdditional->load(Yii::$app->request->post())) {
                        if ($userData->save() && $userDataAdditional->save()) {
                            Yii::$app->session->setFlash('success', 'Dados pessoais atualizados com sucesso!');
                            return $this->redirect(['perfil/index']);
                        } else {
                            Yii::$app->session->setFlash('error', 'Erro ao atualizar os dados pessoais.');
                        }
                    }
                    break;

                case 'morada':
                    if ($userDataAdditional->load(Yii::$app->request->post())) {
                        if ($userDataAdditional->save()) {
                            Yii::$app->session->setFlash('success', 'Dados de morada atualizados com sucesso!');
                            return $this->redirect(['perfil/index']);
                        } else {
                            Yii::$app->session->setFlash('error', 'Erro ao atualizar os dados de morada.');
                        }
                    }
                    break;

                default:
                    Yii::$app->session->setFlash('error', 'Operação inválida.');
                    break;
            }
        }

        return $this->render('index', [
            'userData' => $userData,
            'userDataAdditional' => $userDataAdditional,
            'mode' => $mode,
            'passwordModel' => $passwordModel,
        ]);
    }
}

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
        $user = Yii::$app->user->identity;

        if (Yii::$app->request->isPost) {
            switch ($mode) {
                case 'password':
                    $user->scenario = User::SCENARIO_PASSWORD;
                    if ($user->load(Yii::$app->request->post())) {
                        if ($user->validate()) {
                            if (empty($user->newPassword)) {
                                Yii::$app->session->setFlash('error', 'A nova password não pode estar vazia.');
                                break;
                            }
                            $userData->setPassword($user->newPassword);

                            if ($userData->save(false)) {
                                Yii::$app->session->setFlash('success', 'Password alterada com sucesso!');
                                return $this->redirect(['perfil/index']);
                            } else {
                                Yii::$app->session->setFlash('error', 'Erro ao alterar a password.');
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'Dados inválidos. Verifique os campos.');
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
            'passwordModel' => $user,
        ]);
    }
}

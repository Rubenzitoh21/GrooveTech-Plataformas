<?php

namespace backend\controllers;

use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PerfilController implements the CRUD actions for User model.
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
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,

                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'changepassword', 'update'],
                            'roles' => ['gestor'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('editarDadosPessoais')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $user = Yii::$app->user->identity;

        if ($user->load(Yii::$app->request->post()) && $user->save()) {
            Yii::$app->session->setFlash('success', 'Perfil alterado com sucesso');
            return $this->refresh();
        }

        return $this->render('index', ['model' => $user]);
    }

    /**
     * Displays a single User model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('editarDadosPessoais')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionChangepassword()
    {
        if (!Yii::$app->user->can('editarDadosPessoais')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $user = Yii::$app->user->identity;
        $user->setScenario(User::SCENARIO_PASSWORD);
        $loadedPost = $user->load(Yii::$app->request->post());

        if ($loadedPost && $user->validate()) {
            $user->password = $user->newPassword;
            $user->save(false);
            Yii::$app->session->setFlash('success', 'Password alterada com sucesso');
            return $this->redirect(['index']);
        }

        return $this->render('changepassword', [
            'model' => $user,
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}

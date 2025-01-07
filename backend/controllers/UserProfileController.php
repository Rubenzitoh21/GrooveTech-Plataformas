<?php

namespace backend\controllers;

use common\models\Avaliacoes;
use common\models\Carrinhos;
use common\models\Faturas;
use common\models\ProdutosCarrinhos;
use common\models\UserProfile;
use backend\models\UserProfileSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserProfileController implements the CRUD actions for UserProfile model.
 */
class UserProfileController extends Controller
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
                            'actions' => ['index', 'view', 'update', 'delete'],
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all UserProfile models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verUsers')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $searchModel = new UserProfileSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserProfile model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('verUsers')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserProfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('criarUsers')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = new UserProfile();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserProfile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('editarUsers')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserProfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('apagarUsers')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $model = $this->findModel($id);
        $user = $model->user;

        try {
            $faturas = Faturas::find()->where(['user_id' => $user->id])->exists();
            $avaliacoes = Avaliacoes::find()->where(['user_id' => $user->id])->exists();

            if ($faturas || $avaliacoes) {
                Yii::$app->session->setFlash('error', 'Não é possível eliminar este Utilizador porque está associado a outros registos.');
            } else {

                $carrinhos = Carrinhos::find()->where(['user_id' => $user->id])->all();
                foreach ($carrinhos as $carrinho) {
                    ProdutosCarrinhos::deleteAll(['carrinhos_id' => $carrinho->id]);
                }
                Carrinhos::deleteAll(['user_id' => $user->id]);

                $model->delete();
                $user->delete();

                Yii::$app->session->setFlash('success', 'Utilizador eliminado com sucesso.');
            }
        } catch (\yii\db\IntegrityException $e) {
            Yii::$app->session->setFlash('error', 'Não é possível eliminar este Utilizador porque está associado a outros registos.');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Ocorreu um erro ao tentar eliminar o Utilizador.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the UserProfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UserProfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserProfile::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

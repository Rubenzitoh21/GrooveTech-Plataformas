<?php

namespace frontend\controllers;

use backend\models\Empresas;
use common\models\Faturas;
use common\models\LinhasFaturas;
use common\models\Pagamentos;
use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FaturasController implements the CRUD actions for Faturas model.
 */
class FaturasController extends Controller
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
                    'only' => ['index', 'view', 'print'],
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'print'],
                            'allow' => true,
                            'roles' => ['cliente'],
                        ],
                    ],
                    'denyCallback' => function ($rule, $action) {
                        throw new \yii\web\ForbiddenHttpException('Acesso negado.');
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Faturas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verFaturas')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $userId = Yii::$app->user->id;

        $dataProvider = new ActiveDataProvider([
            'query' => Faturas::find()->where(['user_id' => $userId]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Faturas model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        if (!Yii::$app->user->can('verFaturas')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $empresa = Empresas::find()->one();
        $profile = UserProfile::find()->where(['user_id' => Yii::$app->user->id])->one();
        return $this->render('view', [
            'empresa' => $empresa,
            'profile' => $profile,
            'model' => $this->findModel($id),
        ]);
    }

//    /**
//     * Creates a new Faturas model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return string|\yii\web\Response
//     */
//    public function actionCreate()
//    {
//        $model = new Faturas();
//
//        if ($this->request->isPost) {
//            if ($model->load($this->request->post()) && $model->save()) {
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        } else {
//            $model->loadDefaultValues();
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Updates an existing Faturas model.
//     * If update is successful, the browser will be redirected to the 'view' page.
//     * @param int $id ID
//     * @return string|\yii\web\Response
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionUpdate($id)
//    {
//        $model = $this->findModel($id);
//
//        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        }
//
//        return $this->render('update', [
//            'model' => $model,
//        ]);
//    }
//
//    /**
//     * Deletes an existing Faturas model.
//     * If deletion is successful, the browser will be redirected to the 'index' page.
//     * @param int $id ID
//     * @return \yii\web\Response
//     * @throws NotFoundHttpException if the model cannot be found
//     */
//    public function actionDelete($id)
//    {
//        $this->findModel($id)->delete();
//
//        return $this->redirect(['index']);
//    }

    /**
     * Finds the Faturas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Faturas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (!Yii::$app->user->can('verFaturas')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        if (($model = Faturas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrint($id)
    {
        $empresa = Empresas::find()->one();
        $model = $this->findModel($id);
        $this->layout = false;
        return $this->render('print-template',
            [
                'model' => $model,
                'empresa' => $empresa,
            ]);
    }

}

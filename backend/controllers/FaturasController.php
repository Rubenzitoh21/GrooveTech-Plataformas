<?php

namespace backend\controllers;

use backend\models\Empresas;
use common\models\Faturas;
use backend\models\FaturasSearch;
use common\models\LinhasFaturas;
use Yii;
use yii\filters\AccessControl;
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
                            'actions' => ['index', 'view', 'delete', 'update', 'print'],
                            'roles' => ['gestor'],
                        ],
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

        $searchModel = new FaturasSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
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

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Faturas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if (!Yii::$app->user->can('editarFaturas')) {
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
     * Deletes an existing Faturas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('editarFaturas')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $fatura = $this->findModel($id);

        $fatura->status = 'Anulada';
        $fatura->save();

//        LinhasFaturas::deleteAll(['faturas_id' => $id]);
//        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Faturas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Faturas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faturas::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrint($id)
    {
        if (!Yii::$app->user->can('verFaturas')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

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

<?php

namespace backend\controllers;

use common\models\Avaliacoes;
use common\models\AvaliacoesSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AvaliacoesController implements the CRUD actions for Avaliacoes model.
 */
class AvaliacoesController extends Controller
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
                    'only' => ['index', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['index', 'delete'],
                            'allow' => true,
                            'roles' => ['gestor'],
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

    /**
     * Lists all Avaliacoes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('verAvaliacoes')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $searchModel = new AvaliacoesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Deletes an existing Avaliacoes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('apagarAvaliacoes')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Avaliacoes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Avaliacoes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Avaliacoes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}

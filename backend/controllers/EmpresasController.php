<?php

namespace backend\controllers;

use backend\models\Empresas;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EmpresasController implements the CRUD actions for Empresas model.
 */
class EmpresasController extends Controller
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
                            'actions' => ['index', 'view', 'update'],
                            'roles' => ['admin'],
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Empresas models.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect(['view']);
    }

    /**
     * Displays a single Empresas model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        if (!Yii::$app->user->can('verEmpresa')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $empresa = $this->findModel();

        return $this->render('view', [
            'model' => $empresa,
        ]);
    }

    /**
     * Updates an existing Empresas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        if (!Yii::$app->user->can('editarEmpresa')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $empresa = $this->findModel();

        if ($this->request->isPost && $empresa->load($this->request->post())) {
            if ($empresa->save()) {
                Yii::$app->session->setFlash('success', 'Empresa atualizada com sucesso.');
                return $this->redirect(['view']);
            } else {
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao atualizar a Empresa.');
            }
        }

        return $this->render('update', [
            'model' => $empresa,
        ]);
    }

    /**
     * Finds the Empresas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Empresas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel()
    {
        $empresa = Empresas::find()->one();
        if ($empresa !== null) {
            return $empresa;
        }

        throw new NotFoundHttpException('A página solicitada não existe.');
    }
}

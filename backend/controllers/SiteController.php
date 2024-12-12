<?php

namespace backend\controllers;

use common\models\Faturas;
use common\models\LoginForm;
use common\models\User;
use common\models\UserProfile;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'error', 'logout'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ],

                    [
                        'allow' => true,
                        'actions' => ['logout', 'error'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['gestor'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('backendAccess')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $totalFaturado = Faturas::find()
            ->where(['status' => 'Pago'])
            ->sum('valortotal');

        $totalFaturas = Faturas::find()->count();

        $totalClientes = UserProfile::find()->count();


        $meses = array_fill(1, 12, 0);

        $vendasPorMes = Faturas::find()
            ->select(['COUNT(*) as quantidade', 'MONTH(data) as mes'])
            ->where(['status' => 'Pago'])
            ->groupBy(['mes'])
            ->indexBy('mes')
            ->asArray()
            ->all();

        foreach ($vendasPorMes as $mes => $venda) {
            $meses[$mes] = (int)$venda['quantidade'];
        }


        $faturado = array_fill(1, 12, 0);

        $faturas = Faturas::find()
            ->select(['MONTH(data) as mes', 'SUM(valortotal) as total'])
            ->groupBy('mes')
            ->asArray()
            ->all();

        foreach ($faturas as $fatura) {
            $faturado[(int)$fatura['mes']] = (float)$fatura['total'];
        }


        return $this->render('index', [
            'totalFaturado' => $totalFaturado ?? 0,
            'totalFaturas' => $totalFaturas ?? 0,
            'totalClientes' => $totalClientes ?? 0,
            'meses' => $meses,
            'faturado' => $faturado,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (Yii::$app->user->can('backendAccess'))
                return $this->goHome();

            else {

                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'O cliente não pode aceder a esta área!');

                return $this->refresh();
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->can('backendAccess')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        Yii::$app->user->logout();

        return $this->goHome();
    }
}

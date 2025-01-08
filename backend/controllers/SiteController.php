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
    public function actionIndex($year = null)
    {
        if (!Yii::$app->user->can('backendAccess')) {
            throw new \yii\web\ForbiddenHttpException('Acesso negado.');
        }

        $session = Yii::$app->session;

        //Notificações de novos clientes e compras
        if (!$session->has('lastCheckedUserId')) {
            $ultimoCliente = UserProfile::find()
                ->select(['id'])
                ->orderBy(['id' => SORT_DESC])
                ->scalar();
            $session->set('lastCheckedUserId', $ultimoCliente);
        }
        $lastCheckedUserId = $session->get('lastCheckedUserId');
        $novosClientes = UserProfile::find()
            ->where(['>', 'id', $lastCheckedUserId])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        if (!empty($novosClientes)) {
            $session->set('lastCheckedUserId', $novosClientes[0]->id);
        }

        if (!$session->has('lastCheckedFaturaId')) {
            $ultimaFatura = Faturas::find()
                ->select(['id'])
                ->orderBy(['id' => SORT_DESC])
                ->scalar();
            $session->set('lastCheckedFaturaId', $ultimaFatura);
        }
        $lastCheckedFaturaId = $session->get('lastCheckedFaturaId');
        $novasCompras = Faturas::find()
            ->where(['>', 'id', $lastCheckedFaturaId])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        if (!empty($novasCompras)) {
            $session->set('lastCheckedFaturaId', $novasCompras[0]->id);
        }

        // Determina o ano atual
        $currentYear = $year ?? date('Y');
        $prevYear = $currentYear - 1;
        $nextYear = $currentYear + 1;

        // Estatísticas do dashboard
        $totalFaturado = Faturas::find()
            ->where(['status' => 'Pago'])
            ->sum('valortotal');

        $totalFaturas = Faturas::find()
            ->where(['status' => 'Pago'])
            ->count();

        $totalClientes = UserProfile::find()->count();

        // Vendas por mês no ano selecionado
        $meses = array_fill(1, 12, 0);

        $vendasPorMes = Faturas::find()
            ->select(['COUNT(*) as quantidade', 'MONTH(data) as mes'])
            ->where(['status' => 'Pago'])
            ->andWhere(['YEAR(data)' => $currentYear])
            ->groupBy(['mes'])
            ->indexBy('mes')
            ->asArray()
            ->all();

        foreach ($vendasPorMes as $mes => $venda) {
            $meses[$mes] = (int)$venda['quantidade'];
        }

        // Faturado por mês no ano selecionado
        $faturado = array_fill(1, 12, 0);

        $faturas = Faturas::find()
            ->select(['MONTH(data) as mes', 'SUM(valortotal) as total'])
            ->where(['status' => 'Pago'])
            ->andWhere(['YEAR(data)' => $currentYear])
            ->groupBy('mes')
            ->asArray()
            ->all();

        foreach ($faturas as $fatura) {
            $faturado[(int)$fatura['mes']] = (float)$fatura['total'];
        }


        return $this->render('index', [
            'novasCompras' => $novasCompras,
            'novosClientes' => $novosClientes,
            'totalFaturado' => $totalFaturado ?? 0,
            'totalFaturas' => $totalFaturas ?? 0,
            'totalClientes' => $totalClientes ?? 0,
            'meses' => $meses,
            'faturado' => $faturado,
            'currentYear' => $currentYear,
            'prevYear' => $prevYear,
            'nextYear' => $nextYear,
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

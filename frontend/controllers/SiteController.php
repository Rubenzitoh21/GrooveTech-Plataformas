<?php

namespace frontend\controllers;

use backend\models\Empresas;
use common\models\CategoriasProdutos;
use common\models\Produtos;
use common\models\UserProfile;
use common\models\User;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Query;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\ForbiddenHttpException;

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
                'only' => ['index', 'logout', 'contact', 'about', 'login', 'signup', 'request-password-reset', 'reset-password', 'verify-email', 'resend-verification-email'],
                'rules' => [
                    [
                        'actions' => ['logout', 'contact'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'about', 'login', 'signup', 'request-password-reset', 'reset-password', 'verify-email', 'resend-verification-email'],
                        'allow' => true,
                        'roles' => ['?', '@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    if (Yii::$app->user->isGuest) {
                        Yii::$app->getResponse()->redirect(['site/login'])->send();
                        Yii::$app->end();
                    } else {
                        throw new ForbiddenHttpException('Acesso negado.');
                    }
                },
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
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $categoriasDestaque = CategoriasProdutos::find()
            ->where(['id' => [5, 6, 9]])
            ->all();

        $produtosAvaliados = (new Query())
            ->select([
                'produtos.id',
                'produtos.nome',
                'produtos.descricao',
                'produtos.preco',
                'AVG(avaliacoes.rating) AS avg_rating',
                'COUNT(avaliacoes.id) AS review_count',
                '(SELECT fileName FROM imagens WHERE imagens.produto_id = produtos.id LIMIT 1) AS image_file'
            ])
            ->from('produtos')
            ->innerJoin('linhas_faturas', 'linhas_faturas.produtos_id = produtos.id')
            ->innerJoin('avaliacoes', 'avaliacoes.linhas_faturas_id = linhas_faturas.id')
            ->groupBy('produtos.id')
            ->orderBy(['avg_rating' => SORT_DESC])
            ->limit(3)
            ->all();

        $produtosAvaliadosIds = array_column($produtosAvaliados, 'id');
        $quantidadeRestante = 3 - count($produtosAvaliados);

        // Adiciona produtos aleatórios, excluindo os que já estão na lista de avaliados
        if ($quantidadeRestante > 0) {
            $produtosAleatorios = (new Query())
                ->select([
                    'produtos.id',
                    'produtos.nome',
                    'produtos.descricao',
                    'produtos.preco',
                    new \yii\db\Expression('0 AS avg_rating'),
                    new \yii\db\Expression('0 AS review_count'),
                    '(SELECT fileName FROM imagens WHERE imagens.produto_id = produtos.id LIMIT 1) AS image_file'
                ])
                ->from('produtos')
                ->where(['NOT IN', 'produtos.id', $produtosAvaliadosIds])
                ->orderBy(new \yii\db\Expression('RAND()'))
                ->limit($quantidadeRestante)
                ->all();

            // Combina os produtos avaliados com os aleatórios
            $produtosDestaque = array_merge($produtosAvaliados, $produtosAleatorios);
        } else {
            $produtosDestaque = $produtosAvaliados;
        }
        return $this->render('index', [
            'categoriasDestaque' => $categoriasDestaque,
            'produtosDestaque' => $produtosDestaque,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (!Yii::$app->user->can('backendAccess'))
                return $this->goHome();

            else {

                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'Acesso negado, apenas cliente podem iniciar sessão.');

                return $this->refresh();
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Obriagdo por nos contactar. Responderemos o mais breve possível.');
            } else {
                Yii::$app->session->setFlash('error', 'Ocorreu um erro ao enviar a sua mensagem.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Conta criada com sucesso. Obrigado pelo seu registo!');
            return $this->render('login', [
                'model' => new LoginForm(),
            ]);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Verifique o seu email para obter mais instruções.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Desculpe, não conseguimos repor a sua palavra-passe para o email fornecido.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Nova palavra-passe guardada.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}

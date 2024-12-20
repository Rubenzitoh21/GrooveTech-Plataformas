<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use yii\helpers\Url;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }
    
    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/login'));
    }

    public function loginVazio(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->amGoingTo('Login sem credenciais');
        $I->fillField('LoginForm[username]', '');
        $I->fillField('LoginForm[password]', '');
        $I->click('Login');
        $I->see('Este campo não pode ficar em branco.');
    }

    public function credenciaisVazias(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->amGoingTo('Login com credenciais erradas');
        $I->fillField('LoginForm[username]', 'adminn');
        $I->fillField('LoginForm[password]', 'admin321');
        $I->click('Login');
        $I->see('Nome de utilizador ou palavra-passe incorretos.');
    }

    public function validarLoginAdmin(FunctionalTester $I)
    {
        $I->fillField('LoginForm[username]', 'admin');
        $I->seeInField('LoginForm[username]', 'admin');
        $I->fillField('LoginForm[password]', 'admin1234');
        $I->seeInField('LoginForm[password]', 'admin1234');
        $I->click('#login-form button[type=submit]');

        $I->see('Início');
        $I->dontSeeLink('#login-form button[type=submit]');
    }
}

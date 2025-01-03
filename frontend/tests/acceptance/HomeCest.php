<?php

namespace frontend\tests\acceptance;

use frontend\tests\AcceptanceTester;
use yii\bootstrap4\Button;
use yii\helpers\Url;

class HomeCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/groovetech/frontend/web/site/index');
    }

    public function createAccountAndPurchase(AcceptanceTester $I)
    {
        $I->wantTo('Criar uma conta, iniciar sessão, editar dados no perfil e realizar uma compra');

        $I->see('Início');
//        $I->wait(2);
        $I->click('i.fa-user');
        $I->see('Iniciar Sessão');
//        $I->wait(1);

//        //criar conta
//        $I->click('Criar Conta');
//        $I->see('Criar Conta');
//        $I->wait(3);
//        $I->fillField('input[name="SignupForm[username]"]', 'testuser');
//        $I->wait(0.5);
//        $I->fillField('input[name="SignupForm[primeironome]"]', 'Test');
//        $I->wait(0.5);
//        $I->fillField('input[name="SignupForm[apelido]"]', 'User');
//        $I->wait(0.5);
//        $I->click('#datepicker');
//        $I->waitForElementVisible('.datepicker', 5);
//        $I->click('.datepicker-days .day:nth-child(1)');
//        $I->wait(0.5);
//        $I->fillField('input[name="SignupForm[email]"]', 'testuser@example.com');
//        $I->wait(0.5);
//        $I->fillField('input[name="SignupForm[password]"]', 'password123');
//        $I->wait(0.5);
//        $I->selectOption('select[name="SignupForm[genero]"]', 'Masculino');
//        $I->wait(0.1);
//        $I->click('button[type="submit"][name="signup-button"]');
//        $I->see('Conta criada com sucesso');
//        $I->wait(3);

        // login
        $I->fillField('LoginForm[username]', 'testuser');
        $I->wait(0.5);
        $I->fillField('LoginForm[password]', 'password123');
        $I->wait(0.5);
        $I->click('button[type="submit"][name="login-button"]');
        $I->see('Início');
//        $I->wait(2);

        // Editar Perfil
//        $I->click('i.fa-user');
//        $I->seeInCurrentUrl('/perfil/index');
//        $I->wait(3);
//        $I->see('Dados Pessoais');
//        $I->click('Editar Dados');
//
//        $I->fillField('Telemóvel', '912345678');
//        $I->fillField('NIF', '123456789');
//
//        $I->wait(2);
//        $I->waitForElementVisible('button[name="update-data-button"]', 5);
//        $I->waitForElementClickable('button[name="update-data-button"]', 5);
//        $I->scrollTo('button[name="update-data-button"]');
//        $I->executeJS('document.querySelector("button[name=\'update-data-button\']").click();');
//        $I->wait(1);
//
//        $I->see('Dados pessoais atualizados com sucesso!');
//        $I->wait(2);
//
//
//        $I->see('Dados de Morada');
//        $I->waitForElementVisible('a[href="/editar-morada"]', 5);  // Ajusta o seletor, se necessário
//        $I->click('a[href="/editar-morada"]'); // Clica no link
//
//        $I->fillField('Morada', 'Rua do Teste');
//        $I->fillField('Localidade', 'Teste');
//        $I->fillField('Código Postal', '1234-567');
//
//        $I->wait(2);
//        $I->waitForElementVisible('button[name="update-morada-button"]', 5);
//        $I->waitForElementClickable('button[name="update-morada-button"]', 5);
//        $I->scrollTo('button[name="update-morada-button"]');
//        $I->executeJS('document.querySelector("button[name=\'update-morada-button\']").click();');
//        $I->wait(1);
//
//        $I->see('Dados de morada atualizados com sucesso!');
//        $I->wait(2);

        $I->click('Produtos');
        $I->seeInCurrentUrl('/produtos/index');
        $I->wait(2);

        $I->waitForElementVisible('a.h3.text-decoration-none.text-dark', 5);
        $I->scrollTo('a.h3.text-decoration-none.text-dark');
        $I->executeJS('document.querySelector("a.h3.text-decoration-none.text-dark").click();');
        $I->seeInCurrentUrl("/produtos/view?id=");

        $I->wait(2);

        $I->waitForElementVisible('a.btn.btn-success.btn-lg.w-100', 5);
        $I->scrollTo('a.btn.btn-success.btn-lg.w-100');
        $I->executeJS('document.querySelector("a.btn.btn-success.btn-lg.w-100").click();');

        $I->wait(2);
//
//        // Adicionar ao Carrinho
//        $I->click('Adicionar ao Carrinho');
//        $I->seeInCurrentUrl('/carrinho/index');
//        $I->see('Produto Teste');
//        $I->see('Quantidade: 1');
//
//        // Aumentar Quantidade no Carrinho
//        $I->fillField('Quantidade', '2');
//        $I->click('Atualizar Carrinho');
//        $I->see('Quantidade: 2');
//        $I->see('Total: 21.98');
//
//        // Realizar Checkout
//        $I->click('Checkout');
//        $I->seeInCurrentUrl('/checkout/index');
//        $I->see('Escolher Método de Pagamento');
//        $I->selectOption('Checkout[payment_method]', 'Cartão de Crédito');
//        $I->selectOption('Checkout[shipping_method]', 'Expresso');
//        $I->click('Finalizar Compra');
//
//        // Verificar Conclusão
//        $I->see('Obrigado pela tua compra!');
//        $I->seeInCurrentUrl('/checkout/success');
    }
}

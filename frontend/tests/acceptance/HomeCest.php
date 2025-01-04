<?php

namespace frontend\tests\acceptance;

use frontend\tests\AcceptanceTester;

class HomeCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/groovetech/frontend/web/site/index');
    }

    public function createAccountAndPurchase(AcceptanceTester $I)
    {
        $I->wantTo('Criar uma conta, iniciar sessão, editar dados no perfil e realizar uma compra');

        $userData = include(__DIR__ . '/../_data/user_data.php');

        // Criar conta
        $I->see('Início');
        $I->wait(2);
        $I->click('i.fa-user');
        $I->see('Iniciar Sessão');
        $I->wait(1);

        $I->click('Criar Conta');
        $I->see('Criar Conta');
        $I->wait(3);
        $I->fillField('input[name="SignupForm[username]"]', $userData['username']);
        $I->wait(0.5);
        $I->fillField('input[name="SignupForm[primeironome]"]', $userData['primeironome']);
        $I->wait(0.5);
        $I->fillField('input[name="SignupForm[apelido]"]', $userData['apelido']);
        $I->wait(0.5);
        $I->click('#datepicker');
        $I->waitForElementVisible('.datepicker', 5);
        $I->click('.datepicker-days .day:nth-child(1)');
        $I->wait(0.5);
        $I->fillField('input[name="SignupForm[email]"]', $userData['email']);
        $I->wait(0.5);
        $I->fillField('input[name="SignupForm[password]"]', $userData['password']);
        $I->wait(0.5);
        $I->selectOption('select[name="SignupForm[genero]"]', $userData['genero']);
        $I->wait(0.1);
        $I->click('button[type="submit"][name="signup-button"]');
        $I->see('Conta criada com sucesso');
        $I->wait(2);

        // login
        $I->fillField('LoginForm[username]', $userData['username']);
        $I->wait(0.5);
        $I->fillField('LoginForm[password]', $userData['password']);
        $I->wait(0.5);
        $I->click('button[type="submit"][name="login-button"]');
        $I->see('Início');
        $I->wait(2);

        $I->click('Produtos');
        $I->seeInCurrentUrl('/produtos/index');
        $I->wait(2);

        // Selecionar produto
        $I->waitForElementVisible('a.h3.text-decoration-none.text-dark', 5);
        $I->scrollTo('a.h3.text-decoration-none.text-dark');
        $I->executeJS('document.querySelector("a.h3.text-decoration-none.text-dark").click();');
        $I->seeInCurrentUrl("/produtos/view?id=");
        $I->wait(2);

        // Adicionar ao carrinho
        $I->waitForElementVisible('a.btn.btn-success.btn-lg.w-100', 5);
        $I->scrollTo('a.btn.btn-success.btn-lg.w-100');
        $I->executeJS('document.querySelector("a.btn.btn-success.btn-lg.w-100").click();');
        $I->wait(2);
        $I->see('Carrinho');
        $I->seeInCurrentUrl('/carrinhos/index');

        // Aumentar quantidade
        $quantidadeAtual = (int) $I->grabValueFrom('input[name="quantidade"]');
        $I->waitForElementVisible('button.btn-plus a', 5);
        $I->scrollTo('button.btn-plus a');
        $I->click('button.btn-plus a');
        $I->wait(2);
        $novaQuantidade = (int) $I->grabValueFrom('input[name="quantidade"]');
        if ($novaQuantidade !== $quantidadeAtual + 1) {
            throw new \Exception('A quantidade não foi incrementada. Atual: ' . $quantidadeAtual . ', Nova: ' . $novaQuantidade);
        }

        // Realizar Checkout
        $I->waitForElementVisible('//a[contains(text(),"Finalizar compra")]', 5);
        $I->scrollTo('//a[contains(text(),"Finalizar compra")]');
        $I->waitForElementClickable('//a[contains(text(),"Finalizar compra")]', 5);
        $I->executeJS('document.querySelector("a.text-decoration-none[href*=\'/carrinhos/checkout\']").click();');
        $I->seeInCurrentUrl('/carrinhos/checkout');

        // Atualizar informações
        $I->waitForElementVisible('#profile-form-user-data', 5);
        $I->fillField('UserProfile[rua]', $userData['rua']);
        $I->wait(0.5);
        $I->fillField('UserProfile[codigopostal]', $userData['codigopostal']);
        $I->wait(0.5);
        $I->fillField('UserProfile[localidade]', $userData['localidade']);
        $I->wait(0.5);
        $I->fillField('UserProfile[telefone]', $userData['telefone']);
        $I->wait(0.5);
        $I->fillField('UserProfile[nif]', $userData['nif']);
        $I->wait(1);

        $I->scrollTo('#profile-form-user-data button.btn.btn-success');
        $I->waitForElementClickable('#profile-form-user-data button.btn.btn-success', 5);
        $I->executeJS("document.querySelector('#profile-form-user-data button.btn.btn-success').click();");
        $I->see('Dados atualizados com sucesso!');
        $I->wait(2);

        // Selecionar pagamento e envio
        $I->waitForElementVisible("ul.templatemo-accordion", 5);
        $I->executeJS("document.querySelector('li.pb-3:nth-child(1) a').click();");
        $I->waitForElementVisible("ul.collapse.show li input[name='Faturas[pagamentos_id]']", 5);
        $I->executeJS("document.querySelector('ul.collapse.show li:nth-child(1) input[name=\"Faturas[pagamentos_id]\"]').click();");
        $I->executeJS("document.querySelector('li.pb-3:nth-child(2) a').click();");
        $I->wait(2);
        $I->waitForElementVisible("ul.collapse.show li input[name='Faturas[expedicoes_id]']", 5);
        $I->executeJS("document.querySelector('ul.collapse.show li:nth-child(1) input[name=\"Faturas[expedicoes_id]\"]').click();");
        $I->waitForElementClickable("#profile-form-user-data button.btn.btn-success", 5);
        $I->executeJS("document.querySelector('#profile-form-user-data button.btn.btn-success').scrollIntoView();");
        $I->wait(2);

        // Finalizar encomenda
        $I->click('Encomendar');
        $I->see('Fatura');
        $I->wait(5);
    }
}

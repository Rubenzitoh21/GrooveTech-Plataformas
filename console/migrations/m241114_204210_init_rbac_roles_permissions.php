<?php

use yii\db\Migration;

/**
 * Class m241114_204210_init_rbac_roles_permissions
 */
class m241114_204210_init_rbac_roles_permissions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Aceder ao backend
        $permission_backendAccess = $auth->createPermission('backendAccess');
        $auth->add($permission_backendAccess);

        // Users
        $permission_verUsers = $auth->createPermission('verUsers');
        $auth->add($permission_verUsers);
        $permission_editarUsers = $auth->createPermission('editarUsers');
        $auth->add($permission_editarUsers);
        $permission_criarUsers = $auth->createPermission('criarUsers');
        $auth->add($permission_criarUsers);
        $permission_apagarUsers = $auth->createPermission('apagarUsers');
        $auth->add($permission_apagarUsers);

        // Produtos
        $permission_verProdutos = $auth->createPermission('verProdutos');
        $auth->add($permission_verProdutos);
        $permission_editarProdutos = $auth->createPermission('editarProdutos');
        $auth->add($permission_editarProdutos);
        $permission_criarProdutos = $auth->createPermission('criarProdutos');
        $auth->add($permission_criarProdutos);
        $permission_apagarProdutos = $auth->createPermission('apagarProdutos');
        $auth->add($permission_apagarProdutos);

        // Categorias
        $permission_verCategorias = $auth->createPermission('verCategorias');
        $auth->add($permission_verCategorias);
        $permission_editarCategorias = $auth->createPermission('editarCategorias');
        $auth->add($permission_editarCategorias);
        $permission_criarCategorias = $auth->createPermission('criarCategorias');
        $auth->add($permission_criarCategorias);
        $permission_apagarCategorias = $auth->createPermission('apagarCategorias');
        $auth->add($permission_apagarCategorias);

        // Avaliações
        $permission_verAvaliacoes = $auth->createPermission('verAvaliacoes');
        $auth->add($permission_verAvaliacoes);
        $permission_criarAvaliacoes = $auth->createPermission('criarAvaliacoes');
        $auth->add($permission_criarAvaliacoes);
        $permission_apagarAvaliacoes = $auth->createPermission('apagarAvaliacoes');
        $auth->add($permission_apagarAvaliacoes);

        // Permissão para editar faturas
        $permission_verFaturas = $auth->createPermission('verFaturas');
        $auth->add($permission_verFaturas);
        $permission_editarFaturas = $auth->createPermission('editarFaturas');
        $auth->add($permission_editarFaturas);
        $permission_anularFaturas = $auth->createPermission('anularFaturas');
        $auth->add($permission_anularFaturas);

        // Permissões para editar dados de ivas
        $permission_verIvas = $auth->createPermission('verIvas');
        $auth->add($permission_verIvas);
        $permission_editarIvas = $auth->createPermission('editarIvas');
        $auth->add($permission_editarIvas);
        $permission_criarIvas = $auth->createPermission('criarIvas');
        $auth->add($permission_criarIvas);
        $permission_apagarIvas = $auth->createPermission('apagarIvas');
        $auth->add($permission_apagarIvas);

        // Dados da empresa
        $permission_verEmpresa = $auth->createPermission('verEmpresa');
        $auth->add($permission_verEmpresa);
        $permission_editarEmpresa = $auth->createPermission('editarEmpresa');
        $auth->add($permission_editarEmpresa);

        // Dados pessoais
        $permission_editarDadosPessoais = $auth->createPermission('editarDadosPessoais');
        $auth->add($permission_editarDadosPessoais);

        // Compras
        $permission_verCompras = $auth->createPermission('verCompras');
        $auth->add($permission_verCompras);
        $permission_editarCompras = $auth->createPermission('editarCompras');
        $auth->add($permission_editarCompras);
        $permission_fazerCompras = $auth->createPermission('fazerCompras');
        $auth->add($permission_fazerCompras);


        // Role Gestor
        $role_gestor = $auth->createRole('gestor');
        $auth->add($role_gestor);

        $auth->addChild($role_gestor, $permission_backendAccess);

        $auth->addChild($role_gestor, $permission_editarDadosPessoais);

        $auth->addChild($role_gestor, $permission_verProdutos);
        $auth->addChild($role_gestor, $permission_editarProdutos);
        $auth->addChild($role_gestor, $permission_criarProdutos);
        $auth->addChild($role_gestor, $permission_apagarProdutos);

        $auth->addChild($role_gestor, $permission_verCategorias);
        $auth->addChild($role_gestor, $permission_editarCategorias);
        $auth->addChild($role_gestor, $permission_criarCategorias);
        $auth->addChild($role_gestor, $permission_apagarCategorias);

        $auth->addChild($role_gestor, $permission_verAvaliacoes);
        $auth->addChild($role_gestor, $permission_apagarAvaliacoes);

        $auth->addChild($role_gestor, $permission_verFaturas);
        $auth->addChild($role_gestor, $permission_editarFaturas);
        $auth->addChild($role_gestor, $permission_anularFaturas);

        $auth->addChild($role_gestor, $permission_verIvas);
        $auth->addChild($role_gestor, $permission_editarIvas);
        $auth->addChild($role_gestor, $permission_criarIvas);
        $auth->addChild($role_gestor, $permission_apagarIvas);


        // Role Admin
        $role_admin = $auth->createRole('admin');
        $auth->add($role_admin);

        $auth->addChild($role_admin, $role_gestor);

        $auth->addChild($role_admin, $permission_verUsers);
        $auth->addChild($role_admin, $permission_editarUsers);
        $auth->addChild($role_admin, $permission_criarUsers);
        $auth->addChild($role_admin, $permission_apagarUsers);

        $auth->addChild($role_admin, $permission_verEmpresa);
        $auth->addChild($role_admin, $permission_editarEmpresa);


        // Role Cliente
        $role_cliente = $auth->createRole('cliente');
        $auth->add($role_cliente);

        $auth->addChild($role_cliente, $permission_editarDadosPessoais);

        $auth->addChild($role_cliente, $permission_verProdutos);

        $auth->addChild($role_cliente, $permission_verCompras);
        $auth->addChild($role_cliente, $permission_editarCompras);
        $auth->addChild($role_cliente, $permission_fazerCompras);

        $auth->addChild($role_cliente, $permission_verFaturas);

        $auth->addChild($role_cliente, $permission_verAvaliacoes);
        $auth->addChild($role_cliente, $permission_criarAvaliacoes);
        $auth->addChild($role_cliente, $permission_apagarAvaliacoes);



        // Atribuição das roles aos users
        $auth->assign($role_admin, 1);
        $auth->assign($role_gestor, 2);
        $auth->assign($role_cliente, 3);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}

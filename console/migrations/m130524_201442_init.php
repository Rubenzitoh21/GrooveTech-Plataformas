<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
  /*  ----/PERMISSÕES DAS ROLES /----*/
    /*
    ADMIN
    Gestão do back-office:
•	Ver, editar, criar e apagar utilizadores
•	Ver, editar, criar e apagar produtos
•	Ver, editar, criar e apagar categorias
•	Ver, apagar avaliações
•	Ver, editar, criar e apagar encomendas
•	Ver, editar (editar o estado da fatura)
•	Ver, editar, criar e apagar ivas
•	Ver, editar, criar empresa

    GESTOR
    Gestão do back-office:
•	Ver, editar, criar e apagar produtos
•	Ver, editar, criar e apagar categorias
•	Ver, editar faturas
•	Ver, apagar avaliações
•	Ver, editar encomendas

    CLIENTE
    Gestão do front-office:
•	Fazer encomendas
•	Editar os dados pessoais
•	Consultar faturas
•	Fazer avaliações*/

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // https://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if ($this->db->schema->getTableSchema('{{%user}}', true) === null) {
            $this->createTable('{{%user}}', [
                'id' => $this->primaryKey(),
                'username' => $this->string()->notNull()->unique(),
                'auth_key' => $this->string(32)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'password_reset_token' => $this->string()->unique(),
                'email' => $this->string()->notNull()->unique(),

                'status' => $this->smallInteger()->notNull()->defaultValue(10),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull(),
            ], $tableOptions);
        } else {
            echo "Tabela '{{%user}}' já existe. Nenhuma ação foi tomada.\n";
        }
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}

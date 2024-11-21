<?php

use \yii\db\Migration;

class m190124_110200_add_verification_token_column_to_user_table extends Migration
{
    public function up()
    {
        $table = '{{%user}}';
        $column = 'verification_token';

        if ($this->db->schema->getTableSchema($table, true)->getColumn($column) === null) {
            $this->addColumn($table, $column, $this->string()->defaultValue(null));
        } else {
            echo "A coluna '{$column}' já existe na tabela '{$table}'. Nenhuma ação foi tomada.\n";
        }
    }

    public function down()
    {
        $this->dropColumn('{{%user}}', 'verification_token');
    }
}

<?php

use yii\db\Migration;

class m171015_094344_add_test_quotes_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('test_quote', [
            'id' => $this->primaryKey(),
            "text" => $this->text()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('test_quote');
    }

}

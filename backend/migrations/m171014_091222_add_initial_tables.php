<?php

use yii\db\Migration;

class m171014_091222_add_initial_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('emotion', [
            'id' => $this->primaryKey(),
            "name" => $this->string()
        ]);

        $this->createTable('quote', [
            'id' => $this->primaryKey(),
            "text" => $this->text(),
            "emotion_id" => $this->integer()
        ]);
        $this->addForeignKey("FK_quote-emotion_id", 'quote', 'emotion_id', 'emotion', 'id', 'CASCADE', 'CASCADE');

        /**
         * Initial data
         */
        $this->batchInsert('emotion', ['id', 'name'],[
            [1, 'angry'],
            [2, 'disgust'],
            [3, 'fear'],
            [4, 'happy'],
            [5, 'sad'],
            [6, 'surprise'],
            [7, 'neutral']
        ]);

    }

    public function safeDown()
    {
        $this->dropTable('quote');
        $this->dropTable('emotion');
    }

}

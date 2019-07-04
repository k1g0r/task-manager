<?php

use yii\db\Migration;

/**
 * Class m190704_135510_add_clients_table
 */
class m190704_135510_add_clients_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%clients}}', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer(),
            'name' => $this->string()->comment("Имя"),
            'phone' => $this->string(),
            'otherContact' => $this->text(),
            'note' => $this->string()->comment("заметка"),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'status' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%clients}}');
    }

}

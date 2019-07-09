<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%passwords}}`.
 */
class m190705_115837_create_passwords_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%passwords}}', [
            'id' => $this->primaryKey(),
            'label' => $this->string(),
            'decs' => $this->string(),
            'login' => $this->string(),
            'password' => $this->string(),
            'url' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%passwords}}');
    }
}

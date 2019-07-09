<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project_password}}`.
 */
class m190705_115855_create_project_password_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project_password}}', [
            'project_id' => $this->integer(),
            'password_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project_password}}');
    }
}

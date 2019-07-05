<?php

use yii\db\Migration;

/**
 * Class m190704_142505_add_projects_table
 */
class m190704_142505_add_projects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%projects}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer(),
            'name' => $this->string(),
            'note' => $this->text()->comment("заметка"),
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
        $this->dropTable('{{%projects}}');
    }

}

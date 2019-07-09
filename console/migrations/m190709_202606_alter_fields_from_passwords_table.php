<?php

use yii\db\Migration;

/**
 * Class m190709_202606_alter_fields_from_passwords_table
 */
class m190709_202606_alter_fields_from_passwords_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('passwords', 'decs', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('passwords', 'decs', $this->string());
    }
}

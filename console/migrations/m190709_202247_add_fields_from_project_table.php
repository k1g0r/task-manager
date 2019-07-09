<?php

use yii\db\Migration;

/**
 * Class m190709_202247_add_fields_from_project_table
 */
class m190709_202247_add_fields_from_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('projects', 'hourPrice', $this->integer()->comment("Стоимость часа работ"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('projects', 'hourPrice');
    }

}

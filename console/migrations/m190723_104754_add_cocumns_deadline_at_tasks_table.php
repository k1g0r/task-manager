<?php

use yii\db\Migration;

/**
 * Class m190723_104754_add_cocumns_deadline_at_tasks_table
 */
class m190723_104754_add_cocumns_deadline_at_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'deadline_at', $this->integer()->comment('крайний срок'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tasks', 'deadline_at');
    }

}

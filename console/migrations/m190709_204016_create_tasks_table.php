<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m190709_204016_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->comment("Проект"),
            'parent_id' => $this->integer()->comment("Родительская задача"),
            'name' => $this->string(),
            'taskText' => $this->text()->comment("Текст задачи"),
            'resultText' => $this->text()->comment("Что сделано"),
            'hoursPrice' => $this->integer()->defaultValue(0)->comment("Стоимость часа работы"),
            'time' => $this->integer()->defaultValue(0)->comment("Сколько времени потрачено (минуты)"),
            'total' => $this->integer()->defaultValue(0)->comment("Итоговая сумма за задачу"),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'waiting_at' => $this->integer()->comment("Отправили на оплату"),
            'payment_at' => $this->integer()->comment("Оплачено"),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m240628_031527_alter_roll_add_is_fill_testing
 */
class m240628_031527_alter_roll_add_is_fill_testing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%certificates.roll}}', 'is_fill_testing', $this->boolean()->defaultValue(false)->comment('Заполнены результаты тестирования'));
        $this->update('{{%certificates.roll}}', ['is_fill_testing' => true]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%certificates.roll}}', 'is_fill_testing');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m240704_113727_alter_standard_add_prefix
 */
class m240704_113727_alter_standard_add_prefix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%references.standard}}', 'prefix', $this->string()->comment('Префикс'));
        $this->update('{{%references.standard}}', ['prefix' => 'CT']);
        $this->update('{{%references.standard}}', ['prefix' => 'RT'], ['id' => 1]);
        $this->alterColumn('{{%references.standard}}', 'prefix', 'set not null');

        $this->alterColumn('{{%certificates.cylinder}}', 'weight', $this->decimal(12, 3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%references.standard}}', 'prefix');
        $this->alterColumn('{{%certificates.cylinder}}', 'weight', $this->decimal(10, 2));
    }

}

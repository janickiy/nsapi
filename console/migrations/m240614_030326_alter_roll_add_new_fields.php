<?php

use yii\db\Migration;

/**
 * Class m240614_030326_alter_roll_add_new_fields
 */
class m240614_030326_alter_roll_add_new_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%certificates.roll}}', 'absorbed_energy_1', $this->decimal(10, 2)->comment('Поглощенная энергия образец 1'));
        $this->addColumn('{{%certificates.roll}}', 'absorbed_energy_2', $this->decimal(10, 2)->comment('Поглощенная энергия образец 2'));
        $this->addColumn('{{%certificates.roll}}', 'absorbed_energy_3', $this->decimal(10, 2)->comment('Поглощенная энергия образец 3'));
        $this->addColumn('{{%certificates.roll}}', 'fluidity', $this->decimal(10, 2)->comment('Предел текучести'));
        $this->addColumn('{{%certificates.roll}}', 'fluidity_note', $this->string()->comment('Предел текучести сноска'));
        $this->addColumn('{{%certificates.roll}}', 'strength', $this->decimal(10, 2)->comment('Предел прочности'));
        $this->addColumn('{{%certificates.roll}}', 'relative_extension', $this->decimal(10, 2)->comment('Относительное удлинение'));
        $this->addColumn('{{%certificates.roll}}', 'relative_extension_note', $this->string()->comment('Относительное удлинение сноска'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%certificates.roll}}', 'absorbed_energy_1');
        $this->dropColumn('{{%certificates.roll}}', 'absorbed_energy_2');
        $this->dropColumn('{{%certificates.roll}}', 'absorbed_energy_3');
        $this->dropColumn('{{%certificates.roll}}', 'fluidity');
        $this->dropColumn('{{%certificates.roll}}', 'fluidity_note');
        $this->dropColumn('{{%certificates.roll}}', 'strength');
        $this->dropColumn('{{%certificates.roll}}', 'relative_extension');
        $this->dropColumn('{{%certificates.roll}}', 'relative_extension_note');
    }
}

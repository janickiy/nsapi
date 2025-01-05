<?php

use yii\db\Migration;

/**
 * Class m240711_050837_alter_standard_add_show_absorbed_energy
 */
class m240711_050837_alter_standard_add_show_absorbed_energy extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%references.standard}}', 'is_show_absorbed_energy', $this->boolean()->notNull()->defaultValue(true));
        $this->update('{{%references.standard}}', ['is_show_absorbed_energy' => false], ['id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%references.standard}}', 'is_show_absorbed_energy');
    }
}

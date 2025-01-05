<?php

use yii\db\Migration;

/**
 * Class m240629_055537_alter_roll_add_is_exact
 */
class m240629_055537_alter_roll_add_is_exact extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%certificates.roll}}', 'is_exact_hardness_om', $this->boolean()->notNull()->defaultValue(false)->comment('Точное или нет значение твердости ОМ'));
        $this->addColumn('{{%certificates.roll}}', 'is_exact_hardness_ssh', $this->boolean()->notNull()->defaultValue(false)->comment('Точное или нет значение твердости СШ'));
        $this->addColumn('{{%certificates.roll}}', 'is_exact_hardness_ztv', $this->boolean()->notNull()->defaultValue(false)->comment('Точное или нет значение твердости ЗТВ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%certificates.roll}}', 'is_exact_hardness_om');
        $this->dropColumn('{{%certificates.roll}}', 'is_exact_hardness_ssh');
        $this->dropColumn('{{%certificates.roll}}', 'is_exact_hardness_ztv');
    }
}

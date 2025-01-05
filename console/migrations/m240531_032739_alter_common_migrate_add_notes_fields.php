<?php

use yii\db\Migration;

/**
 * Class m240531_032739_alter_common_migrate_add_notes_fields
 */
class m240531_032739_alter_common_migrate_add_notes_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%certificates.certificate}}', 'brand_geophysical_cable_note', $this->string()->comment('Сноска для типа марки геофизического кабеля'));
        $this->addColumn('{{%certificates.certificate}}', 'length_geophysical_cable_note', $this->string()->comment('Сноска для длины геофизического кабеля'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%certificates.certificate}}', 'brand_geophysical_cable_note');
        $this->dropColumn('{{%certificates.certificate}}', 'length_geophysical_cable_note');
    }
}

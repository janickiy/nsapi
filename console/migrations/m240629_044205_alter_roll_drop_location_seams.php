<?php

use yii\db\Migration;

/**
 * Class m240629_044205_alter_roll_drop_location_seams
 */
class m240629_044205_alter_roll_drop_location_seams extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%certificates.roll}}', 'location_seams');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%certificates.roll}}', 'location_seams', $this->decimal(10, 2)->comment('Расположения поперечных швов от начала, м'),);
    }
}

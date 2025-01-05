<?php

use yii\db\Migration;

/**
 * Class m240619_075933_alter_certificate_rfid
 */
class m240619_075933_alter_certificate_rfid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%certificates.certificate}}', 'rfid', 'drop not null');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%certificates.certificate}}', 'rfid', 'set not null');
    }
}

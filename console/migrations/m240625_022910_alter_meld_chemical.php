<?php

use yii\db\Migration;

/**
 * Class m240625_022910_alter_meld_chemical
 */
class m240625_022910_alter_meld_chemical extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%certificates.meld}}', 'chemical_c', $this->decimal(10, 4));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_mn', $this->decimal(10, 4));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_si', $this->decimal(10, 4));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_s', $this->decimal(10, 4));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_p', $this->decimal(10, 4));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%certificates.meld}}', 'chemical_c', $this->decimal(10, 2));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_mn', $this->decimal(10, 2));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_si', $this->decimal(10, 2));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_s', $this->decimal(10, 2));
        $this->alterColumn('{{%certificates.meld}}', 'chemical_p', $this->decimal(10, 2));
    }
}

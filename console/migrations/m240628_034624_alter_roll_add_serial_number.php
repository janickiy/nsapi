<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m240628_034624_alter_roll_add_serial_number
 */
class m240628_034624_alter_roll_add_serial_number extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%certificates.roll}}', 'serial_number', $this->integer()->notNull()->defaultValue(0)->comment('Порядковый номер'));
        $certs = (new Query())->select('id')->from('{{%certificates.certificate}}')->column();
        foreach ($certs as $cert) {
            $numberRoll = 1;
            $melds = (new Query())->select('id')
                ->from('{{%certificates.meld}}')
                ->where(['certificate_id' => $cert])
                ->orderBy(['id' => SORT_ASC])
                ->column();
            foreach ($melds as $meld) {
                $rolls = (new Query())->select('id')
                    ->from('{{%certificates.roll}}')
                    ->where(['meld_id' => $meld])
                    ->orderBy(['id' => SORT_ASC])
                    ->column();
                foreach ($rolls as $roll) {
                    $this->update('{{certificates.roll}}', ['serial_number' => $numberRoll], ['id' => $roll]);
                    $numberRoll++;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%certificates.roll}}', 'serial_number');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m240603_085307_add_notes
 */
class m240603_085307_add_notes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificates.note}}', [
            'id' => $this->primaryKey(),
            'certificate_id' => $this->integer()->notNull()->comment('Идентификатор сертификата'),
            'text' => $this->string()->comment('Текст примечания')
        ]);
        $this->addCommentOnTable('{{%certificates.note}}', 'Примечания');
        $this->addForeignKey(
            'FK_Certificates_Note_CertificateId__Certificates_Certificate_Id',
            '{{%certificates.note}}',
            'certificate_id',
            '{{%certificates.certificate}}',
            'id', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%certificates.note}}');
    }
}

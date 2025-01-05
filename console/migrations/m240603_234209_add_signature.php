<?php

use yii\db\Migration;

/**
 * Class m240603_234209_add_signature
 */
class m240603_234209_add_signature extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificates.signature}}', [
            'id' => $this->primaryKey(),
            'certificate_id' => $this->integer()->notNull()->comment('Идентификатор сертификата'),
            'name' => $this->string()->comment('ФИО'),
            'position' => $this->string()->comment('Должность'),
        ]);
        $this->addCommentOnTable('{{%certificates.signature}}', 'Подписи');
        $this->addForeignKey(
            'FK_Certificates_Signature_CertificateId__Certificates_Certificate_Id',
            '{{%certificates.signature}}',
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
        $this->dropTable('{{%certificates.signature}}');
    }
}

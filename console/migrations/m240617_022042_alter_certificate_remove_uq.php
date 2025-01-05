<?php

use yii\db\Migration;

/**
 * Class m240617_022042_alter_certificate_remove_uq
 */
class m240617_022042_alter_certificate_remove_uq extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex("UQ_CertificatesCertificate_Number", "certificates.certificate");
        $this->dropIndex("UQ_CertificatesCertificate_NumberTube", "certificates.certificate");
        $this->dropIndex("UQ_CertificatesCertificate_Rfid", "certificates.certificate");

        $this->createIndex(
            "UQ_CertificatesCertificate_Number",
            "certificates.certificate",
            "number",
            true)
        ;
        $this->createIndex(
            "UQ_CertificatesCertificate_NumberTube",
            "certificates.certificate",
            "number_tube",
            true)
        ;
        $this->createIndex(
            "UQ_CertificatesCertificate_Rfid",
            "certificates.certificate",
            "rfid",
            true)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex("UQ_CertificatesCertificate_Number", "certificates.certificate");
        $this->dropIndex("UQ_CertificatesCertificate_NumberTube", "certificates.certificate");
        $this->dropIndex("UQ_CertificatesCertificate_Rfid", "certificates.certificate");

        $this->execute('CREATE UNIQUE INDEX "UQ_CertificatesCertificate_Number" ON certificates.certificate("number")' . " WHERE status_id <> 'deleted'");
        $this->execute('CREATE UNIQUE INDEX "UQ_CertificatesCertificate_NumberTube" ON certificates.certificate("number_tube")' . " WHERE status_id <> 'deleted'");
        $this->execute('CREATE UNIQUE INDEX "UQ_CertificatesCertificate_Rfid" ON certificates.certificate("rfid")' . " WHERE status_id <> 'deleted'");
    }
}

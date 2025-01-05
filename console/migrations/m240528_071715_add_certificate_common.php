<?php

use yii\db\Migration;

/**
 * Class m240528_071715_add_certificate_common
 */
class m240528_071715_add_certificate_common extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE SCHEMA IF NOT EXISTS {{%certificates}}");

        $this->createTable('{{%certificates.status}}', [
            'id' => $this->string(36)->notNull()->unique(),
            'name' => $this->string()->notNull()->unique()->comment('Название'),
        ]);
        $this->addCommentOnTable('{{%certificates.status}}', 'Статусы сертификата');
        $this->batchInsert('{{%certificates.status}}', ['id', 'name'], [
            ['draft', 'Черновик'],
            ['approve', 'На согласовании'],
            ['published', 'Опубликован'],
            ['refunded', 'Возвращен на доработку'],
            ['deleted', 'Удален'],
        ]);
        $this->addPrimaryKey('PK_Certificates_Id', '{{%certificates.status}}', 'id');

        $this->createTable('{{%certificates.certificate}}', [
            'id' => $this->primaryKey(),
            'status_id' => $this->string()->notNull()->comment('Идентификатор статуса'),
            'number' => $this->string()->notNull()->comment('Номер документа'),
            'number_tube' => $this->string()->notNull()->comment('Номер трубы'),
            'rfid' => $this->string()->notNull()->comment('RFID метка'),
            'product_type' => $this->string()->comment('Вид изделия'),
            'standard_id' => $this->integer()->comment('Идентификатор стандарта'),
            'hardness_id' => $this->integer()->comment('Идентификатор группы прочности'),
            'outer_diameter_id' => $this->integer()->comment('Идентификатор внешнего диаметра'),
            'agreement_delivery' => $this->string()->comment('Договор поставки'),
            'type_heat_treatment' => $this->string()->comment('Вид термической обработки'),
            'type_quick_connection' => $this->string()->comment('Тип быстросъемного соединения'),
            'type_quick_connection_note' => $this->string()->comment('Сноска для типа быстросъемного соединения'),
            'result_hydrostatic_test' => $this->string()->comment('Результат гидростатических испытаний'),
            'brand_geophysical_cable' => $this->string()->comment('Марка геофизического кабеля'),
            'length_geophysical_cable' => $this->decimal(10, 2)->comment('Длина геофизического кабеля'),
            'customer' => $this->string()->comment('Покупатель'),
            'created_at' => 'timestamp with time zone NOT NULL DEFAULT NOW()',
        ]);
        $this->addCommentOnTable('{{%certificates.certificate}}', 'Сертификаты');
        $this->addForeignKey(
            'FK_CertificatesCertificate_StatusId__CertificatesStatus_Id',
            '{{%certificates.certificate}}',
            'status_id',
            '{{%certificates.status}}',
            'id'
        );
        $this->execute('CREATE UNIQUE INDEX "UQ_CertificatesCertificate_Number" ON certificates.certificate("number")' . " WHERE status_id <> 'deleted'");
        $this->execute('CREATE UNIQUE INDEX "UQ_CertificatesCertificate_NumberTube" ON certificates.certificate("number_tube")' . " WHERE status_id <> 'deleted'");
        $this->execute('CREATE UNIQUE INDEX "UQ_CertificatesCertificate_Rfid" ON certificates.certificate("rfid")' . " WHERE status_id <> 'deleted'");
        $this->addForeignKey(
            'FK_CertificatesCertificate_StandardId__ReferencesStandard_Id',
            '{{%certificates.certificate}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_CertificatesCertificate_HardnessId__ReferencesHardness_Id',
            '{{%certificates.certificate}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->addForeignKey(
            'FK_CertificatesCertificate_OuterDiameterId__ReferencesOuterDiameter_Id',
            '{{%certificates.certificate}}',
            'outer_diameter_id',
            '{{%references.outer_diameter}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%certificates.certificate}}');
        $this->dropTable('{{%certificates.status}}');
        $this->execute("DROP SCHEMA IF EXISTS {{%certificates}};");
    }
}

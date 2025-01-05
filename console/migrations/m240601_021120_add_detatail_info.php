<?php

use yii\db\Migration;

/**
 * Class m240601_021120_add_detatail_info
 */
class m240601_021120_add_detatail_info extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%certificates.certificate}}', 'rolls_note', $this->string()->comment('Сноска для рулонов'));

        $this->createTable('{{%certificates.meld}}', [
            'id' => $this->primaryKey(),
            'certificate_id' => $this->integer()->notNull()->comment('Идентификатор сертификата'),
            'number' => $this->string()->notNull()->comment('Номер плавки'),
            'sekv' => $this->decimal(10, 2)->comment('СЭКВ, %'),
            'sekv_note' => $this->string()->comment('Сноска для СЭКВ'),
            'chemical_c' => $this->decimal(10, 2)->comment('Химический состав углерод C, %'),
            'chemical_mn' => $this->decimal(10, 2)->comment('Химический состав марганец Mn, %'),
            'chemical_si' => $this->decimal(10, 2)->comment('Химический состав кремний Si, %'),
            'chemical_s' => $this->decimal(10, 2)->comment('Химический состав сера S, %'),
            'chemical_p' => $this->decimal(10, 2)->comment('Химический состав фосфор P, %'),
            'dirty_type_a' => $this->decimal(10, 2)->comment('Степень загрязненности Тип A'),
            'dirty_type_b' => $this->decimal(10, 2)->comment('Степень загрязненности Тип B'),
            'dirty_type_c' => $this->decimal(10, 2)->comment('Степень загрязненности Тип C'),
            'dirty_type_d' => $this->decimal(10, 2)->comment('Степень загрязненности Тип D'),
            'dirty_type_ds' => $this->decimal(10, 2)->comment('Степень загрязненности Тип DS'),
        ]);
        $this->addCommentOnTable('{{%certificates.meld}}', 'Плавки');
        $this->addForeignKey(
            'FK_Certificates_Meld_CertificateId__Certificates_Certificate_Id',
            '{{%certificates.meld}}',
            'certificate_id',
            '{{%certificates.certificate}}',
            'id', 'CASCADE'
        );
        $this->createIndex(
            'UK_Certificates_Meld__CertificateId_Number',
            '{{%certificates.meld}}',
            ['certificate_id', 'number'],
            true
        );

        $this->createTable('{{%references.wall_thickness}}',[
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Название'),
            'value' => $this->decimal(10, 2)->notNull()->unique()->comment('Значение')
        ]);
        $this->addCommentOnTable('{{%references.wall_thickness}}', 'Толщина стенки');
        $this->batchInsert('{{%references.wall_thickness}}', ['name', 'value'], [
            ['1.9', 1.9], ['2', 2], ['2.1', 2.1], ['2.2', 2.2], ['2.4', 2.4], ['2.6', 2.6], ['2.8', 2.8],
            ['3', 3], ['3.2', 3.2], ['3.4', 3.4], ['3.7', 3.7], ['4', 4], ['4.4', 4.4], ['4.8', 4.8],
            ['5.2', 5.2], ['5.7', 5.7], ['6.4', 6.4], ['7', 7], ['7.1', 7.1], ['7.6', 7.6]
        ]);

        $this->createTable('{{certificates.roll}}', [
            'id' => $this->primaryKey(),
            'meld_id' => $this->integer()->notNull()->comment('Идентификатор плавки'),
            'number' => $this->string()->notNull()->comment('Номер плавки'),
            'wall_thickness_id' => $this->integer()->comment('Идентификатор толщины стенки'),
            'length' => $this->decimal(10, 2)->comment('Длина рулона, м'),
            'location_seams' => $this->decimal(10, 2)->comment('Расположения поперечных швов от начала, м'),
            'location_seams_note' => $this->string()->comment('Сноска для расположения швов'),
            'grain_size' => $this->decimal(10, 2)->comment('Размер зерна'),
            'hardness_note' => $this->string()->comment('Сноска для твердости'),
            'hardness_om' => $this->decimal(10, 2)->comment('Твердтость OM'),
            'hardness_ssh' => $this->decimal(10, 2)->comment('Твердтость СШ'),
            'hardness_ztv' => $this->decimal(10, 2)->comment('Твердтость ЗТВ'),
            'is_use_note' => $this->boolean()->notNull()->defaultValue(false)->comment('Использовать сноску')
        ]);
        $this->addCommentOnTable('{{%certificates.roll}}', 'Рулоны');
        $this->addForeignKey(
            'FK_Certificates_Roll_MeldId__Certificates_Meld_Id',
            '{{%certificates.roll}}',
            'meld_id',
            '{{%certificates.meld}}',
            'id', 'CASCADE'
        );
        $this->addForeignKey(
            'FK_Certificates_Roll_WallThicknessId__References_WallThickness_Id',
            '{{%certificates.roll}}',
            'wall_thickness_id',
            '{{%references.wall_thickness}}',
            'id'
        );
        $this->createIndex(
            'UK_Certificates_Roll__MeldId_Number',
            '{{%certificates.roll}}',
            ['meld_id', 'number'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{certificates.roll}}');
        $this->dropTable('{{%references.wall_thickness}}');
        $this->dropColumn('{{%certificates.certificate}}', 'rolls_note');
        $this->dropTable('{{%certificates.meld}}');
    }
}

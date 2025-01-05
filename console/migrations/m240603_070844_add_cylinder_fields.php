<?php

use yii\db\Migration;

/**
 * Class m240603_070844_add_cylinder_fields
 */
class m240603_070844_add_cylinder_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%certificates.cylinder}}', [
            'id' => $this->integer()->notNull()->unique(),
            'material' => $this->string()->comment('Материал изготовления'),
            'weight' => $this->decimal(10, 2)->comment('Масса барабана'),
            'diameter_core' => $this->decimal(10, 2)->comment('Диаметр сердечника'),
            'diameter_cheek' => $this->decimal(10, 2)->comment('Диаметр щек'),
            'width' => $this->decimal(10, 2)->comment('Ширина'),
            'mark_nitrogen' => $this->string()->comment('Отметка о заполнении азотом'),
        ]);
        $this->addCommentOnTable('{{%certificates.cylinder}}', 'Сведения о барабане');
        $this->addPrimaryKey('PK_Certificates_Cylinder__Id', '{{%certificates.cylinder}}', 'id');
        $this->addForeignKey(
            'FK_Certificates_Cylinder_Id__Certificates_Certificate_Id',
            '{{%certificates.cylinder}}',
            'id',
            '{{%certificates.certificate}}',
            'id', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%certificates.cylinder}}');
    }
}

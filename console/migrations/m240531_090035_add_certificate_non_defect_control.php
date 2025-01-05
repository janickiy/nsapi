<?php

use yii\db\Migration;

/**
 * Class m240531_090035_add_certificate_non_defect_control
 */
class m240531_090035_add_certificate_non_defect_control extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.control_result}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string()->notNull()->unique()->comment('Текст результата'),
        ]);
        $this->addCommentOnTable('{{%references.control_result}}', 'Результат контроля');
        $this->insert('{{%references.control_result}}', [
            'text' => 'дефектов не обнаружено'
        ]);

        $this->createTable('{{%references.control_method}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Название'),
        ]);
        $this->addCommentOnTable('{{%references.control_method}}', 'Методы неразрушающего контроля');

        $this->createTable('{{%references.nd_control_method}}', [
            'id' => $this->primaryKey(),
            'control_method_id' => $this->integer()->notNull()->comment('Идентификатор метода контроля'),
            'name' => $this->string()->notNull()->comment('Название НД')
        ]);
        $this->addCommentOnTable('{{%references.nd_control_method}}', 'НД на контроль');
        $this->createIndex(
            'UQ_References_NdControl__ControlMethodId_Name',
            '{{%references.nd_control_method}}',
            ['control_method_id', 'name'],
            true
        );
        $this->createIndex(
            'UQ_References_NdControl__ControlMethodId_Id',
            '{{%references.nd_control_method}}',
            ['control_method_id', 'id'],
            true
        );
        $this->addForeignKey(
            'FK_References_NdControl_ControlMethodId__References_ControlMethod_Id',
            '{{%references.nd_control_method}}',
            'control_method_id',
            '{{%references.control_method}}',
            'id'
        );
        $controlMethodData = [
            'Ультразвуковой' => 'ГОСТ Р 55724',
            'Магнитопорошковый' => 'ГОСТ Р 56512',
            'Рентгенотелевизионный' => 'ГОСТ ISO 17636-2-2017',
            'Вихретоковый контроль' => 'ГОСТ Р ИСО 15549',
            'Капиллярный контроль' => 'ГОСТ 18442',
        ];
        foreach ($controlMethodData as $method => $nd) {
            $this->insert('{{%references.control_method}}', ['name' => $method]);
            $idMethod = Yii::$app->db->getLastInsertID();
            $this->insert(
                '{{%references.nd_control_method}}',
                ['control_method_id' => $idMethod, 'name' => $nd]
            );
        }

        $this->createTable('{{%certificates.control_object}}', [
            'id' => $this->string()->notNull()->unique(),
            'name' => $this->string()->notNull()->comment('Название')
        ]);
        $this->addCommentOnTable('{{%certificates.control_object}}', 'Обекты контроля');
        $this->addPrimaryKey(
            'PK_Certificates_ControlObjectId',
            '{{%certificates.control_object}}',
            'id'
        );
        $this->batchInsert('{{%certificates.control_object}}', ['id', 'name'], [
            ['cross_seams_roll_ends', 'Поперечные швы концов рулонов'],
            ['longitudinal_seams', 'Продольные швы ГНКТ'],
            ['base_metal', 'Основной металл ГНКТ'],
            ['circular_corner_seam', 'Кольцевой угловой шов'],
        ]);

        $this->createTable('{{%certificates.non_destructive_test}}', [
            'id' => $this->primaryKey(),
            'certificate_id' => $this->integer()->notNull()->comment('Идентификатор сертификата'),
            'control_object_id' => $this->string()->notNull()->comment('Идентификатор объекта контроля'),
            'control_method_id' => $this->integer()->null()->comment('Идентификатор метода контроля'),
            'nd_control_method_id' => $this->integer()->null()->comment('Идентификатор НД на метод контроля'),
            'control_result_id' => $this->integer()->null()->comment('Идентификатор результата контроля'),
            'note' => $this->string()->null()->comment('Текст сноски')
        ]);
        $this->addCommentOnTable('{{%certificates.non_destructive_test}}', 'Неразрушающий контроль');
        $this->addForeignKey(
            'FK_Certificates_NonDestructiveTest_CertificateId__Certificates_Certificate_Id',
            '{{%certificates.non_destructive_test}}',
            'certificate_id',
            '{{%certificates.certificate}}',
            'id', 'CASCADE'
        );
        $this->addForeignKey(
            'FK_Certificates_NonDestructiveTest_ControlObjectId__Certificates_ControlObject_Id',
            '{{%certificates.non_destructive_test}}',
            'control_object_id',
            '{{%certificates.control_object}}',
            'id'
        );
        $this->addForeignKey(
            'FK_Certificates_NonDestructiveTest_ControlMethodId__References_ControlMethod_Id',
            '{{%certificates.non_destructive_test}}',
            'control_method_id',
            '{{%references.control_method}}',
            'id'
        );
        $this->addForeignKey(
            'FK_Certificates_NonDestructiveTest_NdControlMethodId__References_NdControlMethod_Id',
            '{{%certificates.non_destructive_test}}',
            'nd_control_method_id',
            '{{%references.nd_control_method}}',
            'id'
        );
        $this->addForeignKey(
            'FK_Certificates_NonDestructiveTest_ControlMethodId_NdControlMethodId__References_NdControlMethod_ControlId_Id',
            '{{%certificates.non_destructive_test}}',
            ['control_method_id', 'nd_control_method_id'],
            '{{%references.nd_control_method}}',
            ['control_method_id', 'id']
        );
        $this->addForeignKey(
            'FK_Certificates_NonDestructiveTest_ControlMethodResultId__References_ControlResult_Id',
            '{{%certificates.non_destructive_test}}',
            'control_result_id',
            '{{%references.control_result}}',
            'id'
        );

        $this->createIndex(
            'UK_Certificates_NonDestructiveTest__CertificateId_ControlObjectId_ControlMethodId',
            '{{%certificates.non_destructive_test}}',
            ['certificate_id', 'control_object_id', 'control_method_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%certificates.non_destructive_test}}');
        $this->dropTable('{{%certificates.control_object}}');
        $this->dropTable('{{%references.nd_control_method}}');
        $this->dropTable('{{%references.control_method}}');
        $this->dropTable('{{%references.control_result}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m240605_093033_chemical_elements_reference
 */
class m240605_093033_chemical_elements_reference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.mass_fraction}}', [
            'id' => $this->primaryKey(),
            'hardness_id' => $this->integer()->notNull()->unique()->comment('Идентификатор группы прочности'),
            'carbon' => $this->decimal(10, 4)->comment('Углерод C'),
            'manganese' => $this->decimal(10, 4)->comment('Марганец Mn'),
            'silicon' => $this->decimal(10, 4)->comment('Кремний Si'),
            'sulfur' => $this->decimal(10, 4)->comment('Сера S'),
            'phosphorus' => $this->decimal(10, 4)->comment('Фосфор P'),
        ]);
        $this->addCommentOnTable('{{%references.mass_fraction}}', 'Массовая доля элементов');
        $this->addForeignKey(
            'FK_ReferencesMassFraction_HardnessId__ReferencesHardness_Id',
            '{{%references.mass_fraction}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->batchInsert(
            '{{%references.mass_fraction}}',
            ['hardness_id', 'carbon', 'manganese', 'silicon', 'sulfur', 'phosphorus'],
            [
                [1, 0.16, 1.2, 0.5, 0.005, 0.025],
                [2, 0.16, 1.2, 0.5, 0.005, 0.02],
                [3, 0.16, 1.2, 0.5, 0.005, 0.02],
                [4, 0.16, 1.65, 0.5, 0.005, 0.025],
                [5, 0.16, 1.65, 0.5, 0.005, 0.025],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%references.mass_fraction}}');
    }
}

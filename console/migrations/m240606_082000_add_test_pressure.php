<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m240606_082000_add_test_pressure
 */
class m240606_082000_add_test_pressure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.test_pressure}}', [
            'id' => $this->primaryKey(),
            'standard_id' => $this->integer()->notNull()->comment('Идентификатор стандарта'),
            'hardness_id' => $this->integer()->notNull()->comment('Идентификатор группы прочности'),
            'outer_diameter_id' => $this->integer()->notNull()->comment('Идентификатор внешнего диаметра'),
            'wall_thickness_id' => $this->integer()->notNull()->comment('Идентификатор толщины стенки'),
            'value' => $this->decimal(10, 2)->comment('Значение')
        ]);
        $this->addCommentOnTable('{{%references.test_pressure}}', 'Испытательное давление');
        $this->addForeignKey(
            'FK_ReferencesTestPressure_StandardId__ReferencesStandard_Id',
            '{{%references.test_pressure}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesTestPressure_HardnessId__ReferencesHardness_Id',
            '{{%references.test_pressure}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesTestPressure_OuterDiameterId__ReferencesOuterDiameter_Id',
            '{{%references.test_pressure}}',
            'outer_diameter_id',
            '{{%references.outer_diameter}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesTestPressure_WallThicknessId__ReferencesWallThickness_Id',
            '{{%references.test_pressure}}',
            'wall_thickness_id',
            '{{%references.wall_thickness}}',
            'id'
        );
        $this->createIndex(
            'UQ_ReferencesTestPressure_StandardId_HardnessId_OuterDiameterId_WallThicknessId',
            '{{%references.test_pressure}}',
            ['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id'],
            true
        );

        $handle = fopen(__DIR__ . "/init_data/test_pressure.csv", "r");
        for($i = 0; $row = fgetcsv($handle); $i++) {
            if (!$i) {
                continue;
            }

            $standardName = trim($row[0]);
            $hardnessName = trim($row[1]);
            $diameterName = trim(str_replace(".", ",", $row[3]));
            $wallName = floatval(str_replace(',', '.', $row[4]));
            $wallName = number_format($wallName, 2, ',');
            $value = floatval(str_replace(',', '.', $row[5])) ?: null;

            $hardnessId = (new Query())
                ->select('id')
                ->from('{{%references.hardness}}')
                ->where(['name' => $hardnessName])
                ->scalar();
            $diameterId = (new Query())
                ->select('id')
                ->from('{{%references.outer_diameter}}')
                ->where(['name' => $diameterName])
                ->scalar();
            $wallId = (new Query())
                ->select('id')
                ->from('{{%references.wall_thickness}}')
                ->where(['name' => $wallName])
                ->scalar();

            try {
                $this->insert('{{%references.test_pressure}}', [
                    'standard_id' => $standardName == 'CT' ? 2 : 1,
                    'hardness_id' => $hardnessId,
                    'outer_diameter_id' => $diameterId,
                    'wall_thickness_id' => $wallId,
                    'value' => $value,
                ]);
                if ($standardName == 'CT') {
                    $this->insert('{{%references.test_pressure}}', [
                        'standard_id' => 3,
                        'hardness_id' => $hardnessId,
                        'outer_diameter_id' => $diameterId,
                        'wall_thickness_id' => $wallId,
                        'value' => $value,
                    ]);
                }
            } catch (Exception $e) {
                var_dump($e->getMessage());
                var_dump($i);
                var_dump($row);
                die();
            }
        }
        fclose($handle);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%references.test_pressure}}');
    }
}

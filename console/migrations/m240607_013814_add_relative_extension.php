<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m240607_013814_add_relative_extension
 */
class m240607_013814_add_relative_extension extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.relative_extension}}', [
            'id' => $this->primaryKey(),
            'standard_id' => $this->integer()->notNull()->comment('Идентификатор стандарта'),
            'hardness_id' => $this->integer()->notNull()->comment('Идентификатор группы прочности'),
            'outer_diameter_id' => $this->integer()->notNull()->comment('Идентификатор внешнего диаметра'),
            'wall_thickness_id' => $this->integer()->notNull()->comment('Идентификатор толщины стенки'),
            'value' => $this->decimal(10, 2)->comment('Значение')
        ]);
        $this->addCommentOnTable('{{%references.relative_extension}}', 'Относительно удлинение');
        $this->addForeignKey(
            'FK_ReferencesRelativeExtension_StandardId__ReferencesStandard_Id',
            '{{%references.relative_extension}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesRelativeExtension_HardnessId__ReferencesHardness_Id',
            '{{%references.relative_extension}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesRelativeExtension_OuterDiameterId__ReferencesOuterDiameter_Id',
            '{{%references.relative_extension}}',
            'outer_diameter_id',
            '{{%references.outer_diameter}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesRelativeExtension_WallThicknessId__ReferencesWallThickness_Id',
            '{{%references.relative_extension}}',
            'wall_thickness_id',
            '{{%references.wall_thickness}}',
            'id'
        );
        $this->createIndex(
            'UQ_ReferencesRelativeExtension_StandardId_HardnessId_OuterDiameterId_WallThicknessId',
            '{{%references.relative_extension}}',
            ['standard_id', 'hardness_id', 'outer_diameter_id', 'wall_thickness_id'],
            true
        );

        $handle = fopen(__DIR__ . "/init_data/relative_extension.csv", "r");
        for($i = 0; $row = fgetcsv($handle); $i++) {
            if (!$i) {
                continue;
            }

            $standardName = trim($row[0]);
            $hardnessName = trim($row[1]);
            $diameterName = trim(str_replace(".", ",", $row[2]));
            $wallName = floatval(str_replace(',', '.', $row[3]));
            $wallName = number_format($wallName, 2, ',');
            $value = floatval(str_replace(',', '.', $row[4])) ?: null;

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
                $this->insert('{{%references.relative_extension}}', [
                    'standard_id' => $standardName == 'CT' ? 2 : 1,
                    'hardness_id' => $hardnessId,
                    'outer_diameter_id' => $diameterId,
                    'wall_thickness_id' => $wallId,
                    'value' => $value,
                ]);
                if ($standardName == 'CT') {
                    $this->insert('{{%references.relative_extension}}', [
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
        $this->dropTable('{{%references.relative_extension}}');
    }
}

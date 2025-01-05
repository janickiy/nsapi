<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m240605_021118_theoretical_mass_reference
 */
class m240605_021118_theoretical_mass_reference extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.theoretical_mass}}', [
            'id' => $this->primaryKey(),
            'outer_diameter_id' => $this->integer()->notNull()->comment('Идентификатор наружнего диаметра'),
            'wall_thickness_id' => $this->integer()->notNull()->comment('Идентификатор толщины стенки'),
            'value' => $this->decimal(10,2)->comment('Теоретическая масса')
        ]);
        $this->addCommentOnTable('{{%references.theoretical_mass}}', 'Теоретическая масса 1 метра');
        $this->addForeignKey(
            'FK_PreferencesTheoreticalMass_OuterDiameterId__ReferencesOuterDiameter_Id',
            '{{%references.theoretical_mass}}',
            'outer_diameter_id',
            '{{%references.outer_diameter}}',
            'id',
        );
        $this->addForeignKey(
            'FK_PreferencesTheoreticalMass_WallThicknessId__ReferencesWallThickness_Id',
            '{{%references.theoretical_mass}}',
            'wall_thickness_id',
            '{{%references.wall_thickness}}',
            'id',
        );
        $this->createIndex(
            'UQ_PreferencesTheoreticalMass_OuterDiameterId_WallThicknessId',
            '{{%references.theoretical_mass}}',
            ['outer_diameter_id', 'wall_thickness_id'],
            true
        );

        $oldWalls = (new Query())
            ->from('{{%references.wall_thickness}}')
            ->all();
        foreach ($oldWalls as $oldWall) {
            $this->update(
                '{{%references.wall_thickness}}',
                ['name' => number_format($oldWall['value'], 2, ',')],
                ['id' => $oldWall['id']]
            );
        }

        $existData = [];
        $handle = fopen(__DIR__ . "/init_data/theoretical_mass.csv", "r");
        for($i = 0; $row = fgetcsv($handle); $i++) {
            if (!$i) {
                continue;
            }
            $diameterName = trim(str_replace(".", ",", $row[0]));
            $wallName = trim(str_replace(".", ",", $row[1]));
            $value = floatval(str_replace(',', '.', $row[2])) ?: null;

            $key = $diameterName . "_" . $wallName . "_" . ($value ?? '0');
            if (!in_array($key, $existData)) {
                $existData[] = $key;
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
                if ($diameterId && $wallId) {
                    $this->insert('{{%references.theoretical_mass}}', [
                        'outer_diameter_id' => $diameterId,
                        'wall_thickness_id' => $wallId,
                        'value' => $value,
                    ]);
                }
            }
        }
        fclose($handle);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%references.theoretical_mass}}');
    }
}

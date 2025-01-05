<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m240613_062500_add_absorbed_energy_limit
 */
class m240613_062500_add_absorbed_energy_limit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.absorbed_energy_limit}}', [
            'id' => $this->primaryKey(),
            'standard_id' => $this->integer()->notNull()->comment('Идентификатор стандарта'),
            'wall_thickness_id' => $this->integer()->notNull()->comment('Идентификатор толщины стенки'),
            'value' => $this->decimal(10, 2)->comment('Значение не менее'),
            'value_average' => $this->decimal(10, 2)->comment('Среднее значение не менее')
        ]);
        $this->addCommentOnTable('{{%references.absorbed_energy_limit}}', 'Поглощенная энергия');
        $this->addForeignKey(
            'FK_ReferencesAbsorbedEnergyLimit_StandardId__ReferencesStandard_Id',
            '{{%references.absorbed_energy_limit}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesAbsorbedEnergyLimit_WallThicknessId__ReferencesWallThickness_Id',
            '{{%references.absorbed_energy_limit}}',
            'wall_thickness_id',
            '{{%references.wall_thickness}}',
            'id'
        );
        $this->createIndex(
            'UQ_ReferencesAbsorbedEnergyLimit_StandardId_WallThicknessId',
            '{{%references.absorbed_energy_limit}}',
            ['standard_id', 'wall_thickness_id'],
            true
        );

        $handle = fopen(__DIR__ . "/init_data/absorbed_energy.csv", "r");
        for($i = 0; $row = fgetcsv($handle); $i++) {
            if ($i < 2) {
                continue;
            }

            $wallName = floatval(str_replace(',', '.', $row[0]));
            $wallName = number_format($wallName, 2, ',');
            $value = floatval(str_replace(',', '.', $row[1])) ?: null;
            $value_average = floatval(str_replace(',', '.', $row[2])) ?: null;

            $wallId = (new Query())
                ->select('id')
                ->from('{{%references.wall_thickness}}')
                ->where(['name' => $wallName])
                ->scalar();


            $this->insert('{{%references.absorbed_energy_limit}}', [
                'standard_id' => 2,
                'wall_thickness_id' => $wallId,
                'value' => $value,
                'value_average' => $value_average,
            ]);
        }
        fclose($handle);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%references.absorbed_energy_limit}}');
    }
}

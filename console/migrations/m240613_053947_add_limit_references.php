<?php

use yii\db\Migration;
use yii\db\Query;

/**
 * Class m240613_053947_add_limit_references
 */
class m240613_053947_add_limit_references extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%references.strength_limit}}', [
            'id' => $this->primaryKey(),
            'standard_id' => $this->integer()->notNull()->comment('Идентификатор стандарта'),
            'hardness_id' => $this->integer()->notNull()->comment('Идентификатор группы прочности'),
            'value' => $this->decimal(10, 2)->comment('Значение')
        ]);
        $this->addCommentOnTable('{{%references.strength_limit}}', 'Предел прочности');
        $this->addForeignKey(
            'FK_ReferencesStrengthLimit_StandardId__ReferencesStandard_Id',
            '{{%references.strength_limit}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesStrengthLimit_HardnessId__ReferencesHardness_Id',
            '{{%references.strength_limit}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->createIndex(
            'UQ_ReferencesStrengthLimit_StandardId_HardnessId',
            '{{%references.strength_limit}}',
            ['standard_id', 'hardness_id'],
            true
        );

        $this->createTable('{{%references.hardness_limit}}', [
            'id' => $this->primaryKey(),
            'standard_id' => $this->integer()->notNull()->comment('Идентификатор стандарта'),
            'hardness_id' => $this->integer()->notNull()->comment('Идентификатор группы прочности'),
            'value' => $this->decimal(10, 2)->comment('Значение')
        ]);
        $this->addCommentOnTable('{{%references.hardness_limit}}', 'Предел твердости');
        $this->addForeignKey(
            'FK_ReferencesHardnessLimit_StandardId__ReferencesStandard_Id',
            '{{%references.hardness_limit}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesHardnessLimit_HardnessId__ReferencesHardness_Id',
            '{{%references.hardness_limit}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->createIndex(
            'UQ_ReferencesHardnessLimit_StandardId_HardnessId',
            '{{%references.hardness_limit}}',
            ['standard_id', 'hardness_id'],
            true
        );

        $this->createTable('{{%references.fluidity_limit}}', [
            'id' => $this->primaryKey(),
            'standard_id' => $this->integer()->notNull()->comment('Идентификатор стандарта'),
            'hardness_id' => $this->integer()->notNull()->comment('Идентификатор группы прочности'),
            'value_min' => $this->decimal(10, 2)->comment('Не менее'),
            'value_max' => $this->decimal(10, 2)->comment('Не более')
        ]);
        $this->addCommentOnTable('{{%references.fluidity_limit}}', 'Предел текучести');
        $this->addForeignKey(
            'FK_ReferencesFluidityLimit_StandardId__ReferencesStandard_Id',
            '{{%references.fluidity_limit}}',
            'standard_id',
            '{{%references.standard}}',
            'id'
        );
        $this->addForeignKey(
            'FK_ReferencesFluidityLimit_HardnessId__ReferencesHardness_Id',
            '{{%references.fluidity_limit}}',
            'hardness_id',
            '{{%references.hardness}}',
            'id'
        );
        $this->createIndex(
            'UQ_ReferencesFluidityLimit_StandardId_HardnessId',
            '{{%references.fluidity_limit}}',
            ['standard_id', 'hardness_id'],
            true
        );

        $handle = fopen(__DIR__ . "/init_data/limits.csv", "r");
        for($i = 0; $row = fgetcsv($handle); $i++) {
            if (!$i) {
                continue;
            }

            $standardName = trim($row[0]);
            $hardnessName = trim($row[1]);
            $value_s = floatval(str_replace(',', '.', $row[2])) ?: null;
            $value_min = floatval(str_replace(',', '.', $row[3])) ?: null;
            $value_max = floatval(str_replace(',', '.', $row[4])) ?: null;
            $value_h = floatval(str_replace(',', '.', $row[5])) ?: null;

            $hardnessId = (new Query())
                ->select('id')
                ->from('{{%references.hardness}}')
                ->where(['name' => $hardnessName])
                ->scalar();


            $this->insert('{{%references.strength_limit}}', [
                'standard_id' => $standardName == 'СТ' ? 2 : 1,
                'hardness_id' => $hardnessId,
                'value' => $value_s,
            ]);
            $this->insert('{{%references.hardness_limit}}', [
                'standard_id' => $standardName == 'СТ' ? 2 : 1,
                'hardness_id' => $hardnessId,
                'value' => $value_h,
            ]);
            $this->insert('{{%references.fluidity_limit}}', [
                'standard_id' => $standardName == 'СТ' ? 2 : 1,
                'hardness_id' => $hardnessId,
                'value_min' => $value_min,
                'value_max' => $value_max,
            ]);

            if ($standardName == 'СТ') {
                $this->insert('{{%references.strength_limit}}', [
                    'standard_id' => 3,
                    'hardness_id' => $hardnessId,
                    'value' => $value_s,
                ]);
                $this->insert('{{%references.hardness_limit}}', [
                    'standard_id' => 3,
                    'hardness_id' => $hardnessId,
                    'value' => $value_h,
                ]);
                $this->insert('{{%references.fluidity_limit}}', [
                    'standard_id' => 3,
                    'hardness_id' => $hardnessId,
                    'value_min' => $value_min,
                    'value_max' => $value_max,
                ]);
            }
        }
        fclose($handle);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%references.strength_limit}}');
        $this->dropTable('{{%references.hardness_limit}}');
        $this->dropTable('{{%references.fluidity_limit}}');
    }
}

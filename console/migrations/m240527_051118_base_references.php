<?php

use yii\db\Migration;

/**
 * Class m240527_051118_base_references
 */
class m240527_051118_base_references extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("CREATE SCHEMA IF NOT EXISTS {{%references}};");

        $this->createTable('{{%references.standard}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Название стандарта'),
        ]);
        $this->addCommentOnTable('{{%references.standard}}', 'Справочник стандартов');
        $this->batchInsert('{{%references.standard}}', ['name'], [
            ['ТУ 24.20.32-001-05094951-2017'],
            ['ТУ 24.20.32-002-05094951-2018'],
            ['API Spec 5ST']
        ]);

        $this->createTable('{{%references.hardness}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Название группы'),
        ]);
        $this->addCommentOnTable('{{%references.hardness}}', 'Справочник групп прочности');
        $this->batchInsert('{{%references.hardness}}', ['name'], [
            ['70'], ['80'], ['90'], ['100'], ['110']
        ]);

        $this->createTable('{{%references.outer_diameter}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Название'),
            'millimeter' => $this->decimal(10, 2)->notNull()->unique()->comment('Диаметр в миллиметрах'),
            'inch' => $this->decimal(10, 2)->notNull()->unique()->comment('Диаметр в дюймах'),
        ]);
        $this->addCommentOnTable('{{%references.outer_diameter}}', 'Справочник внешних диаметров');
        $this->batchInsert('{{%references.outer_diameter}}', ['name', 'millimeter', 'inch'], [
            ['25,40', 25.4, 1],
            ['31,80', 31.8, 1.25],
            ['33,50', 33.5, 1.32],
            ['38,10', 38.1, 1.5],
            ['44,50', 44.5, 1.75],
            ['50,80', 50.8, 2],
            ['60,30', 60.3, 2.37],
            ['66,70', 66.7, 2.63],
            ['73,00', 73, 2.87],
            ['82,60', 82.6, 3.25],
            ['88,90', 88.9, 3.5],
        ]);

        $this->createTable('{{%references.customer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique()->comment('Название'),
        ]);
        $this->addCommentOnTable('{{%references.customer}}', 'Справочник покупателей');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%references.customer}}');
        $this->dropTable('{{%references.outer_diameter}}');
        $this->dropTable('{{%references.hardness}}');
        $this->dropTable('{{%references.standard}}');

        $this->execute("DROP SCHEMA IF EXISTS {{%references}};");
    }
}

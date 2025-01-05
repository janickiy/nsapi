<?php

namespace common\models\certificates;

use yii\db\ActiveRecord;

/**
 * Статус сертификата
 *
 * @property string $id
 * @property string $name
 */
class Status extends ActiveRecord
{
    const
        STATUS_DRAFT = 'draft',
        STATUS_APPROVE = 'approve',
        STATUS_PUBLISHED = 'published',
        STATUS_REFUNDED = 'refunded',
        STATUS_DELETED = 'deleted';
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.status';
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Название',
        ];
    }
}

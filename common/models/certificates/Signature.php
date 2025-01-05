<?php

namespace common\models\certificates;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Плавка
 *
 * @property integer $id
 * @property integer $certificate_id Идентификатор сертификата
 * @property string $name ФИО
 * @property string $position Должность
 *
 * @property-read Certificate $certificate
 */
class Signature extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.signature';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['certificate_id'], 'required'],
            [['name', 'position'], 'string'],
            [['certificate_id'], 'integer'],
            [
                ['certificate_id'],
                'exist',
                'targetClass' => Certificate::class,
                'targetAttribute' => ['certificate_id' => 'id']
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'certificate_id' => 'Сертификат',
            'name' => 'ФИО',
            'position' => 'Должность',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCertificate(): ActiveQuery
    {
        return $this->hasOne(Certificate::class, ['id' => 'certificate_id']);
    }
}

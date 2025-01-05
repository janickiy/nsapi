<?php

namespace common\models\certificates;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Плавка
 *
 * @property integer $id
 * @property integer $certificate_id Идентификатор сертификата
 * @property string $number Номер плавки
 * @property numeric $sekv СЭКВ, %
 * @property string $sekv_note Сноска для СЭКВ
 * @property numeric $chemical_c Химический состав углерод C, %
 * @property numeric $chemical_mn Химический состав марганец Mn, %
 * @property numeric $chemical_si Химический состав кремний Si, %
 * @property numeric $chemical_s Химический состав сера S, %
 * @property numeric $chemical_p Химический состав фосфор P, %
 * @property numeric $dirty_type_a Степень загрязненности Тип A
 * @property numeric $dirty_type_b Степень загрязненности Тип B
 * @property numeric $dirty_type_c Степень загрязненности Тип C
 * @property numeric $dirty_type_d Степень загрязненности Тип D
 * @property numeric $dirty_type_ds Степень загрязненности Тип DS
 *
 * @property-read Certificate $certificate
 * @property-read Roll[] $rolls
 */
class Meld extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.meld';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['certificate_id', 'number'], 'required'],
            [['number'], 'string'],
            [['sekv_note'], 'string', 'max' => 255],
            [['certificate_id'], 'integer'],
            [
                [
                    'sekv',
                    'chemical_c', 'chemical_mn', 'chemical_si', 'chemical_s', 'chemical_p',
                    'dirty_type_a', 'dirty_type_b', 'dirty_type_c', 'dirty_type_d', 'dirty_type_ds',
                ],
                'number'
            ],
            [
                ['certificate_id'],
                'exist',
                'targetClass' => Certificate::class,
                'targetAttribute' => ['certificate_id' => 'id']
            ],
            [
                ['number'],
                'unique',
                'targetAttribute' => ['certificate_id', 'number'],
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
            'number' => 'Номер плавки',
            'sekv' => 'СЭКВ',
            'sekv_note' => 'Сноска для СЭКВ',
            'chemical_c' => 'Углерод',
            'chemical_mn' => 'Марганец',
            'chemical_si' => 'Кремний',
            'chemical_s' => 'Сера',
            'chemical_p' => 'Фосвор',
            'dirty_type_a' => 'Тип A',
            'dirty_type_b' => 'Тип B',
            'dirty_type_c' => 'Тип C',
            'dirty_type_d' => 'Тип D',
            'dirty_type_ds' => 'Тип DS',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCertificate(): ActiveQuery
    {
        return $this->hasOne(Certificate::class, ['id' => 'certificate_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRolls(): ActiveQuery
    {
        return $this->hasMany(Roll::class, ['meld_id' => 'id'])->orderBy(['serial_number' => SORT_ASC]);
    }
}

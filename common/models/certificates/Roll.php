<?php

namespace common\models\certificates;

use common\models\references\WallThickness;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Рулон
 *
 * @property integer $id
 * @property integer $meld_id Идентификатор плавки
 * @property string $number Номер рулона
 * @property integer $wall_thickness_id Идентификатор толщины стенки
 * @property numeric $length Длина рулона
 * @property string $location_seams_note Сноска для расположения швов
 * @property numeric $grain_size Размер зерна
 * @property string $hardness_note Сноска для твердости
 * @property boolean $is_exact_hardness_om Точное или нет значение твердости ОМ
 * @property numeric $hardness_om Твердтость OM
 * @property boolean $is_exact_hardness_ssh Точное или нет значение твердости СШ
 * @property numeric $hardness_ssh Твердтость СШ
 * @property boolean $is_exact_hardness_ztv Точное или нет значение твердости ЗТВ
 * @property numeric $hardness_ztv Твердтость ЗТВ
 * @property boolean $is_use_note Использовать сноску
 * @property numeric $absorbed_energy_1 Поглощенная энергия образец 1
 * @property numeric $absorbed_energy_2 Поглощенная энергия образец 2
 * @property numeric $absorbed_energy_3 Поглощенная энергия образец 3
 * @property numeric $fluidity Предел текучести
 * @property string $fluidity_note Сноска для предела текучести
 * @property numeric $strength Предел прочности
 * @property numeric $relative_extension Относительное удлинение
 * @property string $relative_extension_note Сноска для относительного удлинения
 * @property boolean $is_fill_testing Заполнены результаты тестирования
 * @property integer $serial_number Порядковый номер
 *
 * @property-read numeric $locationSeams Расположения поперечных швов от начала
 * @property-read Meld $meld
 * @property-read WallThickness $wallThickness
 */
class Roll extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.roll';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['meld_id', 'number'], 'required'],
            [['number'], 'string'],
            [
                [
                    'is_use_note', 'is_fill_testing',
                    'is_exact_hardness_om', 'is_exact_hardness_ssh', 'is_exact_hardness_ztv',
                ],
                'boolean'
            ],
            [
                ['location_seams_note', 'hardness_note', 'fluidity_note', 'relative_extension_note'],
                'string',
                'max' => 255
            ],
            [['meld_id', 'wall_thickness_id', 'serial_number'], 'integer'],
            [
                [
                    'length', 'grain_size',
                    'hardness_om', 'hardness_ssh', 'hardness_ztv',
                    'absorbed_energy_1', 'absorbed_energy_2', 'absorbed_energy_3',
                    'fluidity', 'strength', 'relative_extension',
                ],
                'number'
            ],
            [
                ['meld_id'],
                'exist',
                'targetClass' => Meld::class,
                'targetAttribute' => ['meld_id' => 'id']
            ],
            [
                ['wall_thickness_id'],
                'exist',
                'targetClass' => WallThickness::class,
                'targetAttribute' => ['wall_thickness_id' => 'id']
            ],
            [
                ['number'],
                'unique',
                'targetAttribute' => ['meld_id', 'number'],
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'meld_id' => 'Плавка',
            'number' => 'Номер рулона',
            'wall_thickness_id' => 'Толщина стенки',
            'length' => 'Длина рулона',
            'location_seams_note' => 'Сноска для расположения поперечных швов',
            'grain_size' => 'Размер зерна',
            'hardness_note' => 'Сноска для твердости',
            'is_exact_hardness_om' => 'Точное значение ОМ',
            'hardness_om' => 'Твердтость OM',
            'is_exact_hardness_ssh' => 'Точное значение СШ',
            'hardness_ssh' => 'Твердтость СШ',
            'is_exact_hardness_ztv' => 'Точное значение ЗТВ',
            'hardness_ztv' => 'Твердтость ЗТВ',
            'is_use_note' => 'Использовать сноску',
            'absorbed_energy_1' => 'Поглощенная энергия образец 1',
            'absorbed_energy_2' => 'Поглощенная энергия образец 2',
            'absorbed_energy_3' => 'Поглощенная энергия образец 3',
            'fluidity' => 'Предел текучести',
            'fluidity_note' => 'Сноска для предела текучести',
            'strength' => 'Предел прочности',
            'relative_extension' => 'Относительное удлинение',
            'relative_extension_note' => 'Сноска для относительного удлинения',
            'serial_number' => 'Порядковый номер',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMeld(): ActiveQuery
    {
        return $this->hasOne(Meld::class, ['id' => 'meld_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getWallThickness(): ActiveQuery
    {
        return $this->hasOne(WallThickness::class, ['id' => 'wall_thickness_id']);
    }

    /**
     * @return float|null
     */
    public function getLocationSeams(): ?float
    {
        $result = 0;
        $certificateRolls = $this->meld->certificate->rolls;
        if ($certificateRolls) {
            if ($certificateRolls[count($certificateRolls) - 1]->id == $this->id) {
                return null;
            }
            foreach ($certificateRolls as $roll) {
                if ($roll->length) {
                    $result += $roll->length;
                } else {
                    $result = 0;
                    break;
                }
                if ($roll->id == $this->id) {
                    break;
                }
            }
        }
        return $result ?: null;
    }
}

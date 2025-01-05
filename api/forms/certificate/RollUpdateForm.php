<?php

namespace api\forms\certificate;

use api\models\references\RelativeExtension;
use common\models\certificates\Certificate;
use common\models\certificates\Roll;
use common\models\references\AbsorbedEnergyLimit;
use common\models\references\FluidityLimit;
use common\models\references\HardnessLimit;
use common\models\references\StrengthLimit;
use common\models\references\WallThickness;
use yii\base\Model;

class RollUpdateForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $wall_thickness_id;
    public $length;
    public $location_seams_note;
    public $grain_size;
    public $hardness_note;
    public $is_exact_hardness_om;
    public $hardness_om;
    public $is_exact_hardness_ssh;
    public $hardness_ssh;
    public $is_exact_hardness_ztv;
    public $hardness_ztv;
    public $is_use_note;
    public $absorbed_energy_1;
    public $absorbed_energy_2;
    public $absorbed_energy_3;
    public $fluidity;
    public $fluidity_note;
    public $strength;
    public $relative_extension;
    public $relative_extension_note;
    public $is_fill_testing = false;

    private $roll;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                'wall_thickness_id', 'length', 'location_seams_note',
                'grain_size', 'hardness_note', 'hardness_om', 'hardness_ssh', 'hardness_ztv', 'is_use_note',
                'absorbed_energy_1', 'absorbed_energy_2', 'absorbed_energy_3', 'fluidity', 'strength',
                'relative_extension', 'fluidity_note', 'relative_extension_note', 'is_fill_testing',
                'is_exact_hardness_om', 'is_exact_hardness_ssh', 'is_exact_hardness_ztv',
            ],
            self::SCENARIO_PUBLISH => [
                'wall_thickness_id', 'length', 'location_seams_note',
                'grain_size', 'hardness_note', 'hardness_om', 'hardness_ssh', 'hardness_ztv', 'is_use_note',
                'absorbed_energy_1', 'absorbed_energy_2', 'absorbed_energy_3', 'fluidity', 'strength',
                'relative_extension', 'fluidity_note', 'relative_extension_note', 'is_fill_testing',
                'is_exact_hardness_om', 'is_exact_hardness_ssh', 'is_exact_hardness_ztv',
            ]
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
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
            [
                ['location_seams_note', 'hardness_note', 'fluidity_note', 'relative_extension_note'],
                'filter',
                'filter' => 'trim'
            ],
            [
                ['location_seams_note', 'hardness_note', 'fluidity_note', 'relative_extension_note'],
                'filter',
                'filter' => 'strip_tags'
            ],
            [
                ['location_seams_note', 'hardness_note', 'fluidity_note', 'relative_extension_note'],
                'default',
                'value' => null
            ],
            [['wall_thickness'], 'integer'],
            [
                [
                    'length', 'grain_size',
                    'hardness_om', 'hardness_ssh', 'hardness_ztv',
                    'absorbed_energy_1', 'absorbed_energy_2', 'absorbed_energy_3',
                    'fluidity', 'strength', 'relative_extension',
                ],
                'number',
                'min' => 0
            ],
            [
                ['wall_thickness_id'],
                'exist',
                'targetClass' => WallThickness::class,
                'targetAttribute' => ['wall_thickness_id' => 'id']
            ],
            [
                [
                    'wall_thickness_id', 'length',
                ],
                'required',
                'on' => self::SCENARIO_PUBLISH,

            ],
//            [
//                [
//                    'absorbed_energy_1', 'absorbed_energy_2', 'absorbed_energy_3', 'fluidity', 'strength',
//                    'relative_extension',
//                ],
//                'required',
//                'on' => self::SCENARIO_PUBLISH,
//                'when' => function (RollUpdateForm $model) {
//                    return $model->is_fill_testing;
//                }
//            ],
            [['grain_size'], 'number', 'min' => Certificate::GRAIN_MAX, 'on' => self::SCENARIO_PUBLISH],
            [['hardness_om', 'hardness_ssh', 'hardness_ztv'], 'validateHardness', 'on' => self::SCENARIO_PUBLISH],
            [
                ['absorbed_energy_1', 'absorbed_energy_2', 'absorbed_energy_3'],
                'validateEnergy',
                'on' => self::SCENARIO_PUBLISH,
                'when' => function (RollUpdateForm $model) {
                    return $model->is_fill_testing;
                }
            ],
            [
                ['strength'],
                'validateStrength',
                'on' => self::SCENARIO_PUBLISH,
                'when' => function (RollUpdateForm $model) {
                    return $model->is_fill_testing;
                }
            ],
            [
                ['relative_extension'],
                'validateRelativeExtension',
                'on' => self::SCENARIO_PUBLISH,
                'when' => function (RollUpdateForm $model) {
                    return $model->is_fill_testing;
                }
            ],
            [
                ['fluidity'],
                'validateFluidity',
                'on' => self::SCENARIO_PUBLISH,
                'when' => function (RollUpdateForm $model) {
                    return $model->is_fill_testing;
                }
            ],
        ];
    }

    public function validateHardness($attribute): void
    {
        if ($this->roll) {
            $hardnessLimit = HardnessLimit::findByCertificate($this->roll->meld->certificate);
            if ($hardnessLimit) {
                if ($this->$attribute > $hardnessLimit->value) {
                    $this->addError(
                        $attribute, 'Значение должно быть не более ' . $hardnessLimit->value
                    );
                }
            }
        }
    }

    public function validateEnergy($attribute): void
    {
        if ($this->roll) {
            $absorbedEnergyLimit = AbsorbedEnergyLimit::findByWallThickness(
                $this->roll->wallThickness, $this->roll->meld->certificate
            );
            if ($absorbedEnergyLimit) {
                if ($this->$attribute < $absorbedEnergyLimit->value) {
                    $this->addError(
                        $attribute, 'Значение должно быть не менее ' . $absorbedEnergyLimit->value
                    );
                }
            }
        }
    }

    public function validateStrength($attribute): void
    {
        if ($this->roll) {
            $strengthLimit = StrengthLimit::findByCertificate($this->roll->meld->certificate);
            if ($strengthLimit) {
                if ($this->$attribute < $strengthLimit->value) {
                    $this->addError(
                        $attribute, 'Значение должно быть не менее ' . $strengthLimit->value
                    );
                }
            }
        }
    }

    public function validateRelativeExtension($attribute): void
    {
        if ($this->roll) {
            $relativeExtension = RelativeExtension::findByWallThickness(
                $this->roll->wallThickness,
                $this->roll->meld->certificate
            );
            if ($relativeExtension) {
                if ($this->$attribute < $relativeExtension->value) {
                    $this->addError(
                        $attribute, 'Значение должно быть не менее ' . $relativeExtension->value
                    );
                }
            }
        }
    }

    public function validateFluidity($attribute): void
    {
        if ($this->roll) {
            $fluidityLimit = FluidityLimit::findByCertificate($this->roll->meld->certificate);
            if ($fluidityLimit) {
                if ($this->$attribute < $fluidityLimit->value_min) {
                    $this->addError(
                        $attribute, 'Значение должно быть не менее ' . $fluidityLimit->value_min
                    );
                }
                if ($this->$attribute > $fluidityLimit->value_max) {
                    $this->addError(
                        $attribute, 'Значение должно быть не более ' . $fluidityLimit->value_max
                    );
                }
            }
        }
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'wall_thickness_id' => 'Толщина стенки',
            'length' => 'Длина рулона',
            'location_seams_note' => 'Сноска для расположения поперечных швов',
            'grain_size' => 'Размер зерна',
            'hardness_note' => 'Сноска для твердости',
            'hardness_om' => 'Твердтость OM',
            'hardness_ssh' => 'Твердтость СШ',
            'hardness_ztv' => 'Твердтость ЗТВ',
            'is_use_note' => 'Применить сноску',
            'absorbed_energy_1' => 'Поглощенная энергия образец 1',
            'absorbed_energy_2' => 'Поглощенная энергия образец 2',
            'absorbed_energy_3' => 'Поглощенная энергия образец 3',
            'fluidity' => 'Предел текучести',
            'fluidity_note' => 'Сноска для предела текучести',
            'strength' => 'Предел прочности',
            'relative_extension' => 'Относительное удлинение',
            'relative_extension_note' => 'Сноска для относительного удлинения',
        ];
    }

    /**
     * @param Roll $roll
     * @return void
     */
    public function loadData(Roll $roll): void
    {
        $this->roll = $roll;
        $this->setAttributes($roll->attributes, false);
    }
}

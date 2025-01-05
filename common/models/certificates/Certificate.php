<?php

namespace common\models\certificates;

use common\models\references\Hardness;
use common\models\references\OuterDiameter;
use common\models\references\Standard;
use common\models\references\TestPressure;
use common\models\references\TheoreticalMass;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Сертификат
 *
 * @property integer $id
 * @property string $status_id Идентификатор статусы
 * @property string $number Номер документа
 * @property string $number_tube Номер трубы
 * @property string $rfid RFID метка
 * @property string $product_type Вид изделия
 * @property integer $standard_id Идентификатор стандарта
 * @property integer $hardness_id Идентификатор группы прочности
 * @property integer $outer_diameter_id Идентификатор внешнего диаметра
 * @property string $agreement_delivery Договор поставки
 * @property string $type_heat_treatment Вид термической обработки
 * @property string $type_quick_connection Тип быстросъемного соединения
 * @property string $type_quick_connection_note Сноска для типа быстросъемного соединения
 * @property string $result_hydrostatic_test Результат гидростатических испытаний
 * @property string $brand_geophysical_cable Марка геофизического кабеля
 * @property string $brand_geophysical_cable_note Сноска для типа марки геофизического кабеля
 * @property numeric $length_geophysical_cable Длина геофизического кабеля
 * @property string $length_geophysical_cable_note Сноска для длины геофизического кабеля
 * @property string $customer Покупатель
 * @property string $created_at Дата создания
 * @property string $rolls_note Сноска для рулонов
 *
 * @property-read numeric $pressureTest Испытательное давление
 * @property-read numeric $theoreticalMass Теоретическая масса
 *
 * @property-read Status $status Статус
 * @property-read Standard $standard Стандарт
 * @property-read Hardness $hardness Группа прочности
 * @property-read OuterDiameter $outerDiameter Внешний диаметр
 * @property-read NonDestructiveTest[] $nonDestructiveTests Неразрушаюший контроль
 * @property-read Meld[] $melds Плавки
 * @property-read Roll[] $rolls Рулоны
 * @property-read Cylinder $cylinder Барабан
 * @property-read Note[] $notes Примечания
 * @property-read Signature[] $signatures Подписи
 */
class Certificate extends ActiveRecord
{
    //TODO Возможно вынести в настройки
    const
        DIRTY_MAX = 2,
        GRAIN_MAX = 8;

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.certificate';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['status_id', 'number', 'number_tube'], 'required'],
            [['status_id'], 'string'],
            [['created_at'], 'safe'],
            [
                [
                    'number', 'number_tube', 'rfid', 'product_type', 'agreement_delivery', 'type_heat_treatment',
                    'type_quick_connection', 'type_quick_connection_note', 'result_hydrostatic_test',
                    'brand_geophysical_cable', 'customer', 'brand_geophysical_cable_note',
                    'length_geophysical_cable_note', 'rolls_note',
                ],
                'string',
                'max' => 255
            ],
            [['standard_id', 'hardness_id', 'outer_diameter_id'], 'integer'],
            [['length_geophysical_cable'], 'number'],
//            [['number', 'number_tube', 'rfid'], 'unique', 'when' => function (self $model) {
//                return $model->status_id !== Status::STATUS_DELETED;
//            }],
            [['number', 'number_tube', 'rfid'], 'unique'],
            [
                ['status_id'],
                'exist',
                'targetClass' => Status::class,
                'targetAttribute' => ['status_id' => 'id']
            ],
            [
                ['standard_id'],
                'exist',
                'targetClass' => Standard::class,
                'targetAttribute' => ['standard_id' => 'id']
            ],
            [
                ['hardness_id'],
                'exist',
                'targetClass' => Hardness::class,
                'targetAttribute' => ['hardness_id' => 'id']
            ],
            [
                ['outer_diameter_id'],
                'exist',
                'targetClass' => OuterDiameter::class,
                'targetAttribute' => ['outer_diameter_id' => 'id']
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'status_id' => 'Статус',
            'number' => 'Номер документа',
            'number_tube' => 'Номер трубы',
            'rfid' => 'RFID метка',
            'product_type' => 'Вид изделия',
            'standard_id' => 'Стандарт',
            'hardness_id' => 'Группа прочности',
            'outer_diameter_id' => 'Внешний диаметр',
            'agreement_delivery' => 'Договор поставки',
            'type_heat_treatment' => 'Вид термической обработки',
            'type_quick_connection' => 'Тип быстросъемного соединения',
            'type_quick_connection_note' => 'Сноска для типа быстросъемного соединения',
            'result_hydrostatic_test' => 'Результат гидростатических испытаний',
            'brand_geophysical_cable' => 'Марка геофизического кабеля',
            'brand_geophysical_cable_note' => 'Сноска для типа марки геофизического кабеля',
            'length_geophysical_cable' => 'Длина геофизического кабеля',
            'length_geophysical_cable_note' => 'Сноска для длины геофизического кабеля',
            'customer' => 'Покупатель',
            'created_at' => 'Дата создания',
            'testPressure' => 'Испытательное давление',
            'theoreticalMass' => 'Теоретическая масса',
            'rolls_note' => 'Сноска для рулонов'
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'status_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getHardness(): ActiveQuery
    {
        return $this->hasOne(Hardness::class, ['id' => 'hardness_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getStandard(): ActiveQuery
    {
        return $this->hasOne(Standard::class, ['id' => 'standard_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getOuterDiameter(): ActiveQuery
    {
        return $this->hasOne(OuterDiameter::class, ['id' => 'outer_diameter_id']);
    }

    /**
     * @return float
     */
    public function getPressureTest(): float
    {
        $result = [];
        foreach ($this->melds as $meld) {
            foreach ($meld->rolls as $roll) {
                if ($roll->wallThickness) {
                    $testPressure = TestPressure::findByWallThickness($roll->wallThickness, $this);
                    if ($testPressure) {
                        $result[] = $testPressure->value;
                    }
                }
            }
        }
        return $result ? min($result) : 0;
    }

    /**
     * @return float
     */
    public function getTheoreticalMass(): float
    {
        $result = 0;
        foreach ($this->melds as $meld) {
            foreach ($meld->rolls as $roll) {
                if ($roll->wallThickness) {
                    $theoreticalMass = TheoreticalMass::findByWallThickness($roll->wallThickness, $this);
                    if ($theoreticalMass && $roll->length) {
                        $result += ($theoreticalMass->value * $roll->length);
                    }
                }
            }
        }
        return round($result, 3);
    }

    /**
     * @return ActiveQuery
     */
    public function getNonDestructiveTests(): ActiveQuery
    {
        return $this->hasMany(NonDestructiveTest::class, ['certificate_id' => 'id'])
            ->orderBy(['control_object_id' => SORT_ASC, 'control_method_id' => SORT_ASC]);
    }

    /**
     * @return array
     */
    public function getNonDestructiveTestsAsArray(): array
    {
        $result = [];
        $data = ArrayHelper::index($this->nonDestructiveTests, null, 'control_object_id');
        foreach ($data as $controlObjectId => $controlObjects) {
            if (count($controlObjects) > 1) {
                $i = 1;
                foreach ($controlObjects as $item) {
                    $result[$controlObjectId]["method_$i"] = $item;
                    $i++;
                }
            } else {
                $result[$controlObjectId] = $controlObjects[0];
            }
        }
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getMelds(): ActiveQuery
    {
        return $this->hasMany(Meld::class, ['certificate_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRolls(): ActiveQuery
    {
        return $this->hasMany(Roll::class, ['meld_id' => 'id'])
            ->via('melds')
            ->orderBy(['serial_number' => SORT_ASC]);
    }

    /**
     * @return ActiveQuery
     */
    public function getCylinder(): ActiveQuery
    {
        return $this->hasOne(Cylinder::class, ['id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNotes(): ActiveQuery
    {
        return $this->hasMany(Note::class, ['certificate_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSignatures(): ActiveQuery
    {
        return $this->hasMany(Signature::class, ['certificate_id' => 'id']);
    }
}

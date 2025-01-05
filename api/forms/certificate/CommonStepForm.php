<?php

namespace api\forms\certificate;

use common\models\certificates\Certificate;
use common\models\certificates\Status;
use common\models\references\Hardness;
use common\models\references\OuterDiameter;
use common\models\references\Standard;
use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;

class CommonStepForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $number;
    public $number_tube;
    public $rfid;
    public $product_type;
    public $standard_id;
    public $hardness_id;
    public $outer_diameter_id;
    public $agreement_delivery;
    public $type_heat_treatment;
    public $type_quick_connection;
    public $type_quick_connection_note;
    public $result_hydrostatic_test;
    public $brand_geophysical_cable;
    public $brand_geophysical_cable_note;
    public $length_geophysical_cable;
    public $length_geophysical_cable_note;
    public $customer;
    public $created_at;

    private ?int $id = null;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                '!id', 'number', 'number_tube', 'rfid', 'product_type', 'standard_id', 'hardness_id',
                'outer_diameter_id', 'agreement_delivery', 'type_heat_treatment', 'type_quick_connection',
                'type_quick_connection_note', 'result_hydrostatic_test', 'brand_geophysical_cable',
                'length_geophysical_cable', 'customer', 'created_at', 'brand_geophysical_cable_note',
                'length_geophysical_cable_note',
            ],
            self::SCENARIO_PUBLISH => [
                '!id', 'number', 'number_tube', 'rfid', 'product_type', 'standard_id', 'hardness_id',
                'outer_diameter_id', 'agreement_delivery', 'type_heat_treatment', 'type_quick_connection',
                'type_quick_connection_note', 'result_hydrostatic_test', 'brand_geophysical_cable',
                'length_geophysical_cable', 'customer', 'created_at', 'brand_geophysical_cable_note',
                'length_geophysical_cable_note',
            ],
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['created_at'], 'date'],
            [
                [
                    'number', 'number_tube', 'rfid', 'product_type', 'agreement_delivery', 'type_heat_treatment',
                    'type_quick_connection', 'type_quick_connection_note', 'result_hydrostatic_test',
                    'brand_geophysical_cable', 'customer', 'brand_geophysical_cable_note',
                    'length_geophysical_cable_note',
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'number', 'number_tube', 'rfid', 'product_type', 'agreement_delivery', 'type_heat_treatment',
                    'type_quick_connection', 'type_quick_connection_note', 'result_hydrostatic_test',
                    'brand_geophysical_cable', 'customer', 'brand_geophysical_cable_note',
                    'length_geophysical_cable_note',
                ],
                'filter',
                'filter' => 'trim'
            ],
            [
                [
                    'number', 'number_tube', 'rfid', 'product_type', 'agreement_delivery', 'type_heat_treatment',
                    'type_quick_connection', 'type_quick_connection_note', 'result_hydrostatic_test',
                    'brand_geophysical_cable', 'customer', 'brand_geophysical_cable_note',
                    'length_geophysical_cable_note',
                ],
                'filter',
                'filter' => 'strip_tags'
            ],
            [
                [
                    'number', 'number_tube', 'rfid', 'product_type', 'agreement_delivery', 'type_heat_treatment',
                    'type_quick_connection', 'type_quick_connection_note', 'result_hydrostatic_test',
                    'brand_geophysical_cable', 'customer', 'brand_geophysical_cable_note',
                    'length_geophysical_cable_note',
                ],
                'default',
                'value' => null
            ],
            [['standard_id', 'hardness_id', 'outer_diameter_id'], 'integer'],
            [['length_geophysical_cable'], 'number', 'min' => 0],
//            [
//                ['number', 'number_tube', 'rfid'],
//                'unique',
//                'targetClass' => Certificate::class,
//                'filter' => function (ActiveQuery $query) {
//                    $query->andWhere(['<>', 'status_id', Status::STATUS_DELETED]);
//                    if ($this->id) {
//                        $query->andWhere(['<>', 'id', $this->id]);
//                    }
//                    return $query;
//                },
//            ],
            [
                ['number', 'number_tube', 'rfid'],
                'unique',
                'targetClass' => Certificate::class,
                'filter' => function (ActiveQuery $query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                    return $query;
                },
            ],
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
            [['number', 'number_tube', 'created_at'], 'required'],
            [
                [
                    'product_type', 'standard_id', 'hardness_id', 'outer_diameter_id', 'agreement_delivery',
                    'type_heat_treatment', 'type_quick_connection', 'result_hydrostatic_test',
                    'customer',
                ],
                'required',
                'on' =>  self::SCENARIO_PUBLISH,
            ],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
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
        ];
    }

    /**
     * @param Certificate $certificate
     * @return void
     */
    public function loadData(Certificate $certificate): void
    {
        $this->setAttributes($certificate->attributes, false);
        $this->id = $certificate->id;
        $this->created_at = Yii::$app->formatter->asDate($certificate->created_at);
    }
}

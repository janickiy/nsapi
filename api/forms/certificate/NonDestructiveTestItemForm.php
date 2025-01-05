<?php

namespace api\forms\certificate;

use common\models\certificates\NonDestructiveTest;
use common\models\references\ControlMethod;
use common\models\references\ControlResult;
use common\models\references\NdControlMethod;
use yii\base\Model;
use yii\db\ActiveQuery;

class NonDestructiveTestItemForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $control_method_id;
    public $nd_control_method_id;
    public $control_result_id;
    public $note;

    public int $certificate_id;
    public string $control_object_id;

    private ?int $id = null;

    public function __construct(int $certificateId, string $controlObjectId, array $config = [])
    {
        parent::__construct($config);
        $this->certificate_id = $certificateId;
        $this->control_object_id = $controlObjectId;
    }

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                'control_method_id', 'nd_control_method_id', 'control_result_id', 'note'
            ],
            self::SCENARIO_PUBLISH => [
                'control_method_id', 'nd_control_method_id', 'control_result_id'
            ]
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['control_method_id', 'nd_control_method_id', 'control_result_id'], 'integer'],
            [['note'], 'string', 'max' => 255],
            [['note'], 'filter', 'filter' => 'trim'],
            [['note'], 'filter', 'filter' => 'strip_tags'],
            [['note'], 'default', 'value' => null],
            [
                ['control_method_id'],
                'exist',
                'targetClass' => ControlMethod::class,
                'targetAttribute' => ['control_method_id' => 'id']
            ],
            [
                ['nd_control_method_id'],
                'exist',
                'targetClass' => NdControlMethod::class,
                'targetAttribute' => ['nd_control_method_id' => 'id'],
                'filter' => function (ActiveQuery $query) {
                    if ($this->control_method_id) {
                        $query->andWhere(['control_method_id' => $this->control_method_id]);
                    }
                    return $query;
                },
            ],
            [
                ['control_result_id'],
                'exist',
                'targetClass' => ControlResult::class,
                'targetAttribute' => ['control_result_id' => 'id']
            ],
            [
                ['control_method_id'],
                'unique',
                'targetClass' => NonDestructiveTest::class,
                'targetAttribute' => ['certificate_id', 'control_object_id', 'control_method_id'],
                'filter' => function (ActiveQuery $query) {
                    if ($this->id) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    }
                    return $query;
                },
                'message' => "Метод контроля уже используется"
            ],
            [
                ['control_method_id', 'nd_control_method_id', 'control_result_id', 'note'],
                'required',
                'on' => self::SCENARIO_PUBLISH,
            ]
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
            'control_method_id' => 'Метод контроля',
            'nd_control_method_id' => 'НД на метод контроля',
            'control_result_id' => 'Результат контроля',
            'note' => 'Сноска',
        ];
    }

    /**
     * @param NonDestructiveTest $test
     * @return void
     */
    public function loadData(NonDestructiveTest $test): void
    {
        $this->id = $test->id;
        $this->setAttributes($test->attributes, false);
    }
}

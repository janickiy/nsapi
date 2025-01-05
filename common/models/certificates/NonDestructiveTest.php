<?php

namespace common\models\certificates;

use common\models\references\ControlMethod;
use common\models\references\ControlResult;
use common\models\references\NdControlMethod;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Неразрушающий контроль
 *
 * @property integer $id
 * @property integer $certificate_id Идентификатор сертификата
 * @property string $control_object_id Идентификатор объекта контроля
 * @property integer $control_method_id Идентификатор метода контроля
 * @property integer $nd_control_method_id Идентификатор НД на метод контроля
 * @property integer $control_result_id Идентификатор результата контроля
 * @property string $note Текст сноски
 *
 * @property-read Certificate $certificate
 * @property-read ControlObject $controlObject
 * @property-read ControlMethod $controlMethod
 * @property-read NdControlMethod $ndControlMethod
 * @property-read ControlResult $controlResult
 */
class NonDestructiveTest extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'certificates.non_destructive_test';
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['certificate_id', 'control_object_id'], 'required'],
            [['control_object_id'], 'string'],
            [['note'], 'string', 'max' => 255],
            [['certificate_id', 'control_method_id', 'nd_control_method_id', 'control_result_id'], 'integer'],
            [
                ['certificate_id'],
                'exist',
                'targetClass' => Certificate::class,
                'targetAttribute' => ['certificate_id' => 'id']
            ],
            [
                ['control_object_id'],
                'exist',
                'targetClass' => ControlObject::class,
                'targetAttribute' => ['control_object_id' => 'id']
            ],
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
                'targetAttribute' => ['certificate_id', 'control_object_id', 'control_method_id'],
            ],
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
            'certificate_note' => 'Сноска',
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
    public function getControlObject(): ActiveQuery
    {
        return $this->hasOne(ControlObject::class, ['id' => 'control_object_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getControlMethod(): ActiveQuery
    {
        return $this->hasOne(ControlMethod::class, ['id' => 'control_method_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getNdControlMethod(): ActiveQuery
    {
        return $this->hasOne(NdControlMethod::class, ['id' => 'nd_control_method_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getControlResult(): ActiveQuery
    {
        return $this->hasOne(ControlResult::class, ['id' => 'control_result_id']);
    }
}

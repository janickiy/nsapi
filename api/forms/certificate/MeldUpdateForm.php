<?php

namespace api\forms\certificate;

use api\models\references\MassFraction;
use common\models\certificates\Certificate;
use common\models\certificates\Meld;
use yii\base\Model;

class MeldUpdateForm extends Model
{
    const
        SCENARIO_DRAFT = 'draft',
        SCENARIO_PUBLISH = 'publish';

    public $sekv;
    public $sekv_note;
    public $chemical_c;
    public $chemical_mn;
    public $chemical_si;
    public $chemical_s;
    public $chemical_p;
    public $dirty_type_a;
    public $dirty_type_b;
    public $dirty_type_c;
    public $dirty_type_d;
    public $dirty_type_ds;

    private $meld;

    /**
     * @return array[]
     */
    public function scenarios(): array
    {
        return [
            self::SCENARIO_DRAFT => [
                'sekv', 'sekv_note',
                'chemical_c', 'chemical_mn', 'chemical_si', 'chemical_s', 'chemical_p',
                'dirty_type_a', 'dirty_type_b', 'dirty_type_c', 'dirty_type_d', 'dirty_type_ds',
            ],
            self::SCENARIO_PUBLISH => [
                'sekv', 'sekv_note',
                'chemical_c', 'chemical_mn', 'chemical_si', 'chemical_s', 'chemical_p',
                'dirty_type_a', 'dirty_type_b', 'dirty_type_c', 'dirty_type_d', 'dirty_type_ds',
            ]
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['sekv_note'], 'string', 'max' => 255],
            [['sekv_note'], 'filter', 'filter' => 'trim'],
            [['sekv_note'], 'filter', 'filter' => 'strip_tags'],
            [['sekv_note'], 'default', 'value' => null],
            [
                [
                    'sekv',
                    'chemical_c', 'chemical_mn', 'chemical_si', 'chemical_s', 'chemical_p',
                    'dirty_type_a', 'dirty_type_b', 'dirty_type_c', 'dirty_type_d', 'dirty_type_ds',
                ],
                'number',
                'min' => 0,
            ],
            [
                [
                    'chemical_c', 'chemical_mn', 'chemical_si', 'chemical_s', 'chemical_p',
                    'dirty_type_a', 'dirty_type_b', 'dirty_type_c', 'dirty_type_d', 'dirty_type_ds',
                ],
                'required',
                'on' => self::SCENARIO_PUBLISH
            ],
            [
                [
                    'chemical_c', 'chemical_mn', 'chemical_si', 'chemical_s', 'chemical_p',
                ],
                'validateChemical',
                'on' => self::SCENARIO_PUBLISH
            ],
            [
                [
                    'dirty_type_a', 'dirty_type_b', 'dirty_type_c', 'dirty_type_d', 'dirty_type_ds',
                ],
                'validateChemical',
                'on' => self::SCENARIO_PUBLISH
            ]
        ];
    }

    public function validateChemical($attribute): void
    {
        if ($this->meld) {
            $massFraction = MassFraction::findByCertificate($this->meld->certificate);
            if ($massFraction) {
                switch ($attribute) {
                    case 'chemical_c':
                        if ($this->$attribute > $massFraction->carbon) {
                            $this->addError(
                                $attribute, 'Значение должно быть не более ' . $massFraction->carbon
                            );
                        }
                        break;
                    case 'chemical_mn':
                        if ($this->$attribute > $massFraction->manganese) {
                            $this->addError(
                                $attribute, 'Значение должно быть не более ' . $massFraction->manganese
                            );
                        }
                        break;
                    case 'chemical_si':
                        if ($this->$attribute > $massFraction->silicon) {
                            $this->addError(
                                $attribute, 'Значение должно быть не более ' . $massFraction->silicon
                            );
                        }
                        break;
                    case 'chemical_s':
                        if ($this->$attribute > $massFraction->sulfur) {
                            $this->addError(
                                $attribute, 'Значение должно быть не более ' . $massFraction->sulfur
                            );
                        }
                        break;
                    case 'chemical_p':
                        if ($this->$attribute > $massFraction->phosphorus) {
                            $this->addError(
                                $attribute, 'Значение должно быть не более ' . $massFraction->phosphorus
                            );
                        }
                        break;
                }
            }
        }
    }

    public function validateDirty($attribute, $value): void
    {
        if ($value > Certificate::DIRTY_MAX) {
            $this->addError(
                $attribute, 'Значение должно быть не более ' . Certificate::DIRTY_MAX
            );
        }
    }

    /**
     * @return string[]
     */
    public function attributeLabels(): array
    {
        return [
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
            'dirty_type_ds' => 'ТИп DS',
        ];
    }

    /**
     * @param Meld $meld
     * @return void
     */
    public function loadData(Meld $meld): void
    {
        $this->meld = $meld;
        $this->setAttributes($meld->attributes, false);
    }
}

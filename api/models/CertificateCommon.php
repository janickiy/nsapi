<?php

namespace api\models;

use common\models\certificates\Certificate;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;


/**
 * @property-read Cylinder $cylinder Барабан
 * @property-read Note[] $notes Примечания
 * @property-read Signature[] $signatures Подписи
 */
class CertificateCommon extends Certificate
{
    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id',
            'number',
            'number_tube',
            'created_at' => function (CertificateCommon $model) {
                return Yii::$app->formatter->asDate($model->created_at);
            },
            'rfid',
            'product_type',
            'standard_id',
            'hardness_id',
            'outer_diameter_id',
            'agreement_delivery',
            'type_heat_treatment',
            'type_quick_connection',
            'type_quick_connection_note',
            'result_hydrostatic_test',
            'brand_geophysical_cable',
            'brand_geophysical_cable_note',
            'length_geophysical_cable',
            'length_geophysical_cable_note',
            'customer',
            'test_pressure' => function (CertificateCommon $model) {
                return $model->pressureTest;
            },
            'theoretical_mass' => function (CertificateCommon $model) {
                return $model->theoreticalMass;
            }
        ];
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
     * @return array
     */
    public function getDetailTube(): array
    {
        $melds = [];
        foreach ($this->melds as $meld) {
            $melds[$meld->id] = $meld;
        }
        return [
            'rolls_note' => $this->rolls_note,
            'melds' => $melds,
        ];
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
     * @return array
     */
    public function getNotesAsArray(): array
    {
        $result = [];
        foreach ($this->notes as $note) {
            $result[$note->id] = $note;
        }
        return $result;
    }

    /**
     * @return ActiveQuery
     */
    public function getSignatures(): ActiveQuery
    {
        return $this->hasMany(Signature::class, ['certificate_id' => 'id']);
    }

    /**
     * @return array
     */
    public function getSignaturesAsArray(): array
    {
        $result = [];
        foreach ($this->signatures as $signature) {
            $result[$signature->id] = $signature;
        }
        return $result;
    }
}

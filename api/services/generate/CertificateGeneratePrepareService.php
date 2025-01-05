<?php

namespace api\services\generate;

use api\models\CertificateCommon;
use api\models\references\RelativeExtension;
use common\models\certificates\Roll;
use common\models\references\AbsorbedEnergyLimit;
use Yii;
use yii\helpers\ArrayHelper;

class CertificateGeneratePrepareService
{
    protected CertificateCommon $certificate;

    /**
     * @param CertificateCommon $certificate
     */
    public function __construct(CertificateCommon $certificate)
    {
        $this->certificate = $certificate;
    }


    /**
     * @return array
     */
    public function getCommonData(): array
    {
        $queryWall = Roll::find()->alias('r')
            ->select(['wall' => 'w.name', 'length' => 'SUM(r.length)'])
            ->innerJoinWith('meld m')
            ->joinWith('wallThickness w')
            ->where(['m.certificate_id' => $this->certificate->id])
            ->groupBy(['w.name', 'w.value'])
            ->orderBy(['w.value' => SORT_DESC])
            ->asArray()
            ->all();


        return [
            ['title' => "Вид изделия", 'value' => $this->certificate->product_type ?? ''],
            ['title' => 'Номер изделия','value' => $this->certificate->number_tube ?? ''],
            [
                'title' => 'Стандарт на изделие',
                'value' => $this->certificate->standard ? $this->certificate->standard->name : ''
            ],
            [
                'title' => 'Договор поставки / номер спецификации/ номер позиции',
                'value' => $this->certificate->agreement_delivery ?? ''
            ],
            [
                'title' => 'Наружный диаметр',
                'unit' => 'мм',
                'value' => $this->certificate->outerDiameter ? $this->certificate->outerDiameter->name : ''
            ],
            [
                'title' => 'Толщина стенки',
                'unit' => 'мм',
                'value' => implode('/', ArrayHelper::getColumn($queryWall, 'wall'))
            ],
            [
                'title' => 'Группа прочности',
                'value' => $this->certificate->hardness
                    ? ((
                        $this->certificate->standard ? $this->certificate->standard->prefix : ''
                        ) . $this->certificate->hardness->name
                    )
                    : ''
            ],
            [
                'title' => 'Длина',
                'unit' => 'м',
                'value' => implode('/', ArrayHelper::getColumn($queryWall, 'length'))
            ],
            [
                'title' => 'Масса теоретическая',
                'unit' => 'тн',
                'value' => $this->certificate->theoreticalMass
                    ? Yii::$app->formatter->asDecimal(
                        round($this->certificate->theoreticalMass / 1000, 3)
                    )
                    : ''],
            ['title' => 'Вид термической обработки', 'value' => $this->certificate->type_heat_treatment ?? ''],
            [
                'title' => 'Тип быстроразъемного соединения',
                'ref' => $this->certificate->type_quick_connection_note,
                'value' => $this->certificate->type_quick_connection ?? ''
            ],
            [
                'title' => 'Давление испытательное',
                'unit' => 'МПа',
                'value' => $this->certificate->pressureTest
                    ? Yii::$app->formatter->asDecimal($this->certificate->pressureTest, 2)
                    : ''
            ],
            [
                'title' => 'Результат гидростатических испытаний',
                'value' => $this->certificate->result_hydrostatic_test ?? ''
            ],
            [
                'title' => 'Марка геофизического кабеля',
                'ref' => $this->certificate->brand_geophysical_cable_note,
                'value' => $this->certificate->brand_geophysical_cable ?? '-'
            ],
            [
                'title' => 'Длина геофизического кабеля',
                'unit' => 'м',
                'ref' => $this->certificate->length_geophysical_cable_note,
                'value' => $this->certificate->length_geophysical_cable
                    ? Yii::$app->formatter->asDecimal($this->certificate->length_geophysical_cable)
                    :'-'
            ],
        ];
    }

    public function getCylinderData(): array
    {
        $cylinder = $this->certificate->cylinder;
        return [
            [
                'title' => 'Материал изготовления транспортировочного барабана (катушки)',
                'value' => $cylinder ? ($cylinder->material ?? '') : ''
            ],
            [
                'title' => 'Масса барабана, тн',
                'value' => $cylinder ? ($cylinder->weight ?? '') : ''
            ],
            [
                'title' => 'Диаметр сердечника барабана, мм',
                'value' => $cylinder ? ($cylinder->diameter_core ?? '') : ''
            ],
            [
                'title' => 'Габаритные размеры барабана:',
                'value' => '-'
            ],
            [
                'title' => ' - диаметр щек, мм',
                'value' => $cylinder ? ($cylinder->diameter_cheek ?? '') : ''
            ],
            [
                'title' => ' - ширина, мм',
                'value' => $cylinder ? ($cylinder->width ?? '') : ''
            ],
            [
                'title' => 'Отметка о заполнении внутренней полости ГНКТ азотом',
                'value' => $cylinder ? ($cylinder->mark_nitrogen ?? '') : ''
            ],
        ];
    }

    /**
     * @return bool|mixed|string|null
     */
    public function getMinRelativeExtension(): mixed
    {
        $wallThicknessIds = [];
        foreach ($this->certificate->melds as $meld) {
            $wallThicknessIds = array_merge($wallThicknessIds, ArrayHelper::getColumn($meld->rolls, 'wall_thickness_id'));
        }
        return RelativeExtension::find()
            ->where([
                'standard_id' => $this->certificate->standard_id,
                'hardness_id' => $this->certificate->hardness_id,
                'outer_diameter_id' => $this->certificate->outer_diameter_id,
                'wall_thickness_id' => $wallThicknessIds,
            ])
            ->min('value');
    }

    /**
     * @param bool $isAverage
     * @return mixed
     */
    public function getMinAbsorbedEnergy(bool $isAverage = false): mixed
    {
        $wallThicknessIds = [];
        foreach ($this->certificate->melds as $meld) {
            $wallThicknessIds = array_merge($wallThicknessIds, ArrayHelper::getColumn($meld->rolls, 'wall_thickness_id'));
        }

        return AbsorbedEnergyLimit::find()
            ->where([
                'standard_id' => $this->certificate->standard_id,
                'wall_thickness_id' => $wallThicknessIds,
            ])
            ->min($isAverage ? 'value_average' : 'value');
    }
}

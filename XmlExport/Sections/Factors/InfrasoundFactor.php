<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Инфразвук.
 */
class InfrasoundFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAInfraNoise'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $infrasound = $this->builder->insertElement($factors, 'Infrasound');
        $this->builder->insertElement($infrasound, 'Value', $rmData['dEqInfraNoise']);

        $infraData = $rmData['aAInfraNoise'];
        if (isset($infraData[0]['pdkM'])) {
            $this->builder->insertElement($infrasound, 'NormValue', $infraData[0]['pdkM']);
        }
        $this->builder->insertElement($infrasound, 'WorkingConditions', $rmData['iAInfraNoise']);
        if (isset($infraData[0]['nd'])) {
            $this->builder->insertElement($infrasound, 'NormativeAct', $infraData[0]['nd']);
        }

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();

        if (is_array($infraData)) {
            foreach ($infraData as $value) {
                $measuringPlace = $this->builder->insertMeasuringPlace($infrasound, $value, $devices, $stuff);
                $this->builder->insertElement($measuringPlace, 'TimeBehavior', '1');
                $this->builder->insertElement($measuringPlace, 'Value', $value['factM']);
            }
        }
    }
}

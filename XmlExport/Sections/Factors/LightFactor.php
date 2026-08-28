<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Освещение.
 */
class LightFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iALight'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $lightEnvironment = $this->builder->insertElement($factors, 'LightEnvironment');
        $this->builder->insertElement($lightEnvironment, 'WorkingConditions', $rmData['iALight']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getWorkers();

        $lightData = $rmData['aALight'];
        if (!is_array($lightData)) {
            return;
        }

        foreach ($lightData as $value) {
            $measuringPlace = $this->builder->insertElement($lightEnvironment, 'MeasuringPlace');
            $this->builder->insertElement($measuringPlace, 'Name', $value[0]['point']);
            $this->builder->insertElement($measuringPlace, 'Date', $value[0]['dtControl']);
            $this->builder->insertElement($measuringPlace, 'Duration', $value[0]['pointTime']);
            $this->builder->insertElement($measuringPlace, 'VisualWorkCategory', $value[0]['catLW']);
            $this->builder->insertElement($measuringPlace, 'WorkingConditions', $value[0]['asset']);

            foreach ($value as $key => $value2) {
                $factorId = $value2['factorId'];

                if ($factorId === '18') {
                    $this->insertGeneralIllumination($measuringPlace, $value2, $devices, $stuff);
                } elseif ($factorId === '19') {
                    $this->insertDirectGlare($measuringPlace, $value2, $stuff);
                } elseif ($factorId === '20') {
                    $this->insertReflectedGlare($measuringPlace, $value2, $stuff);
                }
            }
        }
    }

    private function insertGeneralIllumination($parent, $value, $devices, $stuff)
    {
        $illumination = $this->builder->insertElement($parent, 'GeneralIllumination');
        $this->builder->insertElement($illumination, 'Value', $value['fact']);
        $this->builder->insertElement($illumination, 'NormValue', $value['pdk']);
        $this->builder->insertElement($illumination, 'WorkingConditions', $value['asset']);
        $this->builder->insertElement($illumination, 'NormativeAct', $value['nd']);

        $this->builder->insertToolsId($devices, $illumination);
        $this->builder->insertStuffId($stuff, $illumination);
    }

    private function insertDirectGlare($parent, $value, $stuff)
    {
        $directGlare = $this->builder->insertElement($parent, 'DirectGlare');
        $this->builder->insertElement($directGlare, 'WorkingConditions', $value['asset']);
        $this->builder->insertElement($directGlare, 'FactorSource', $value['point']);
        $this->builder->insertElement($directGlare, 'NormativeAct', $value['nd']);

        $this->builder->insertStuffId($stuff, $directGlare);
    }

    private function insertReflectedGlare($parent, $value, $stuff)
    {
        $reflectedGlare = $this->builder->insertElement($parent, 'ReflectedGlare');
        $this->builder->insertElement($reflectedGlare, 'WorkingConditions', $value['asset']);
        $this->builder->insertElement($reflectedGlare, 'FactorSource', $value['point']);
        $this->builder->insertElement($reflectedGlare, 'NormativeAct', $value['nd']);

        $this->builder->insertStuffId($stuff, $reflectedGlare);
    }
}

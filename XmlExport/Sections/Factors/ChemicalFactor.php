<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Химические факторы.
 */
class ChemicalFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAChem'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $chemicalFactors = $this->builder->insertElement($factors, 'ChemicalFactors');
        $this->builder->insertElement($chemicalFactors, 'WorkingConditions', $rmData['iAChem']);

        $chem = $rmData['aChem'];
        if (is_array($chem)) {
            $this->insertChemicalAgent($chemicalFactors, $chem);
        }
    }

    private function insertChemicalAgent($chemicalFactors, $chem)
    {
        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();

        foreach ($chem as $value) {
            $chemicalAgent = $this->builder->insertElement($chemicalFactors, 'ChemicalAgent');
            $this->builder->insertElement($chemicalAgent, 'Kod', $value['code']);

            if (!empty($value['pdkM'])) {
                $this->builder->insertElement($chemicalAgent, 'NormValueMax', $value['pdkM']);
            }
            if (!empty($value['pdkS'])) {
                $this->builder->insertElement($chemicalAgent, 'NormValueAvg', $value['pdkS']);
            }

            $this->builder->insertElement($chemicalAgent, 'WorkingConditions', $value['asset']);
            $this->builder->insertElement($chemicalAgent, 'NormativeAct', $value['nd']);

            $measuringPlace = $this->builder->insertMeasuringPlace($chemicalAgent, $value, $devices, $stuff);
            $this->builder->insertElement($measuringPlace, 'ConcentrationMax', $value['factM']);
            $this->builder->insertElement($measuringPlace, 'ConcentrationAvg', $value['factS']);
        }
    }
}

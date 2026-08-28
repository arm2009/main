<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Аэрозоли (АПФД).
 */
class AerosolFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAAPFD'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $apfdFactors = $this->builder->insertElement($factors, 'Aerosol');
        $this->builder->insertElement($apfdFactors, 'WorkingConditions', $rmData['iAAPFD']);

        $apfd = $rmData['aAAPFD'];
        if (!is_array($apfd)) {
            return;
        }

        $devices = $this->data->getDevice();
        $stuff = $this->data->getWorkers();

        foreach ($apfd as $value) {
            $chemicalAgent = $this->builder->insertElement($apfdFactors, 'ChemicalAgent');
            $this->builder->insertElement($chemicalAgent, 'Kod', $value['code']);
            $this->builder->insertElement($chemicalAgent, 'ConcentrationAvg', $value['factS']);
            $this->builder->insertElement($chemicalAgent, 'NormValue', $value['pdkS']);
            $this->builder->insertElement($chemicalAgent, 'WorkingConditions', $value['asset']);
            $this->builder->insertElement($chemicalAgent, 'NormativeAct', $value['nd']);

            $this->builder->insertMeasuringPlace($chemicalAgent, $value, $devices, $stuff);
        }
    }
}

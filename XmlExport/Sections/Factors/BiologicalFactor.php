<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Биологические факторы.
 */
class BiologicalFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iABio'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $bioFactors = $this->builder->insertElement($factors, 'BiologicalFactors');
        $this->builder->insertElement($bioFactors, 'WorkingConditions', $rmData['iABio']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getWorkers();

        // Микроорганизмы-продуценты
        $bioMP = $rmData['aBioMP'];
        if (is_array($bioMP)) {
            foreach ($bioMP as $value) {
                $microorganism = $this->builder->insertElement($bioFactors, 'MicroorganismProducer');
                $this->builder->insertElement($microorganism, 'Kod', $value['code']);
                $this->builder->insertElement($microorganism, 'NormValue', $value['pdk']);
                $this->builder->insertElement($microorganism, 'WorkingConditions', $value['asset']);
                $this->builder->insertElement($microorganism, 'NormativeAct', $value['nd']);

                $this->builder->insertMeasuringPlace($microorganism, $value, $devices, $stuff);
            }
        }

        // Патогенные микроорганизмы
        $bioPM = $rmData['aBioPM'];
        if (is_array($bioPM)) {
            foreach ($bioPM as $value) {
                $microorganism = $this->builder->insertElement($bioFactors, 'MicroorganismPathogenic');
                $this->builder->insertElement($microorganism, 'Kod', $value['code']);
                $this->builder->insertElement($microorganism, 'WorkingConditions', $value['asset']);
                $this->builder->insertElement($microorganism, 'NormativeAct', $value['nd']);

                $this->builder->insertMeasuringPlace($microorganism, $value, array(), $stuff);
            }
        }
    }
}

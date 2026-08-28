<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Напряженность труда.
 */
class LabourIntensity extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iATennese'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $labourIntensity = $this->builder->insertElement($factors, 'LabourIntensity');
        $this->builder->insertElement($labourIntensity, 'WorkingConditions', $rmData['iATennese']);

        $data = $rmData['aATennese'];
        $this->builder->insertElement($labourIntensity, 'Date', $data['dTenneseDate']);

        $this->insertPunkt('1', $data, $labourIntensity, 'SignalDensity');
        $this->insertPunkt('2', $data, $labourIntensity, 'ObjectsUnderControl');
        $this->insertPunkt('3', $data, $labourIntensity, 'OpticalInstrumentUsage');
        $this->insertPunkt('4', $data, $labourIntensity, 'VocalLoad');
        $this->insertPunkt('5', $data, $labourIntensity, 'OperationMonotony');
        $this->insertPunkt('6', $data, $labourIntensity, 'RaptAttentionTime');
    }

    private function insertPunkt($razdel, $data, $parent, $name)
    {
        $child = $this->builder->insertElement($parent, $name);
        $this->builder->insertElement($child, 'Value', $data['aTenneseTotal'][$razdel]);
        $this->builder->insertElement($child, 'NormValueMin', $data['aTennesePDKMin'][$razdel]);
        $this->builder->insertElement($child, 'NormValueMax', $data['aTennesePDKMax'][$razdel]);
        $this->builder->insertElement($child, 'WorkingConditions', $data['aTenneseTotalAll'][$razdel]);
        $this->builder->insertElement($child, 'NormativeAct', $data['nd']);

        $stuff = $this->data->getWorkers();

        if ($name !== 'OperationMonotony' && $name !== 'ObjectsUnderControl') {
            $devices = $this->data->getDevice();
            $this->builder->insertToolsId($devices, $child);
        }

        $this->builder->insertStuffId($stuff, $child);
    }
}

<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Шум.
 */
class NoiseFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iANoise'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $noiseFactor = $this->builder->insertElement($factors, 'Noise');
        $this->builder->insertElement($noiseFactor, 'Value', $rmData['dEqNoise']);
        $this->builder->insertElement($noiseFactor, 'Uncertainty', $rmData['dSuspNoise']);

        $noiseData = $rmData['aNOISE'];
        if (isset($noiseData[0]['pdkM'])) {
            $this->builder->insertElement($noiseFactor, 'NormValue', $noiseData[0]['pdkM']);
        }
        $this->builder->insertElement($noiseFactor, 'WorkingConditions', $rmData['iANoise']);
        if (isset($noiseData[0]['nd'])) {
            $this->builder->insertElement($noiseFactor, 'NormativeAct', $noiseData[0]['nd']);
        }
    }
}

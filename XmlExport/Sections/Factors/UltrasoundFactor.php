<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Ультразвук.
 */
class UltrasoundFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAUltraNoise'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $ultrasound = $this->builder->insertElement($factors, 'Ultrasound');
        $this->builder->insertElement($ultrasound, 'WorkingConditions', $rmData['iAUltraNoise']);

        $ultraData = $rmData['aAUltraNoise'];
        if (isset($ultraData[0]['nd'])) {
            $this->builder->insertElement($ultrasound, 'NormativeAct', $ultraData[0]['nd']);
        }

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();

        if (is_array($ultraData)) {
            foreach ($ultraData as $value) {
                $measuringPlace = $this->builder->insertElement($ultrasound, 'MeasuringPlace');
                $this->builder->insertElement($measuringPlace, 'Name', $value['point']);
                $this->builder->insertElement($measuringPlace, 'Date', $value['dtControl']);
                $this->builder->insertElement($measuringPlace, 'TimeBehavior', '0');
                $this->builder->insertElement($measuringPlace, 'Duration', $value['pointTime']);
                $this->builder->insertElement($measuringPlace, 'FactorSource', $value['point']);
                $this->builder->insertElement($measuringPlace, 'WorkingConditions', $value['asset']);

                $octaves = $value['aOctave'];
                if (is_array($octaves)) {
                    foreach ($octaves as $key2 => $value2) {
                        $soundPressureOctave = $this->builder->insertElement($measuringPlace, 'SoundPressureOctave');
                        $this->builder->insertElement($soundPressureOctave, 'Octave', $key2);
                        $this->builder->insertElement($soundPressureOctave, 'Value', $value2['Value']);
                        $this->builder->insertElement($soundPressureOctave, 'NormValue', $value2['NormValue']);
                    }
                }

                $this->builder->insertToolsId($devices, $measuringPlace);
                $this->builder->insertStuffId($stuff, $measuringPlace);
            }
        }
    }
}

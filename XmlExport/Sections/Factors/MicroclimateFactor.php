<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Микроклимат.
 */
class MicroclimateFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAMicroclimat'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $microclimate = $this->builder->insertElement($factors, 'Microclimate');
        $this->builder->insertElement($microclimate, 'WorkingConditions', $rmData['iAMicroclimat']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();

        $microData = $rmData['aAMicroclimat'];
        if (!is_array($microData)) {
            return;
        }

        foreach ($microData as $value) {
            $measuringPlace = $this->builder->insertElement($microclimate, 'MeasuringPlace');
            $this->builder->insertElement($measuringPlace, 'Name', $value[0]['point']);
            $this->builder->insertElement($measuringPlace, 'Date', $value[0]['dtControl']);
            $this->builder->insertElement($measuringPlace, 'Duration', $value[0]['pointTime']);
            $this->builder->insertElement($measuringPlace, 'WorkingConditions', $value[0]['asset']);
            $this->builder->insertElement($measuringPlace, 'WorkCategory', $value[0]['pdk']);
            $this->builder->insertElement($measuringPlace, 'Posture', $value[0]['posture']);

            foreach ($value as $key2 => $value2) {
                if (strval($key2) === 'zoneAsset') {
                    continue;
                }

                $factorId = $value2['factorId'];

                if ($factorId === '2') {
                    $this->insertAirTemperature($measuringPlace, $value2, $devices, $stuff);
                } elseif ($factorId === '6') {
                    $this->insertAirSpeed($measuringPlace, $value2, $devices, $stuff);
                } elseif ($factorId === '5') {
                    $this->insertAirHumidity($measuringPlace, $value2, $devices, $stuff);
                } elseif ($factorId === '56') {
                    $this->insertThermalLoadIndex($measuringPlace, $value2, $devices, $stuff);
                } elseif ($factorId === '7') {
                    $this->insertHeatRadiationIntensity($measuringPlace, $value2, $devices, $stuff);
                }
            }
        }
    }

    private function insertAirTemperature($parent, $value, $devices, $stuff)
    {
        $airTemperature = $this->builder->insertElement($parent, 'AirTemperature');
        $this->builder->insertElement($airTemperature, 'Value', $value['fact']);
        $this->builder->insertElement($airTemperature, 'NormValueMin', $value['pdkMin']);
        $this->builder->insertElement($airTemperature, 'NormValueMax', $value['pdkMax']);
        $this->builder->insertElement($airTemperature, 'NormativeAct', $value['nd']);

        $height1 = $this->builder->insertElement($airTemperature, 'AirTemperatureHeight');
        $this->builder->insertElement($height1, 'Height', $value['h1']);
        $this->builder->insertElement($height1, 'Value', $value['fact']);

        $height2 = $this->builder->insertElement($airTemperature, 'AirTemperatureHeight');
        $this->builder->insertElement($height2, 'Height', $value['h2']);
        $this->builder->insertElement($height2, 'Value', $value['fact']);

        $this->builder->insertToolsId($devices, $airTemperature);
        $this->builder->insertStuffId($stuff, $airTemperature);
    }

    private function insertAirSpeed($parent, $value, $devices, $stuff)
    {
        $airSpeed = $this->builder->insertElement($parent, 'AirSpeed');
        $this->builder->insertElement($airSpeed, 'Value', $value['fact']);
        $this->builder->insertElement($airSpeed, 'NormValue', $value['pdkAirSpeed']);
        $this->builder->insertElement($airSpeed, 'NormativeAct', $value['nd']);

        $height1 = $this->builder->insertElement($airSpeed, 'AirSpeedHeight');
        $this->builder->insertElement($height1, 'Height', $value['h1']);
        $this->builder->insertElement($height1, 'Value', $value['fact']);

        $height2 = $this->builder->insertElement($airSpeed, 'AirSpeedHeight');
        $this->builder->insertElement($height2, 'Height', $value['h2']);
        $this->builder->insertElement($height2, 'Value', $value['fact']);

        $this->builder->insertToolsId($devices, $airSpeed);
        $this->builder->insertStuffId($stuff, $airSpeed);
    }

    private function insertAirHumidity($parent, $value, $devices, $stuff)
    {
        $airHumidity = $this->builder->insertElement($parent, 'AirHumidity');
        $this->builder->insertElement($airHumidity, 'Height', $value['h1']);
        $this->builder->insertElement($airHumidity, 'Value', $value['fact']);
        $this->builder->insertElement($airHumidity, 'NormValueMin', $value['pdkMinAH']);
        $this->builder->insertElement($airHumidity, 'NormValueMax', $value['pdkMaxAH']);
        $this->builder->insertElement($airHumidity, 'NormativeAct', $value['nd']);

        $this->builder->insertToolsId($devices, $airHumidity);
        $this->builder->insertStuffId($stuff, $airHumidity);
    }

    private function insertThermalLoadIndex($parent, $value, $devices, $stuff)
    {
        $thermalLoadIndex = $this->builder->insertElement($parent, 'ThermalLoadIndex');
        $this->builder->insertElement($thermalLoadIndex, 'Value', $value['fact']);
        $this->builder->insertElement($thermalLoadIndex, 'NormValue', $value['pdkTNS']);
        $this->builder->insertElement($thermalLoadIndex, 'WorkingConditions', $value['asset']);
        $this->builder->insertElement($thermalLoadIndex, 'NormativeAct', $value['nd']);

        $this->builder->insertToolsId($devices, $thermalLoadIndex);
        $this->builder->insertStuffId($stuff, $thermalLoadIndex);
    }

    private function insertHeatRadiationIntensity($parent, $value, $devices, $stuff)
    {
        $heatRadiation = $this->builder->insertElement($parent, 'HeatRadiationIntensity');
        $this->builder->insertElement($heatRadiation, 'Value', $value['fact']);
        $this->builder->insertElement($heatRadiation, 'FactorSource', $value['point']);
        $this->builder->insertElement($heatRadiation, 'NormValue', $value['pdkHRI']);
        $this->builder->insertElement($heatRadiation, 'WorkingConditions', $value['asset']);
        $this->builder->insertElement($heatRadiation, 'NormativeAct', $value['nd']);

        $height1 = $this->builder->insertElement($heatRadiation, 'HeatRadiationIntensityHeight');
        $this->builder->insertElement($height1, 'Height', $value['h1']);
        $this->builder->insertElement($height1, 'Value', $value['fact']);

        $height2 = $this->builder->insertElement($heatRadiation, 'HeatRadiationIntensityHeight');
        $this->builder->insertElement($height2, 'Height', $value['h2']);
        $this->builder->insertElement($height2, 'Value', $value['fact']);

        $height3 = $this->builder->insertElement($heatRadiation, 'HeatRadiationIntensityHeight');
        $this->builder->insertElement($height3, 'Height', $value['h3']);
        $this->builder->insertElement($height3, 'Value', $value['fact']);

        $this->builder->insertToolsId($devices, $heatRadiation);
        $this->builder->insertStuffId($stuff, $heatRadiation);
    }
}

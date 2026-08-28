<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Вибрация (локальная и общая).
 */
class VibrationFactor extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAVibroL'] !== '-' || $rmData['iAVibroO'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        $this->insertLocal($factors, $rmData);
        $this->insertOverall($factors, $rmData);
    }

    /**
     * Локальная вибрация.
     */
    public function insertLocal($factors, $rmData)
    {
        if ($rmData['iAVibroL'] === '-') {
            return;
        }

        $vibration = $this->builder->insertElement($factors, 'LocalVibration');
        $this->builder->insertElement($vibration, 'ValueX', $rmData['dEqVibroLX']);
        $this->builder->insertElement($vibration, 'ValueY', $rmData['dEqVibroLY']);
        $this->builder->insertElement($vibration, 'ValueZ', $rmData['dEqVibroLZ']);

        $vibroData = $rmData['aAVibroL'];
        if (isset($vibroData[0]['pdkX'])) {
            $this->builder->insertElement($vibration, 'NormValue', $vibroData[0]['pdkX']);
        }
        $this->builder->insertElement($vibration, 'WorkingConditions', $rmData['iAVibroL']);
        if (isset($vibroData[0]['nd'])) {
            $this->builder->insertElement($vibration, 'NormativeAct', $vibroData[0]['nd']);
        }

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();

        if (is_array($vibroData)) {
            foreach ($vibroData as $value) {
                $measuringPlace = $this->builder->insertElement($vibration, 'MeasuringPlace');
                $this->builder->insertElement($measuringPlace, 'Name', $value['point']);
                $this->builder->insertElement($measuringPlace, 'Date', $value['dtControl']);
                $this->builder->insertElement($measuringPlace, 'TimeBehavior', '1');
                $this->builder->insertElement($measuringPlace, 'ValueX', $value['factX']);
                $this->builder->insertElement($measuringPlace, 'ValueY', $value['factY']);
                $this->builder->insertElement($measuringPlace, 'ValueZ', $value['factZ']);
                $this->builder->insertElement($measuringPlace, 'Duration', $value['pointTime']);
                $this->builder->insertElement($measuringPlace, 'FactorSource', $value['point']);
                $this->builder->insertToolsId($devices, $measuringPlace);
                $this->builder->insertStuffId($stuff, $measuringPlace);
            }
        }
    }

    /**
     * Общая вибрация.
     */
    public function insertOverall($factors, $rmData)
    {
        if ($rmData['iAVibroO'] === '-') {
            return;
        }

        $vibration = $this->builder->insertElement($factors, 'OverallVibration');
        $this->builder->insertElement($vibration, 'ValueX', $rmData['dEqVibroOX']);
        $this->builder->insertElement($vibration, 'ValueY', $rmData['dEqVibroOY']);
        $this->builder->insertElement($vibration, 'ValueZ', $rmData['dEqVibroOZ']);

        $vibroData = $rmData['aAVibroO'];
        if (isset($vibroData[0]['pdkX'])) {
            $this->builder->insertElement($vibration, 'NormValueXY', $vibroData[0]['pdkX']);
        }
        if (isset($vibroData[0]['pdkZ'])) {
            $this->builder->insertElement($vibration, 'NormValueZ', $vibroData[0]['pdkZ']);
        }
        $this->builder->insertElement($vibration, 'WorkingConditions', $rmData['iAVibroO']);
        if (isset($vibroData[0]['nd'])) {
            $this->builder->insertElement($vibration, 'NormativeAct', $vibroData[0]['nd']);
        }

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();

        if (is_array($vibroData)) {
            foreach ($vibroData as $value) {
                $measuringPlace = $this->builder->insertElement($vibration, 'MeasuringPlace');
                $this->builder->insertElement($measuringPlace, 'Name', $value['point']);
                $this->builder->insertElement($measuringPlace, 'Date', $value['dtControl']);
                $this->builder->insertElement($measuringPlace, 'TimeBehavior', '1');
                $this->builder->insertElement($measuringPlace, 'ValueX', $value['factX']);
                $this->builder->insertElement($measuringPlace, 'ValueY', $value['factY']);
                $this->builder->insertElement($measuringPlace, 'ValueZ', $value['factZ']);
                $this->builder->insertElement($measuringPlace, 'Duration', $value['pointTime']);
                $this->builder->insertElement($measuringPlace, 'FactorSource', $value['point']);
                $this->builder->insertToolsId($devices, $measuringPlace);
                $this->builder->insertStuffId($stuff, $measuringPlace);
            }
        }
    }
}

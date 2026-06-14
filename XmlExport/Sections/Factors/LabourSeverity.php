<?php

namespace XmlExport\Sections\Factors;

use DOMElement;

/**
 * Тяжесть труда.
 */
class LabourSeverity extends FactorSection
{
    protected function shouldInsert($rmData)
    {
        return $rmData['iAHeavyW'] !== '-' && $rmData['iAHeavyM'] !== '-';
    }

    public function insert($factors, $rmData)
    {
        if (!$this->shouldInsert($rmData)) {
            return;
        }

        $labourSeverity = $this->builder->insertElement($factors, 'LabourSeverity');
        $this->builder->insertElement($labourSeverity, 'WorkingConditions', $rmData['iAHeavy']);

        $data = $rmData['aAHeavy'];
        $this->builder->insertElement($labourSeverity, 'Date', $data['dHeavyDate']);

        $this->insertPhysicalDynamicLoad($labourSeverity, $data);
        $this->insertWeightLifting($labourSeverity, $data);
        $this->insertMotorStereotypy($labourSeverity, $data);
        $this->insertStaticalLoad($labourSeverity, $data);
        $this->insertWorkingPose($labourSeverity, $data);
        $this->insertBodyBending($labourSeverity, $data);
        $this->insertTravelling($labourSeverity, $data);
    }

    private function insertPunktPhysical($razdel, $data, $parent, $sex, $name)
    {
        $totalKey = 'aHeavyTotal' . $sex;
        if (!isset($data[$totalKey][$razdel]) || $data[$totalKey][$razdel] === '-') {
            return;
        }

        $child = $this->builder->insertElement($parent, $name);
        $this->builder->insertElement($child, 'Value', $data['aHeavyTotal'][$razdel]);
        $this->builder->insertElement($child, 'NormValue', $data['aHeavyTotal' . $sex . 'PDK'][$razdel]);
        $this->builder->insertElement($child, 'WorkingConditions', $data[$totalKey][$razdel]);
        $this->builder->insertElement($child, 'NormativeAct', $data['nd']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();
        $this->builder->insertToolsId($devices, $child);
        $this->builder->insertStuffId($stuff, $child);
    }

    private function insertPhysicalDynamicLoad($parent, $data)
    {
        $physicalDynamicLoad = $this->builder->insertElement($parent, 'PhysicalDynamicLoad');

        $this->insertPunktPhysical('11', $data, $physicalDynamicLoad, 'M', 'WeightMovementLess1mMale');
        $this->insertPunktPhysical('11', $data, $physicalDynamicLoad, 'W', 'WeightMovementLess1mFemale');
        $this->insertPunktPhysical('12', $data, $physicalDynamicLoad, 'M', 'WeightMovement1to5mMale');
        $this->insertPunktPhysical('12', $data, $physicalDynamicLoad, 'W', 'WeightMovement1to5mFemale');
        $this->insertPunktPhysical('13', $data, $physicalDynamicLoad, 'M', 'WeightMovementMore5mMale');
        $this->insertPunktPhysical('13', $data, $physicalDynamicLoad, 'W', 'WeightMovementMore5mFemale');
    }

    private function insertWeightLifting($parent, $data)
    {
        $weightLifting = $this->builder->insertElement($parent, 'WeightLifting');

        $this->insertPunktPhysical('21', $data, $weightLifting, 'M', 'SingleWeightLiftingMale');
        $this->insertPunktPhysical('21', $data, $weightLifting, 'W', 'SingleWeightLiftingFemale');
        $this->insertPunktPhysical('22', $data, $weightLifting, 'M', 'ConstantWeightLiftingMale');
        $this->insertPunktPhysical('22', $data, $weightLifting, 'W', 'ConstantWeightLiftingFemale');
        $this->insertPunktPhysical('23', $data, $weightLifting, 'M', 'TotalWeightLiftingOffWorkSurfaceMale');
        $this->insertPunktPhysical('23', $data, $weightLifting, 'W', 'TotalWeightLiftingOffWorkSurfaceFemale');
        $this->insertPunktPhysical('24', $data, $weightLifting, 'M', 'TotalWeightLiftingOffFlorMale');
        $this->insertPunktPhysical('24', $data, $weightLifting, 'W', 'TotalWeightLiftingOffFlorFemale');
    }

    private function insertMotorStereotypy($parent, $data)
    {
        $motorStereotypy = $this->builder->insertElement($parent, 'MotorStereotypy');

        $this->insertPunktPhysical('31', $data, $motorStereotypy, 'M', 'MotorStereotypyLocalLoad');
        $this->insertPunktPhysical('32', $data, $motorStereotypy, 'M', 'MotorStereotypyRegionalLoad');
    }

    private function insertStaticalLoad($parent, $data)
    {
        $staticalLoad = $this->builder->insertElement($parent, 'StaticalLoad');

        $this->insertPunktPhysical('41', $data, $staticalLoad, 'M', 'StaticalLoadOneHandMale');
        $this->insertPunktPhysical('41', $data, $staticalLoad, 'W', 'StaticalLoadOneHandFemale');
        $this->insertPunktPhysical('42', $data, $staticalLoad, 'M', 'StaticalLoadTwoHandsMale');
        $this->insertPunktPhysical('42', $data, $staticalLoad, 'W', 'StaticalLoadTwoHandsFemale');
        $this->insertPunktPhysical('43', $data, $staticalLoad, 'M', 'StaticalLoadBodyMale');
        $this->insertPunktPhysical('43', $data, $staticalLoad, 'W', 'StaticalLoadBodyFemale');
    }

    private function insertWorkingPose($parent, $data)
    {
        $workingPose = $this->builder->insertElement($parent, 'WorkingPose');
        $this->builder->insertElement($workingPose, 'Value', $data['aHeavyTotal']['51']);
        $this->builder->insertElement($workingPose, 'WorkingConditions', $data['aHeavyTotalM']['51']);
        $this->builder->insertElement($workingPose, 'NormativeAct', $data['nd']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();
        $this->builder->insertToolsId($devices, $workingPose);
        $this->builder->insertStuffId($stuff, $workingPose);
    }

    private function insertBodyBending($parent, $data)
    {
        $bodyBending = $this->builder->insertElement($parent, 'BodyBending');
        $this->builder->insertElement($bodyBending, 'Value', $data['aHeavyTotal']['61']);
        $this->builder->insertElement($bodyBending, 'NormValue', $data['aHeavyTotalMPDK']['61']);
        $this->builder->insertElement($bodyBending, 'WorkingConditions', $data['aHeavyTotalM']['61']);
        $this->builder->insertElement($bodyBending, 'NormativeAct', $data['nd']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();
        $this->builder->insertToolsId($devices, $bodyBending);
        $this->builder->insertStuffId($stuff, $bodyBending);
    }

    private function insertTravelling($parent, $data)
    {
        $travelling = $this->builder->insertElement($parent, 'Travelling');

        $horizontalTravelling = $this->builder->insertElement($travelling, 'HorizontalTravelling');
        $this->builder->insertElement($horizontalTravelling, 'Value', $data['aHeavyTotal']['71']);
        $this->builder->insertElement($horizontalTravelling, 'NormValue', $data['aHeavyTotalMPDK']['71']);
        $this->builder->insertElement($horizontalTravelling, 'WorkingConditions', $data['aHeavyTotalM']['71']);
        $this->builder->insertElement($horizontalTravelling, 'NormativeAct', $data['nd']);

        $devices = $this->data->getDevice();
        $stuff = $this->data->getStuff();
        $this->builder->insertToolsId($devices, $horizontalTravelling);
        $this->builder->insertStuffId($stuff, $horizontalTravelling);

        $verticalTravelling = $this->builder->insertElement($travelling, 'VerticalTravelling');
        $this->builder->insertElement($verticalTravelling, 'Value', $data['aHeavyTotal']['72']);
        $this->builder->insertElement($verticalTravelling, 'NormValue', $data['aHeavyTotalMPDK']['72']);
        $this->builder->insertElement($verticalTravelling, 'WorkingConditions', $data['aHeavyTotalM']['72']);
        $this->builder->insertElement($verticalTravelling, 'NormativeAct', $data['nd']);

        $this->builder->insertToolsId($devices, $verticalTravelling);
        $this->builder->insertStuffId($stuff, $verticalTravelling);
    }
}

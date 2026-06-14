<?php

namespace XmlExport\Sections;

use DOMElement;
use XmlExport\XmlDocumentBuilder;

/**
 * Секция гарантий и компенсаций.
 */
class CompensationSection
{
    /** @var XmlDocumentBuilder */
    private $builder;

    /**
     * @param XmlDocumentBuilder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * Вставить все компенсации.
     *
     * @param DOMElement $parent
     * @param array $rmData
     * @return void
     */
    public function insert($parent, $rmData)
    {
        $compensations = $this->builder->insertElement($parent, 'Compensations');

        $this->insertCompensationItem($compensations, 'PayIncrease',
            $rmData['sCompFactSurcharge'], $rmData['iCompSurcharge'], $rmData['sCompBaseSurcharge']);

        $this->insertCompensationItem($compensations, 'CompensatoryLeave',
            $rmData['sCompFactVacation'], $rmData['iCompVacation'], $rmData['sCompBaseVacation']);

        $this->insertCompensationItem($compensations, 'HalfDay',
            $rmData['sCompFactShortWorkDay'], $rmData['iCompShortWorkDay'], $rmData['sCompBaseShortWorkDay']);

        $this->insertCompensationItem($compensations, 'Milk',
            $rmData['sCompFactMilk'], $rmData['iCompMilk'], $rmData['sCompBaseMilk']);

        $this->insertCompensationItem($compensations, 'DieteticTherapy',
            $rmData['sCompFactFood'], $rmData['iCompFood'], $rmData['sCompBaseFood']);

        $this->insertCompensationItem($compensations, 'EarlyRetirement',
            $rmData['sCompFactPension'], $rmData['iCompPension'], $rmData['sCompBasePension']);

        $this->insertCompensationItem($compensations, 'MedicalInspection',
            $rmData['sCompFactPhysical'], $rmData['iCompPhysical'], $rmData['sCompBasePhysical']);
    }

    /**
     * Вставить один элемент компенсации.
     *
     * @param DOMElement $parent
     * @param string $name
     * @param string $actual
     * @param string $need
     * @param string $reason
     * @return void
     */
    private function insertCompensationItem($parent, $name, $actual, $need, $reason)
    {
        $item = $this->builder->insertElement($parent, $name);
        $this->builder->insertElement($item, 'ActualAvailability', $actual);
        $this->builder->insertElement($item, 'NeedToEstablish', $need);
        $this->builder->insertElement($item, 'Reason', $reason);
    }
}

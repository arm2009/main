<?php

namespace XmlExport\Sections;

use DOMElement;
use XmlExport\XmlDocumentBuilder;
use XmlExport\XmlDataProvider;
use XmlExport\Sections\RecommendationSection;
use XmlExport\Sections\CompensationSection;
use XmlExport\Sections\Factors\ChemicalFactor;
use XmlExport\Sections\Factors\BiologicalFactor;
use XmlExport\Sections\Factors\AerosolFactor;
use XmlExport\Sections\Factors\NoiseFactor;
use XmlExport\Sections\Factors\InfrasoundFactor;
use XmlExport\Sections\Factors\UltrasoundFactor;
use XmlExport\Sections\Factors\VibrationFactor;
use XmlExport\Sections\Factors\MicroclimateFactor;
use XmlExport\Sections\Factors\LightFactor;
use XmlExport\Sections\Factors\LabourSeverity;
use XmlExport\Sections\Factors\LabourIntensity;

/**
 * Секция рабочих мест (WorkPlaceDeclared + WorkPlaceAttested).
 */
class WorkPlaceSection
{
    /** @var XmlDocumentBuilder */
    private $builder;

    /** @var XmlDataProvider */
    private $data;

    /** @var RecommendationSection */
    private $recommendationSection;

    /** @var CompensationSection */
    private $compensationSection;

    /** @var array */
    private $factorSections;

    /**
     * @param XmlDocumentBuilder $builder
     * @param XmlDataProvider $data
     */
    public function __construct($builder, $data)
    {
        $this->builder = $builder;
        $this->data = $data;

        $this->recommendationSection = new RecommendationSection($builder);
        $this->compensationSection = new CompensationSection($builder);

        $this->factorSections = array(
            new ChemicalFactor($builder, $data),
            new BiologicalFactor($builder, $data),
            new AerosolFactor($builder, $data),
            new NoiseFactor($builder, $data),
            new InfrasoundFactor($builder, $data),
            new UltrasoundFactor($builder, $data),
            new VibrationFactor($builder, $data),
            new MicroclimateFactor($builder, $data),
            new LightFactor($builder, $data),
            new LabourSeverity($builder, $data),
            new LabourIntensity($builder, $data),
        );
    }

    /**
     * Вставить все рабочие места.
     *
     * @param DOMElement $enterprise
     * @return void
     */
    public function insert($enterprise)
    {
        $rms = $this->data->getRm();

        // Сначала неаттестованные (Declared)
        foreach ($rms as $value) {
            if (!$this->isAttestedPlace($value)) {
                $workPlaceDeclared = $this->builder->insertElement($enterprise, 'WorkPlaceDeclared');
                $this->insertWorkPlaceDeclared($workPlaceDeclared, $value);
            }
        }

        // Потом аттестованные (Attested)
        foreach ($rms as $value) {
            if ($this->isAttestedPlace($value)) {
                $workPlaceAttested = $this->builder->insertElement($enterprise, 'WorkPlaceAttested');
                $this->insertWorkPlaceAttested($workPlaceAttested, $value);
            }
        }
    }

    /**
     * Проверить, аттестовано ли рабочее место.
     *
     * @param array $rmData
     * @return bool
     */
    private function isAttestedPlace($rmData)
    {
        return $rmData['iAChem'] !== '-' ||
            $rmData['iABio'] !== '-' ||
            $rmData['iAAPFD'] !== '-' ||
            $rmData['iANoise'] !== '-' ||
            $rmData['iAInfraNoise'] !== '-' ||
            $rmData['iAUltraNoise'] !== '-' ||
            $rmData['iAVibroO'] !== '-' ||
            $rmData['iAVibroL'] !== '-' ||
            $rmData['iANoIon'] !== '-' ||
            $rmData['iAIon'] !== '-' ||
            $rmData['iAMicroclimat'] !== '-' ||
            $rmData['iALight'] !== '-' ||
            $rmData['iAHeavy'] !== '-' ||
            $rmData['iAHeavyW'] !== '-' ||
            $rmData['iAHeavyM'] !== '-' ||
            $rmData['iATennese'] !== '-';
    }

    /**
     * Вставить неаттестованное рабочее место.
     *
     * @param DOMElement $workPlaceDeclared
     * @param array $rmData
     * @return void
     */
    private function insertWorkPlaceDeclared($workPlaceDeclared, $rmData)
    {
        $this->builder->insertElement($workPlaceDeclared, 'Id', $rmData['id']);
        $this->builder->insertElement($workPlaceDeclared, 'Position', $rmData['sName']);
        $this->builder->insertElement($workPlaceDeclared, 'SubUnit', $rmData['Division']);
        $this->builder->insertElement($workPlaceDeclared, 'Profession', $rmData['sOk']);
        $this->builder->insertElement($workPlaceDeclared, 'WorkersQuantity', $rmData['iCount']);
        $this->builder->insertElement($workPlaceDeclared, 'WomansQuantity', $rmData['iCountWoman']);
        $this->builder->insertElement($workPlaceDeclared, 'TeenagersQuantity', $rmData['iCountYouth']);
        $this->builder->insertElement($workPlaceDeclared, 'InvalidsQuantity', $rmData['iCountDisabled']);
        $this->builder->insertElement($workPlaceDeclared, 'ExpertConclusion', $this->data->getExpertConclusion());

        $snils = $rmData['sSnils'];
        if (is_array($snils)) {
            foreach ($snils as $value) {
                $this->builder->insertElement($workPlaceDeclared, 'Snils', $value);
            }
        }
    }

    /**
     * Вставить аттестованное рабочее место.
     *
     * @param DOMElement $workPlaceAttested
     * @param array $rmData
     * @return void
     */
    private function insertWorkPlaceAttested($workPlaceAttested, $rmData)
    {
        $this->builder->insertElement($workPlaceAttested, 'SOUTCardNumber', $rmData['iNumber']);
        $this->builder->insertElement($workPlaceAttested, 'SheetDate', $rmData['dCreateDate']);
        $this->builder->insertElement($workPlaceAttested, 'Id', $rmData['id']);
        $this->builder->insertElement($workPlaceAttested, 'Position', $rmData['sName']);
        $this->builder->insertElement($workPlaceAttested, 'Profession', $rmData['sOk']);
        $this->builder->insertElement($workPlaceAttested, 'ETKS_EKS_Issue', $rmData['sETKS']);
        $this->builder->insertElement($workPlaceAttested, 'SubUnit', $rmData['Division']);
        $this->builder->insertElement($workPlaceAttested, 'WorkersQuantity', $rmData['iCount']);
        $this->builder->insertElement($workPlaceAttested, 'WomansQuantity', $rmData['iCountWoman']);
        $this->builder->insertElement($workPlaceAttested, 'TeenagersQuantity', $rmData['iCountYouth']);
        $this->builder->insertElement($workPlaceAttested, 'InvalidsQuantity', $rmData['iCountDisabled']);
        $this->builder->insertElement($workPlaceAttested, 'RawMaterials', $rmData['Materials']);
        $this->builder->insertElement($workPlaceAttested, 'Equipment', $rmData['Equipment']);
        $this->builder->insertElement($workPlaceAttested, 'WorkingConditions', $rmData['iATotal']);

        // СНИЛС
        $snils = $rmData['sSnils'];
        if (is_array($snils)) {
            foreach ($snils as $value) {
                $this->builder->insertElement($workPlaceAttested, 'Snils', $value);
            }
        }

        // Рекомендации
        $recommendations = $rmData['Recomendations'];
        if (is_array($recommendations)) {
            $this->recommendationSection->insert($workPlaceAttested, $recommendations);
        }

        // Компенсации
        $this->compensationSection->insert($workPlaceAttested, $rmData);

        // Факторы
        $factors = $this->builder->insertElement($workPlaceAttested, 'Factors');
        foreach ($this->factorSections as $factorSection) {
            $factorSection->insert($factors, $rmData);
        }
    }
}

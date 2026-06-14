<?php

namespace XmlExport\Sections;

use DOMElement;
use XmlExport\XmlDocumentBuilder;
use XmlExport\XmlDataProvider;

/**
 * Секция данных об аттестации: дата, кол-во мест, регион, комиссия, организация.
 */
class AttestationSection
{
    /** @var XmlDocumentBuilder */
    private $builder;

    /** @var XmlDataProvider */
    private $data;

    /**
     * @param XmlDocumentBuilder $builder
     * @param XmlDataProvider $data
     */
    public function __construct($builder, $data)
    {
        $this->builder = $builder;
        $this->data = $data;
    }

    /**
     * Вставить данные об аттестации.
     *
     * @param DOMElement $attestation
     * @return void
     */
    public function insert($attestation)
    {
        $this->builder->insertElement($attestation, 'Date', $this->data->getReportDate());
        $this->builder->insertElement($attestation, 'WorkPlacesQuantity', $this->data->getRmCountTotal());
        $this->builder->insertElement($attestation, 'WorkPlacesAttested', $this->data->getRmCount());
        $this->builder->insertElement($attestation, 'Region', $this->data->getRegion());

        $this->insertCommittee($attestation);
        $this->insertAttestationOrganization($attestation);
    }

    /**
     * Вставить всех членов комиссии.
     *
     * @param DOMElement $attestation
     * @return void
     */
    private function insertCommittee($attestation)
    {
        $committee = $this->builder->insertElement($attestation, 'Committee');
        $committeeArray = $this->data->getComitee();

        if (empty($committeeArray)) {
            return;
        }

        // Председатель
        $chairman = $this->builder->insertElement($committee, 'Chairman');
        $this->insertCommitteeMemberData($chairman, $committeeArray[0]);

        // Члены комиссии
        for ($i = 1; $i < count($committeeArray); $i++) {
            $member = $this->builder->insertElement($committee, 'Member');
            $this->insertCommitteeMemberData($member, $committeeArray[$i]);
        }
    }

    /**
     * Вставить данные члена комиссии.
     *
     * @param DOMElement $element
     * @param array $memberData
     * @return void
     */
    private function insertCommitteeMemberData($element, $memberData)
    {
        $nameParts = explode(' ', $memberData['sName']);
        $lastName = isset($nameParts[0]) ? $nameParts[0] : '';
        $firstName = isset($nameParts[1]) ? $nameParts[1] : '';

        $this->builder->insertCommitteeMember($element, $lastName, $firstName, $memberData['sPost']);
    }

    /**
     * Вставить организацию, проводившую СОУТ.
     *
     * @param DOMElement $attestation
     * @return void
     */
    private function insertAttestationOrganization($attestation)
    {
        $org = $this->builder->insertElement($attestation, 'AttestationOrganization');
        $dataAtt = $this->data->getAttestationOrganisation();

        $this->builder->insertElement($org, 'Name', $dataAtt['sOrgName']);
        $this->builder->insertElement($org, 'RegistrationNumber', $dataAtt['sOrgRegNum']);
        $this->builder->insertElement($org, 'RegistrationDate', $dataAtt['sOrgDate']);

        $ogrn = $dataAtt['sOrgOgrn'];
        if (!preg_match('/\d{13}|\d{15}/', $ogrn)) {
            $ogrn = '0000000000000';
        }
        $this->builder->insertElement($org, 'OGRN', $ogrn);
        $this->builder->insertElement($org, 'INN', $dataAtt['sOrgInn']);
        $this->builder->insertElement($org, 'Address', $dataAtt['sOrgAdress']);
        $this->builder->insertElement($org, 'Phone', $dataAtt['sOrgPhone']);
        $this->builder->insertElement($org, 'E-mail', $dataAtt['sName']);
        $this->builder->insertElement($org, 'Director', $dataAtt['sFirstFaceName']);

        // Работники (эксперты + сотрудники)
        $workers = $this->builder->insertElement($org, 'Workers');
        $this->insertExperts($workers);
        $this->insertStaff($workers);

        // Лаборатория
        $laboratory = $this->builder->insertElement($org, 'Laboratory');
        $this->insertLaboratory($laboratory, $dataAtt);
    }

    /**
     * Вставить экспертов.
     *
     * @param DOMElement $workers
     * @return void
     */
    private function insertExperts($workers)
    {
        $experts = $this->data->getExpert();
        foreach ($experts as $value) {
            $newExpert = $this->builder->insertElementWithId($workers, 'Expert', 'D' . $value['id']);
            $nameParts = explode(' ', $value['sName']);
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            $firstName = isset($nameParts[0]) ? $nameParts[0] : '';
            $this->builder->insertCommitteeMember($newExpert, $lastName, $firstName, $value['sPost']);
        }
    }

    /**
     * Вставить сотрудников.
     *
     * @param DOMElement $workers
     * @return void
     */
    private function insertStaff($workers)
    {
        $stuff = $this->data->getStuff();
        foreach ($stuff as $value) {
            $newStuff = $this->builder->insertElementWithId($workers, 'Expert', 'D' . $value['id']);
            $nameParts = explode(' ', $value['sName']);
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
            $firstName = isset($nameParts[0]) ? $nameParts[0] : '';
            $this->builder->insertCommitteeMember($newStuff, $lastName, $firstName, $value['sPost']);
        }
    }

    /**
     * Вставить данные лаборатории.
     *
     * @param DOMElement $laboratory
     * @param array $dataAtt
     * @return void
     */
    private function insertLaboratory($laboratory, $dataAtt)
    {
        $accredit = $this->data->getAttestationAccreditMy();

        $this->builder->insertElement($laboratory, 'Name', $dataAtt['sOrgName']);
        $this->builder->insertElement($laboratory, 'CertificateNumber', $accredit['sName']);
        $this->builder->insertElement($laboratory, 'CertificateIssueDate', $accredit['dDateCreate']);
        $this->builder->insertElement($laboratory, 'CertificateExpiryDate', $accredit['dDateFinish']);

        $devices = $this->data->getDevice();
        foreach ($devices as $value) {
            $tool = $this->builder->insertElementWithId($laboratory, 'MeasuringTool', 'D' . $value['id']);
            $this->builder->insertElement($tool, 'SerialNumber', $value['sFactoryNum']);
            $this->builder->insertElement($tool, 'CalibrationExpiryDate', $value['dCheckDate']);
            $this->builder->insertElement($tool, 'Name', $value['sName']);
            $this->builder->insertElement($tool, 'FundNumber', $value['sReestrNum']);
        }
    }
}

<?php

namespace XmlExport;

/**
 * Адаптер для работы с xmlData.
 * Предоставляет единый интерфейс получения данных.
 */
class XmlDataProvider
{
    /** @var \xmlData */
    private $xmlData;

    /**
     * @param int $idGroup
     */
    public function __construct($idGroup)
    {
        $this->xmlData = new \xmlData($idGroup);
    }

    /**
     * Получить оригинальный объект xmlData.
     *
     * @return \xmlData
     */
    public function getXmlData()
    {
        return $this->xmlData;
    }

    /**
     * @return string
     */
    public function getReportDate()
    {
        return $this->xmlData->getReportDate();
    }

    /**
     * @return int
     */
    public function getRmCountTotal()
    {
        return $this->xmlData->getRmCountTotal();
    }

    /**
     * @return int
     */
    public function getRmCount()
    {
        return $this->xmlData->getRmCount();
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->xmlData->getRegion();
    }

    /**
     * @return array
     */
    public function getComitee()
    {
        return $this->xmlData->getComitee();
    }

    /**
     * @return array
     */
    public function getAttestationOrganisation()
    {
        return $this->xmlData->getAttestationOrganisation();
    }

    /**
     * @return array
     */
    public function getAttestationAccreditMy()
    {
        return $this->xmlData->getAttestationAccreditMy();
    }

    /**
     * @return array
     */
    public function getExpert()
    {
        return $this->xmlData->getExpert();
    }

    /**
     * @return array
     */
    public function getStuff()
    {
        return $this->xmlData->getStuff();
    }

    /**
     * @return array
     */
    public function getDevice()
    {
        return $this->xmlData->getDevice();
    }

    /**
     * @return array
     */
    public function getDataGroup()
    {
        return $this->xmlData->getDataGroup();
    }

    /**
     * @return array
     */
    public function getRm()
    {
        return $this->xmlData->getRm();
    }

    /**
     * @return string
     */
    public function getExpertConclusion()
    {
        return $this->xmlData->getExpertConclusion();
    }
}

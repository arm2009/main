<?php

namespace XmlExport\Sections;

use DOMElement;
use XmlExport\XmlDocumentBuilder;
use XmlExport\XmlDataProvider;

/**
 * Секция данных о предприятии, где проводилась СОУТ.
 */
class EnterpriseSection
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
     * Вставить данные о предприятии.
     *
     * @param DOMElement $enterprise
     * @return void
     */
    public function insert($enterprise)
    {
        $dataGroup = $this->data->getDataGroup();

        $this->builder->insertElement($enterprise, 'Name', $dataGroup['sFullName']);

        $ogrn = $dataGroup['sOgrn'];
        if (!preg_match('/\d{13}|\d{15}/', $ogrn)) {
            $ogrn = '0000000000000';
        }
        $this->builder->insertElement($enterprise, 'OGRN', $ogrn);

        $inn = $dataGroup['sInn'];
        if (!preg_match('/\d{10}|\d{12}/', $inn)) {
            $inn = '0000000000';
        }
        $this->builder->insertElement($enterprise, 'INN', $inn);

        $okpo = $dataGroup['sOkpo'];
        if (!preg_match('/\d{8}|\d{10}/', $okpo)) {
            $okpo = '00000000';
        }
        $this->builder->insertElement($enterprise, 'OKPO', $okpo);

        $okogu = $dataGroup['sOkogu'];
        if (!preg_match('/\d{5}|\d{7}/', $okogu)) {
            $okogu = '00000';
        }
        $this->builder->insertElement($enterprise, 'OKOGU', $okogu);

        // ОКВЭД
        $okved = $this->builder->insertElement($enterprise, 'OKVED');
        // Разбиваем по запятой или точке с запятой с возможными пробелами
        $okvedCodes = preg_split('/[;,]\s*/', trim($dataGroup['sOkved']));
        foreach ($okvedCodes as $value) {
            $code = trim($value);
            if (!preg_match('/^\d{2}$|^\d{2}\.\d{1}$|^\d{2}\.\d{2}$|^\d{2}\.\d{2}\.\d{1}$|^\d{2}\.\d{2}\.\d{2}$/', $code)) {
                $code = '00';
            }
            $this->builder->insertElement($okved, 'Kod', $code);
        }

        $okato = $dataGroup['sOkato'];
        if (!preg_match('/\d{2}|\d{5}|\d{8}|\d{11}/', $okato)) {
            $okato = '00';
        }
        $this->builder->insertElement($enterprise, 'OKATO', $okato);
        $this->builder->insertElement($enterprise, 'DeJureAddress', $dataGroup['sPlace']);
        $this->builder->insertElement($enterprise, 'PostAddress', $dataGroup['sPlace']);
        $this->builder->insertElement($enterprise, 'E-mail', $dataGroup['sEmail']);
        $this->builder->insertElement($enterprise, 'Director', $dataGroup['sNameDirector']);
    }
}

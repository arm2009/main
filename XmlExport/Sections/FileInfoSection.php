<?php

namespace XmlExport\Sections;

use DOMElement;
use XmlExport\XmlDocumentBuilder;

/**
 * Секция FileInfo — информация о файле экспорта.
 */
class FileInfoSection
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
     * Вставить информацию о файле.
     *
     * @param DOMElement $root
     * @return void
     */
    public function insert($root)
    {
        $fileInfo = $this->builder->insertElement($root, 'FileInfo');

        $this->builder->insertElement($fileInfo, 'SchemeVersion', '1.0');
        $this->builder->insertElement($fileInfo, 'DataSource', 'отсутствует');
        $this->builder->insertElement($fileInfo, 'CreatedDate', date("Y-m-d"));
        $this->builder->insertElement($fileInfo, 'DateSent', date("Y-m-d"));
    }
}

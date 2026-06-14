<?php

namespace XmlExport\Sections\Factors;

use DOMElement;
use XmlExport\XmlDocumentBuilder;
use XmlExport\XmlDataProvider;

/**
 * Базовый абстрактный класс для всех факторов.
 */
abstract class FactorSection
{
    /** @var XmlDocumentBuilder */
    protected $builder;

    /** @var XmlDataProvider */
    protected $data;

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
     * Вставить фактор в родительский элемент.
     *
     * @param DOMElement $factors
     * @param array $rmData
     * @return void
     */
    abstract public function insert($factors, $rmData);

    /**
     * Проверить, нужно ли вставлять фактор.
     *
     * @param array $rmData
     * @return bool
     */
    abstract protected function shouldInsert($rmData);
}

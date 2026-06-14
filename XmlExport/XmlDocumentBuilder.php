<?php

namespace XmlExport;

use DOMDocument;
use DOMElement;

/**
 * Базовый строитель DOM-документа.
 * Содержит общие методы для создания XML-элементов.
 */
class XmlDocumentBuilder
{
    /** @var DOMDocument */
    private $document;

    public function __construct()
    {
        $this->document = new DOMDocument("1.0", "utf-8");
        $this->document->formatOutput = true;
    }

    /**
     * Получить DOM-документ.
     *
     * @return DOMDocument
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Создать корневой элемент.
     *
     * @param string $name
     * @return DOMElement
     */
    public function createRootElement($name)
    {
        $root = $this->document->createElement($name);
        $this->document->appendChild($root);
        return $root;
    }

    /**
     * Вставить элемент с текстовым значением.
     *
     * @param DOMElement $root
     * @param string $name
     * @param string|null $val
     * @return DOMElement
     */
    public function insertElement($root, $name, $val = null)
    {
        $element = $this->document->createElement($name, $val);
        $root->appendChild($element);
        return $element;
    }

    /**
     * Вставить элемент с ID-атрибутом.
     *
     * @param DOMElement $root
     * @param string $name
     * @param string $id
     * @param string|null $val
     * @return DOMElement
     */
    public function insertElementWithId($root, $name, $id, $val = null)
    {
        $element = $this->insertElement($root, $name, $val);
        $element->setAttribute('Id', $id);
        return $element;
    }

    /**
     * Вставить ссылки на приборы (MeasuringTool).
     *
     * @param array $devices
     * @param DOMElement $parent
     * @return void
     */
    public function insertToolsId($devices, $parent)
    {
        if (!is_array($devices)) {
            return;
        }
        foreach ($devices as $value) {
            $measuringTool = $this->insertElement($parent, 'MeasuringTool');
            $measuringTool->setAttribute('Id', 'D' . $value['id']);
        }
    }

    /**
     * Вставить ссылки на сотрудников (WorkerForMeasurement).
     *
     * @param array $stuff
     * @param DOMElement $parent
     * @return void
     */
    public function insertStuffId($stuff, $parent)
    {
        if (!is_array($stuff)) {
            return;
        }
        foreach ($stuff as $value) {
            $worker = $this->insertElement($parent, 'WorkerForMeasurement');
            $worker->setAttribute('Id', 'D' . $value['id']);
        }
    }

    /**
     * Вставить члена комиссии по шаблону.
     *
     * @param DOMElement $element
     * @param string $lastName
     * @param string $firstName
     * @param string $position
     * @param string $middleName
     * @return void
     */
    public function insertCommitteeMember($element, $lastName, $firstName, $position, $middleName = '')
    {
        $name = $this->insertElement($element, 'Name');
        $this->insertElement($name, 'LastName', $lastName);
        $this->insertElement($name, 'FirstName', $firstName);
        $this->insertElement($element, 'Position', $position);
    }

    /**
     * Вставить MeasuringPlace с общими полями.
     *
     * @param DOMElement $parent
     * @param array $data
     * @param array $devices
     * @param array $stuff
     * @return DOMElement
     */
    public function insertMeasuringPlace($parent, $data, $devices, $stuff)
    {
        $measuringPlace = $this->insertElement($parent, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $data['point']);
        $this->insertElement($measuringPlace, 'Date', $data['dtControl']);
        $this->insertElement($measuringPlace, 'Duration', $data['pointTime']);
        $this->insertElement($measuringPlace, 'FactorSource', $data['point']);

        $this->insertToolsId($devices, $measuringPlace);
        $this->insertStuffId($stuff, $measuringPlace);

        return $measuringPlace;
    }
}

<?php

namespace XmlExport;

use XmlExport\Sections\FileInfoSection;
use XmlExport\Sections\AttestationSection;
use XmlExport\Sections\EnterpriseSection;
use XmlExport\Sections\WorkPlaceSection;
use XmlExport\Validators\XsdValidator;

/**
 * Главный класс-фасад для экспорта XML СОУТ.
 *
 * Собирает весь документ из отдельных секций.
 */
class XmlExport
{
    /** @var int */
    private $idGroup;

    /** @var XmlDocumentBuilder */
    private $builder;

    /** @var XmlDataProvider */
    private $data;

    /** @var XsdValidator */
    private $validator;

    /** @var ExportService */
    private $exportService;

    /** @var string|null */
    private $xsdErrors = null;

    /**
     * @param int $idGroup
     * @param bool $checkError
     * @param string $schemaName
     */
    public function __construct($idGroup, $checkError = false, $schemaName = 'Sout_1.0.24.xsd')
    {
        $this->idGroup = $idGroup;
        $this->builder = new XmlDocumentBuilder();
        $this->data = new XmlDataProvider($idGroup);
        $this->validator = new XsdValidator();
        $this->exportService = new ExportService();

        $this->buildDocument($checkError, $schemaName);
    }

    /**
     * Построить XML-документ.
     *
     * @param bool $checkError
     * @param string $schemaName
     * @return void
     */
    private function buildDocument($checkError, $schemaName)
    {
        $document = $this->builder->getDocument();

        // Корневой элемент
        $attestation = $this->builder->createRootElement('Attestation');

        // Информация о файле
        $fileInfoSection = new FileInfoSection($this->builder);
        $fileInfoSection->insert($attestation);

        // Данные об аттестации
        $attestationSection = new AttestationSection($this->builder, $this->data);
        $attestationSection->insert($attestation);

        // Предприятие
        $enterprise = $this->builder->insertElement($attestation, 'Enterprise');
        $enterpriseSection = new EnterpriseSection($this->builder, $this->data);
        $enterpriseSection->insert($enterprise);

        // Рабочие места
        $workPlaceSection = new WorkPlaceSection($this->builder, $this->data);
        $workPlaceSection->insert($enterprise);

        // Валидация
        if ($checkError) {
            if (!$this->validator->validate($document, $schemaName)) {
                $this->xsdErrors = $this->validator->getErrorsAsText();
            }
        }

        // Экспорт
        $result = $this->exportService->export($document, $this->idGroup);

        if ($result['success']) {
            $this->handleSuccess($result);
        } else {
            $this->handleError($result['error']);
        }
    }

    /**
     * Обработать успешный экспорт.
     *
     * @param array $result
     * @return void
     */
    private function handleSuccess($result)
    {
        $errorText = 'Произошли следующие ошибки: ' . $this->xsdErrors;

        if (!empty($this->xsdErrors)) {
            $logMsg = $errorText . ' GroupId: ' . $this->idGroup;
            \DbConnect::Log($logMsg, 'Form Error');
            echo 'Ошибка формирования отчета. ' . $errorText;
        } else {
            $this->exportService->sendFile($result['filePath'], $result['fileName']);
        }

        $this->exportService->cleanup($result['filePath']);
    }

    /**
     * Обработать ошибку экспорта.
     *
     * @param string $error
     * @return void
     */
    private function handleError($error)
    {
        echo 'Ошибка формирования отчета: ' . $error;
    }

    /**
     * Получить XML-документ как строку.
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->builder->getDocument()->saveXML();
    }
}

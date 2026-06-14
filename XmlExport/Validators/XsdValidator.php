<?php

namespace XmlExport\Validators;

use DOMDocument;

/**
 * Валидатор XML-документа по XSD-схеме.
 */
class XsdValidator
{
    /** @var array */
    private $errors = array();

    /**
     * Валидировать документ по XSD-схеме.
     *
     * @param DOMDocument $document
     * @param string $schemaName
     * @return bool
     */
    public function validate($document, $schemaName)
    {
        $this->errors = array();

        libxml_use_internal_errors(true);
        $result = $document->schemaValidate($schemaName);

        if (!$result) {
            $this->errors = $this->collectErrors();
        }

        libxml_clear_errors();
        return $result;
    }

    /**
     * Получить ошибки валидации.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Получить ошибки в виде текста.
     *
     * @return string
     */
    public function getErrorsAsText()
    {
        $errorText = '';
        foreach ($this->errors as $error) {
            $errorText .= '<br>' . $error;
        }
        return $errorText;
    }

    /**
     * Собрать ошибки из libxml.
     *
     * @return array
     */
    private function collectErrors()
    {
        $errors = array();
        $libErrors = libxml_get_errors();

        foreach ($libErrors as $error) {
            $errorMsg = trim($error->message);
            if ($error->file) {
                $errorMsg .= ' in <b>' . $error->file . '</b>';
            }
            $errorMsg .= ' on line <b>' . $error->line . '</b>';
            $errors[] = $errorMsg;
        }

        return $errors;
    }
}

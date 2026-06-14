<?php

namespace XmlExport;

use ZipArchive;
use DateTime;

/**
 * Сервис сохранения и выдачи файла экспорта.
 */
class ExportService
{
    /** @var string */
    private $downloadPath;

    /**
     * @param string $downloadPath
     */
    public function __construct($downloadPath = 'DownloadDoc/')
    {
        $this->downloadPath = $downloadPath;
    }

    /**
     * Сохранить XML-документ и выдать ZIP-архив пользователю.
     *
     * @param \DOMDocument $document
     * @param int $idGroup
     * @return array
     */
    public function export($document, $idGroup)
    {
        $datetime = new DateTime();
        $fileName = $datetime->format('Y\-m\-d\_h:i') . '_Sout_Export_' . $idGroup;
        $xmlPath = $this->downloadPath . $fileName . '.xml';
        $zipPath = $this->downloadPath . $fileName . '.suot';

        // Сохраняем XML
        $document->save($xmlPath);

        // Создаём ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== true) {
            unlink($xmlPath);
            return array(
                'success' => false,
                'fileName' => $fileName,
                'error' => 'Не удалось создать ZIP-архив'
            );
        }

        $zip->addFile($xmlPath, $fileName . '.xml');
        $zip->close();

        // Удаляем временный XML
        unlink($xmlPath);

        return array(
            'success' => true,
            'fileName' => $fileName,
            'filePath' => $zipPath
        );
    }

    /**
     * Отправить файл пользователю (HTTP-заголовки + readfile).
     *
     * @param string $filePath
     * @param string $fileName
     * @return void
     */
    public function sendFile($filePath, $fileName)
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($fileName . '.suot'));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        readfile($filePath);
    }

    /**
     * Удалить файл.
     *
     * @param string $filePath
     * @return void
     */
    public function cleanup($filePath)
    {
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}

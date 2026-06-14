<?php
/**
 * Скрипт для экспорта СОУТ в XML/ZIP.
 * Использует новый рефакторированный класс XmlExport\XmlExport.
 *
 * Требует автозагрузку классов из директории XmlExport/.
 */

// Простая автозагрузка для классов в неймспейсе XmlExport
spl_autoload_register(function ($className) {
    // Преобразуем неймспейс в путь к файлу
    // XmlExport\Sections\Factors\ChemicalFactor -> XmlExport/Sections/Factors/ChemicalFactor.php
    $prefix = 'XmlExport\\';
    $baseDir = __DIR__ . '/XmlExport/';

    $len = strlen($prefix);
    if (strncmp($prefix, $className, $len) !== 0) {
        return;
    }

    $relativeClass = substr($className, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Подключаем xmlData для обратной совместимости
require_once __DIR__ . '/xmlData.php';

// Запуск экспорта
if (isset($_GET['grid'])) {
    $idGroup = intval($_GET['grid']);
    $checkError = isset($_GET['check']) ? (bool)$_GET['check'] : false;
    $schemaName = isset($_GET['schema']) ? $_GET['schema'] : 'Sout_1.0.24.xsd';

    try {
        $xml = new XmlExport\XmlExport($idGroup, $checkError, $schemaName);
    } catch (Exception $e) {
        echo 'Ошибка: ' . $e->getMessage();
    }
}

<?php
include_once('xmlData.php');

//702
//316
//$xml = new xmlExport(316);
if (isset($_GET['grid']))
{
  $xml = new xmlExport($_GET['grid'], true);
}
//$xml = new xmlExport(316, true);

class xmlExport
{
  public $document;
  public $xmlData;
  public $xsdErrors;

  public function __construct($idGroup, $checkError=false, $schemaName='Sout_1.0.4.xsd')
  {
    $this->xmlData = new xmlData($idGroup);

    //Основное создание документа
    $this->document = new DOMDocument("1.0", "utf-8");
    $this->document->formatOutput = true;

    //Данные аттестации
    $attestation = $this->document->createElement("Attestation");
    $this->document->appendChild($attestation);


    //Информация о файле
    $this->insertFileInfo($attestation);

    //Данные об аттестации
    $this->insertAttestationInfo($attestation);

    //Данные о доп лаборатории
/*  $laboratory = $this->insertElement($attestation, 'Laboratory');
    $this->insertLaboratory($laboratory);*/

    //Предприятие, где проводилась СОУТ
    $enterprise = $this->insertElement($attestation, 'Enterprise');
    $this->insertEnterprise($enterprise);

    //Проверка данных
    if ($checkError)
    {
      libxml_use_internal_errors(true);
      if (!$this->document->schemaValidate($schemaName))
      $this->xsdErrors = $this->libxml_display_errors();
    }

    //Сохранение данных
    $datetime = new DateTime();
    $fileName = $datetime->format('Y\-m\-d\_h:i').'_Sout_Export_'.$idGroup;
    $filePath = 'DownloadDoc/';
    $this->document->save($filePath.$fileName.'.xml');
    $zip = new ZipArchive();
    $zip->open($filePath.$fileName.'.suot', ZipArchive::CREATE);
    $zip->addFile($filePath.$fileName.'.xml', $fileName.'.xml');
    $zip->close();
    unlink($filePath.$fileName.'.xml');

    $error = 'Произошли следующие ошибки: '.$this->xsdErrors;

    if ($this->xsdErrors != '') {DbConnect::Log($error.' GroupId: '.$this->idGroup, 'Form Error'); echo 'Ошибка формирования отчета. '.$error;}
    else{
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($fileName.'.suot'));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      readfile($filePath.$fileName.'.suot');
    }
  }

  public function libxml_display_errors() {

      $errors = libxml_get_errors();
      $errorText = '';
      foreach ($errors as $error) {
          $errorText .= '<br>'.$this->libxml_display_error($error);

      }
      libxml_clear_errors();
      return $errorText;
  }

  public function libxml_display_error($error)
  {
    $return = "<br/>\n";
/*    switch ($error->level) {
        case LIBXML_ERR_WARNING:
            $return .= "<b>Warning $error->code</b>: ";
            break;
        case LIBXML_ERR_ERROR:
            $return .= "<b>Error $error->code</b>: ";
            break;
        case LIBXML_ERR_FATAL:
            $return .= "<b>Fatal Error $error->code</b>: ";
            break;
    }*/
    $return .= trim($error->message);
    if ($error->file) {
        $return .=    " in <b>$error->file</b>";
    }
    $return .= " on line <b>$error->line</b>\n";

    return $return;
  }

//----------------------------------------
  public function insertFileInfo($root)
  {
    $fileInfo = $this->insertElement($root, 'FileInfo');

    $this->insertElement($fileInfo, 'SchemeVersion', '1.0');//??
    $this->insertElement($fileInfo, 'DataSource', 'отсутствует');
    $this->insertElement($fileInfo, 'CreatedDate', date("Y-m-d"));//??
    $this->insertElement($fileInfo, 'DateSent', date("Y-m-d"));//??
  }

//-----------------------------------------
  public function insertAttestationInfo($attestation)
  {
    $this->insertElement($attestation, 'Date', $this->xmlData->getReportDate());
    $this->insertElement($attestation, 'WorkPlacesQuantity', $this->xmlData->getRmCountTotal());
    $this->insertElement($attestation, 'WorkPlacesAttested', $this->xmlData->getRmCount());
    $this->insertElement($attestation, 'Region', $this->xmlData->getRegion());
    // FIXME: Пропущено Contract ??
    //$this->insertElement($attestation, 'ExpertConclusion', $this->xmlData->getExpertConclusion());
    // FIXME: Пропущено ProductionControlProtocol ??
    // Атт. комиссия
    $this->insertComitees($attestation);
    $this->insertAttestationOrganization($attestation);
  }

//--------------------------------------------------
//Вставка всех членов комиссии
  public function insertComitees($attestation)
  {
    $commitee = $this->insertElement($attestation, 'Committee');
    $chairman = $this->insertElement($commitee, 'Chairman');
    $commiteeArray = $this->xmlData->getComitee();
    $this->insertComitee($chairman, explode(' ',$commiteeArray[0]['sName'])[0], explode(' ',$commiteeArray[0]['sName'])[1], $commiteeArray[0]['sPost']);

    for ($i=1; $i < count($commiteeArray); $i++) {
      $member = $this->insertElement($commitee, 'Member');
      $this->insertComitee($member, explode(' ',$commiteeArray[$i]['sName'])[0], explode(' ',$commiteeArray[$i]['sName'])[1], $commiteeArray[$i]['sPost']);
    }
  }

//Вставка члена комиссии по шаблону
  public function insertComitee($element, $lastName, $firstName, $position, $middleName='')
  {
    $name = $this->insertElement($element,'Name');
    $LastName = $this->insertElement($name, 'LastName', $lastName);
    $FirstName = $this->insertElement($name, 'FirstName',$firstName);
    //$MiddleName = $this->insertElement($name, 'MiddleName',$middleName);
    $position = $this->insertElement($element, 'Position', $position);
  }
//---------------------------------------

  public function insertAttestationOrganization($attestation)
  {
    $attestationOrganization = $this->insertElement($attestation, 'AttestationOrganization');
    $dataAtt = $this->xmlData->getAttestationOrganisation();
    $this->insertElement($attestationOrganization, 'Name', $dataAtt['sOrgName']);
    $this->insertElement($attestationOrganization, 'RegistrationNumber', $dataAtt['sOrgRegNum']);
    $this->insertElement($attestationOrganization, 'RegistrationDate', $dataAtt['sOrgDate']);

    if (!preg_match('/\d{13}|\d{15}/',$dataAtt['sOrgOgrn'])) $dataAtt['sOrgOgrn'] = '0000000000000';
    $this->insertElement($attestationOrganization, 'OGRN', $dataAtt['sOrgOgrn']);
    $this->insertElement($attestationOrganization, 'INN', $dataAtt['sOrgInn']);
    //$name = $this->insertElement($attestationOrganization, 'KPP', $dataAtt['sOrgInn']);
    $this->insertElement($attestationOrganization, 'Address', $dataAtt['sOrgAdress']);
    $this->insertElement($attestationOrganization, 'Phone', $dataAtt['sOrgPhone']);
    $this->insertElement($attestationOrganization, 'E-mail', $dataAtt['sName']);
    $this->insertElement($attestationOrganization, 'Director', $dataAtt['sFirstFaceName']);
    $workers = $this->insertElement($attestationOrganization, 'Workers');
    //Эксперты
    $experts = $this->xmlData->getExpert();
    foreach ($experts as $key => $value) {
      $newExpert = $this->insertElement($workers, 'Expert');
      $newExpert->setAttribute('Id', 'D'.$value['id']);
      $this->insertComitee($newExpert, explode(' ',$value['sName'])[1], explode(' ',$value['sName'])[0], $value['sPost']);
    }

    //Работники
    $stuff = $this->xmlData->getStuff();
    foreach ($stuff as $key => $value) {
      $newStuff = $this->insertElement($workers, 'Expert');
      $newStuff->setAttribute('Id', 'D'.$value['id']);
      $this->insertComitee($newStuff, explode(' ',$value['sName'])[1], explode(' ',$value['sName'])[0], $value['sPost']);
    }

    $laboratory = $this->insertElement($attestationOrganization, 'Laboratory');
    $this->insertLaboratory($laboratory, $dataAtt);
  }

  public function insertExperts($element)
  {
    // TODO: Запонить экспертами, если будет нужно
  }

  public function insertLaboratory($laboratory, $dataAtt)
  {
    $attestationAccredit = $this->xmlData->getAttestationAccreditMy();
    $this->insertElement($laboratory, 'Name', $dataAtt['sOrgName']);
    $this->insertElement($laboratory, 'CertificateNumber', $attestationAccredit['sName']);
    $this->insertElement($laboratory, 'CertificateIssueDate', $attestationAccredit['dDateCreate']);
    $this->insertElement($laboratory, 'CertificateExpiryDate', $attestationAccredit['dDateFinish']);
    $measuringTools = $this->xmlData->getDevice();
    foreach ($measuringTools as $key => $value) {
      $measuringTool = $this->insertElement($laboratory, 'MeasuringTool');
      $measuringTool->setAttribute('Id', 'D'.$value['id']);
      $this->insertElement($measuringTool, 'SerialNumber', $value['sFactoryNum']);
      $this->insertElement($measuringTool, 'CalibrationExpiryDate', $value['dCheckDate']);
      $this->insertElement($measuringTool, 'Name', $value['sName']);
      $this->insertElement($measuringTool, 'FundNumber', $value['sReestrNum']);
    }

  }
//----------------------------------------------
  //Предприятие, где проводилась СОУТ
  public function insertEnterprise($enterprise)
  {
    $dataGroup = $this->xmlData->getDataGroup();
    $this->insertElement($enterprise, 'Name', $dataGroup['sFullName']);
    if (!preg_match('/\d{13}|\d{15}/',$dataGroup['sOgrn'])) $dataGroup['sOgrn'] = '0000000000000';
    $this->insertElement($enterprise, 'OGRN', $dataGroup['sOgrn']);
    if (!preg_match('/\d{10}|\d{12}/',$dataGroup['sInn'])) $dataGroup['sInn'] = '0000000000';
    $this->insertElement($enterprise, 'INN', $dataGroup['sInn']);
    if (!preg_match('/\d{8}|\d{10}/',$dataGroup['sOkpo'])) $dataGroup['sOkpo'] = '00000000';
    $this->insertElement($enterprise, 'OKPO', $dataGroup['sOkpo']);
    if (!preg_match('/\d{5}|\d{7}/',$dataGroup['sOkogu'])) $dataGroup['sOkogu'] = '00000';
    $this->insertElement($enterprise, 'OKOGU', $dataGroup['sOkogu']);
    $okved = $this->insertElement($enterprise, 'OKVED');
    $okvedCodes = explode(',',$dataGroup['sOkved']);
    foreach ($okvedCodes as $key => $value) {//TODO: Написать в потдержку не верные ОКВЕД
      if (!preg_match_all('/\d{2}|\d{2}.\d{1}|\d{2}.\d{2}|\d{2}.\d{2}.\d{1}|\d{2}.\d{2}.\d{2}/',trim($value)))
      { $value = '00'; }
        $this->insertElement($okved, 'Kod', trim($value));
    }
    //if (count($okvedCodes) == 1){$this->insertElement($okved, 'Kod', '00');}
    if (!preg_match('/\d{2}|\d{5}|\d{8}|\d{11}/',$dataGroup['sOkato'])) $dataGroup['sOkato'] = '00';
    $this->insertElement($enterprise, 'OKATO', $dataGroup['sOkato']);
    $this->insertElement($enterprise, 'DeJureAddress', $dataGroup['sPlace']);
    $this->insertElement($enterprise, 'PostAddress', $dataGroup['sPlace']);
    $this->insertElement($enterprise, 'E-mail', $dataGroup['sEmail']);
    $this->insertElement($enterprise, 'Director', $dataGroup['sNameDirector']);

    //Рабочее место, на котором проведена СОУТ
    $rms = $this->xmlData->getRm();
    foreach ($rms as $key => $value) {
      if (!$this->isAttestedPlace($value))
      {
        $workPlaceDeclared = $this->insertElement($enterprise, 'WorkPlaceDeclared');
        $this->insertWorkPlaceDeclared($workPlaceDeclared, $value);
      }
    }

    foreach ($rms as $key => $value) {
      if ($this->isAttestedPlace($value))
      {
        $workPlaceAttested = $this->insertElement($enterprise, 'WorkPlaceAttested');
        $this->insertWorkPlaceAttested($workPlaceAttested, $value);
      }
    }
  }

  public function isAttestedPlace($rmData)
  {
    if ($rmData['iAChem'] != '-' ||
        $rmData['iABio'] != '-' ||
        $rmData['iAAPFD'] != '-' ||
        $rmData['iANoise'] != '-' ||
        $rmData['iAInfraNoise'] != '-' ||
        $rmData['iAUltraNoise'] != '-' ||
        $rmData['iAVibroO'] != '-' ||
        $rmData['iAVibroL'] != '-' ||
        $rmData['iANoIon'] != '-' ||
        $rmData['iAIon'] != '-' ||
        $rmData['iAMicroclimat'] != '-' ||
        $rmData['iALight'] != '-' ||
        $rmData['iAHeavy'] != '-' ||
        $rmData['iAHeavyW'] != '-' ||
        $rmData['iAHeavyM'] != '-' ||
        $rmData['iATennese'] != '-')
        { return true;} else {return false;}
  }

  public function insertWorkPlaceDeclared($workPlaceDeclared, $rmData)
  {
    $this->insertElement($workPlaceDeclared, 'Id', $rmData['id']);
    $this->insertElement($workPlaceDeclared, 'Position', $rmData['sName']);
    $this->insertElement($workPlaceDeclared, 'SubUnit', $rmData['Division']);
    $this->insertElement($workPlaceDeclared, 'Profession',  $rmData['sOk']);
    $this->insertElement($workPlaceDeclared, 'WorkersQuantity', $rmData['iCount']);
    $this->insertElement($workPlaceDeclared, 'WomansQuantity', $rmData['iCountWoman']);
    $this->insertElement($workPlaceDeclared, 'TeenagersQuantity', $rmData['iCountYouth']);
    $this->insertElement($workPlaceDeclared, 'InvalidsQuantity', $rmData['iCountDisabled']);
    //FIXME: Имя поля исправить на верное
    $this->insertElement($workPlaceDeclared, 'ExpertConclusion', $this->xmlData->getExpertConclusion());
    $snils = $rmData['sSnils'];
    foreach ($snils as $key => $value) {
      $this->insertElement($workPlaceDeclared, 'Snils', $value);
    }
  }

  public function insertWorkPlaceAttested($workPlaceAttested, $rmData)
  {
    $this->insertElement($workPlaceAttested, 'SOUTCardNumber', $rmData['iNumber']);
    $this->insertElement($workPlaceAttested, 'SheetDate', $rmData['dCreateDate']);
    $this->insertElement($workPlaceAttested, 'Id', $rmData['id']);
    $this->insertElement($workPlaceAttested, 'Position', $rmData['sName']);
    $this->insertElement($workPlaceAttested, 'Profession',  $rmData['sOk']);
    $this->insertElement($workPlaceAttested, 'ETKS_EKS_Issue', $rmData['sETKS']);
    $this->insertElement($workPlaceAttested, 'SubUnit', $rmData['Division']);
    $this->insertElement($workPlaceAttested, 'WorkersQuantity', $rmData['iCount']);
    $this->insertElement($workPlaceAttested, 'WomansQuantity', $rmData['iCountWoman']);
    $this->insertElement($workPlaceAttested, 'TeenagersQuantity', $rmData['iCountYouth']);
    $this->insertElement($workPlaceAttested, 'InvalidsQuantity', $rmData['iCountDisabled']);
    $this->insertElement($workPlaceAttested, 'RawMaterials', $rmData['Materials']);
    $this->insertElement($workPlaceAttested, 'Equipment', $rmData['Equipment']);
    $this->insertElement($workPlaceAttested, 'WorkingConditions', $rmData['iATotal']);
    $snils = $rmData['sSnils'];
    foreach ($snils as $key => $value) {
      $this->insertElement($workPlaceAttested, 'Snils', $value);
    }
    //Вставка рекомендаций
    $recomendation = $this->insertElement($workPlaceAttested, 'Recommendations');
    $workingConditionsImprovement = $this->insertElement($recomendation, 'WorkingConditionsImprovement');
    //if (count($rmData['Recomendations'])>0)
    foreach ($rmData['Recomendations'] as $key => $value) {
      $arrangement = $this->insertElement($workingConditionsImprovement, 'Arrangement');
      $this->insertRecomendation($arrangement, $value);
    }

    //Гарантии и компенсации
    $compensations = $this->insertElement($workPlaceAttested, 'Compensations');
    //Доплаты
    $payIncrease = $this->insertElement($compensations, 'PayIncrease');
    $this->insertCompensationAttributes($payIncrease, $rmData['sCompFactSurcharge'], $rmData['iCompSurcharge'], $rmData['sCompBaseSurcharge']);
    //Ежегодный дополнительный оплачиваемый отпуск
    $сompensatoryLeave = $this->insertElement($compensations, 'CompensatoryLeave');
    $this->insertCompensationAttributes($сompensatoryLeave, $rmData['sCompFactVacation'], $rmData['iCompVacation'], $rmData['sCompBaseVacation']);
    //Сокращенная продолжительность рабочего времени
    $halfDay = $this->insertElement($compensations, 'HalfDay');
    $this->insertCompensationAttributes($halfDay, $rmData['sCompFactShortWorkDay'], $rmData['iCompShortWorkDay'], $rmData['sCompBaseShortWorkDay']);
    //Молоко или другие равноценные продукты
    $milk = $this->insertElement($compensations, 'Milk');
    $this->insertCompensationAttributes($milk, $rmData['sCompFactMilk'], $rmData['iCompMilk'], $rmData['sCompBaseMilk']);
    //Лечебно-профилактическое питание
    $dieteticTherapy = $this->insertElement($compensations, 'DieteticTherapy');
    $this->insertCompensationAttributes($dieteticTherapy, $rmData['sCompFactFood'], $rmData['iCompFood'], $rmData['sCompBaseFood']);
    //Право на досрочное назначение трудовой пенсии
    $earlyRetirement = $this->insertElement($compensations, 'EarlyRetirement');
    $this->insertCompensationAttributes($earlyRetirement, $rmData['sCompFactPension'], $rmData['iCompPension'], $rmData['sCompBasePension']);
    //Проведение медецинских осмотров
    $medicalInspection = $this->insertElement($compensations, 'MedicalInspection');
    $this->insertCompensationAttributes($medicalInspection, $rmData['sCompFactPhysical'], $rmData['iCompPhysical'], $rmData['sCompBasePhysical']);

    $factors = $this->insertElement($workPlaceAttested, 'Factors');
    $this->insertChemicalFactors($factors, $rmData);
    $this->insertBiologicalFactors($factors, $rmData);

    $this->insertAerosol($factors, $rmData);

    // FIXME: Вернуть шум
    $this->insertNoise($factors, $rmData);

    $this->insertInfrasound($factors, $rmData);
    $this->insertUltrasound($factors, $rmData);

    $this->insertLocalVibration($factors, $rmData);
    $this->insertOverallVibration($factors, $rmData);

    // TODO: NonionizingRadiation
    // TODO: RadioFields
    // TODO: ElectrostaticField
    // TODO: ConstantMagneticField
    // TODO: UltravioletRadiation
    // TODO: LaserRadiation
    // TODO: IonizingRadiation

    //Факторы микроклимата
    $this->insertMicroclimate($factors, $rmData);

    //Факторы освещенности
    $this->insertLightEnvironment($factors, $rmData);

    //Тяжесть
    $this->insertLabourSeverity($factors, $rmData);
    //Напряженность
    $this->insertLabourIntensity($factors, $rmData);
  }

  public function insertRecomendation($recomendation, $dataRecomendations)
  {
    $this->insertElement($recomendation, 'Name', $dataRecomendations['sActivityName']);
    $this->insertElement($recomendation, 'Purpose', $dataRecomendations['sActivityTarget']);
    $this->insertElement($recomendation, 'DueDate', $dataRecomendations['sTerm']);
    $this->insertElement($recomendation, 'EngagedUnits', $dataRecomendations['sInvolved']);
  }

  public function insertCompensationAttributes($element, $actualAvailability, $needToEstablish, $reason)
  {
    $this->insertElement($element, 'ActualAvailability', $actualAvailability);
    $this->insertElement($element, 'NeedToEstablish', $needToEstablish);
    $this->insertElement($element, 'Reason', $reason);
  }

//Вставка химических факторов
  public function insertChemicalFactors($factors, $rmData)
  {
    if ($rmData['iAChem'] != '-')
    {
      $chemicalFactors = $this->insertElement($factors, 'ChemicalFactors');
      $this->insertElement($chemicalFactors, 'WorkingConditions', $rmData['iAChem']);
      //------------------------
      $chem = $rmData['aChem'];
      $this->insertChemicalAgent($chemicalFactors, $chem);
      //------------------------FIXME Много тут пропущено
    }
  }

  public function insertChemicalAgent($chemicalFactors, $chem)
  {

    foreach ($chem as $key => $value) {
      $chemicalAgent = $this->insertElement($chemicalFactors, 'ChemicalAgent');
      $this->insertElement($chemicalAgent, 'Kod', $value['code']);
      if ($value['pdkM']) ($this->insertElement($chemicalAgent, 'NormValueMax', $value['pdkM']));
      if ($value['pdkS']) ($this->insertElement($chemicalAgent, 'NormValueAvg', $value['pdkS']));
      $this->insertElement($chemicalAgent, 'WorkingConditions', $value['asset']);
      $this->insertElement($chemicalAgent, 'NormativeAct', $value['nd']);

      $measuringPlace = $this->insertElement($chemicalAgent, 'MeasuringPlace');
      $this->insertElement($measuringPlace, 'Name', $value['point']);
      $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
      $this->insertElement($measuringPlace, 'ConcentrationMax', $value['factM']);
      $this->insertElement($measuringPlace, 'ConcentrationAvg', $value['factS']);
      $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
      $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

      $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
      $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
    }
  }
//---------------------------------------------

  public function insertBiologicalFactors($factors, $rmData)
  {
    if ($rmData['iABio'] != '-')
    {
      $bioFactors = $this->insertElement($factors, 'BiologicalFactors');
      $this->insertElement($bioFactors, 'WorkingConditions', $rmData['iABio']);
      $bioMP = $rmData['aBioMP'];
      foreach ($bioMP as $key => $value) {
        $microorganismProducer = $this->insertElement($bioFactors, 'MicroorganismProducer');
        $this->insertElement($microorganismProducer, 'Kod', $value['code']);
        $this->insertElement($microorganismProducer, 'NormValue', $value['pdk']);
        $this->insertElement($microorganismProducer, 'WorkingConditions', $value['asset']);
        $this->insertElement($microorganismProducer, 'NormativeAct', $value['nd']);
        $measuringPlace = $this->insertElement($microorganismProducer, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $value['point']);
        $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
        $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
        $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

        $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
        $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
      }

      $bioPM = $rmData['aBioPM'];
      foreach ($bioPM as $key => $value) {
        $microorganismPathogenic = $this->insertElement($bioFactors, 'MicroorganismPathogenic');
        $this->insertElement($microorganismPathogenic, 'Kod', $value['code']);
        $this->insertElement($microorganismPathogenic, 'WorkingConditions', $value['asset']);
        $this->insertElement($microorganismPathogenic, 'NormativeAct', $value['nd']);
        $measuringPlace = $this->insertElement($microorganismPathogenic, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $value['point']);
        $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
        $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
        $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

        $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
      }
    }
  }

  public function insertStuffId($stuff, $measuringPlace)
  {
    foreach ($stuff as $key => $value) {
      $workerForMeasurement = $this->insertElement($measuringPlace, 'WorkerForMeasurement');
      $workerForMeasurement->setAttribute('Id', 'D'.$value['id']);
    }
  }

  public function insertToolsId($devices, $measuringPlace)
  {
    foreach ($devices as $key => $value) {
      $measuringTool = $this->insertElement($measuringPlace, 'MeasuringTool');
      $measuringTool->setAttribute('Id', 'D'.$value['id']);
    }
  }

  public function insertAerosol($factors, $rmData)
  {
    if ($rmData['iAAPFD'] != '-')
    {
      $apfdFactors = $this->insertElement($factors, 'Aerosol');
      $this->insertElement($apfdFactors, 'WorkingConditions', $rmData['iAAPFD']);
      $chem = $rmData['aChem'];

      $apfd = $rmData['aAAPFD'];
      foreach ($apfd as $key => $value) {

      $chemicalAgent = $this->insertElement($apfdFactors, 'ChemicalAgent');
      $this->insertElement($chemicalAgent, 'Kod', $value['code']);
      $this->insertElement($chemicalAgent, 'ConcentrationAvg', $value['factS']);
      $this->insertElement($chemicalAgent, 'NormValue', $value['pdkS']);
      $this->insertElement($chemicalAgent, 'WorkingConditions', $value['asset']);
      $this->insertElement($chemicalAgent, 'NormativeAct', $value['nd']);

      $measuringPlace = $this->insertElement($chemicalAgent, 'MeasuringPlace');
      $this->insertElement($measuringPlace, 'Name', $value['point']);
      $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
      $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
      $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

      $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
      $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
      }
    }
  }

//---------------------------------------------

  public function getUncertainty()
  {
    return rand(10,20)/10;
  }

  public function insertNoise($factors, $rmData)
  {
    if ($rmData['iANoise'] != '-')
    {
      $noiseFactor = $this->insertElement($factors, 'Noise');
      $this->insertElement($noiseFactor, 'Value', $rmData['dEqNoise']);
      $this->insertElement($noiseFactor, 'Uncertainty', $rmData['dSuspNoise']));
      $this->insertElement($noiseFactor, 'NormValue', $rmData['aNOISE'][0]['pdkM']);
      $this->insertElement($noiseFactor, 'WorkingConditions', $rmData['iANoise']);
      $this->insertElement($noiseFactor, 'NormativeAct', $rmData['aNOISE'][0]['nd']);
      /*$workOperationStrategy = $this->insertElement($noiseFactor, 'WorkOperationStrategy');
      $this->insertElement($workOperationStrategy, 'Value', $rmData['dEqNoise']);
      $this->insertElement($workOperationStrategy, 'Uncertainty', $rmData['dSSNNoise']);
      foreach ($rmData['aNOISE'] as $key => $value) {
        $workOperation = $this->insertElement($workOperationStrategy, 'WorkOperation');
        $this->insertElement($workOperationStrategy, 'Name', $value['point']);
        $this->insertElement($workOperationStrategy, 'TimeBehavior', '1');
        $this->insertElement($workOperationStrategy, 'OperationDuration', $value['pointTime']);
        $this->insertElement($workOperationStrategy, 'FactorSource', $value['pointTime']);
        //$measuringData = $this->insertElement($workOperationStrategy, 'MeasuringData')
      }*/

    }
  }
//---------------------------------------------

  public function insertInfrasound($factors, $rmData)
  {
    if ($rmData['iAInfraNoise'] != '-')
    {
      $infrasound = $this->insertElement($factors, 'Infrasound');
      $this->insertElement($infrasound, 'Value', $rmData['dEqInfraNoise']);
      $this->insertElement($infrasound, 'NormValue', $rmData['aAInfraNoise'][0]['pdkM']);
      $this->insertElement($infrasound, 'WorkingConditions', $rmData['iAInfraNoise']);
      $this->insertElement($infrasound, 'NormativeAct', $rmData['aAInfraNoise'][0]['nd']);
      //echo '<br>'.count($rmData['dEqInfraNoise']).'<br>';
      foreach ($rmData['aAInfraNoise'] as $key => $value) {
        $measuringPlace = $this->insertElement($infrasound, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $value['point']);
        $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
        $this->insertElement($measuringPlace, 'TimeBehavior', '1');
        $this->insertElement($measuringPlace, 'Value', $value['factM']);
        $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
        $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

        $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
        $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
      }
    }
  }

  //---------------------------------------------

    public function insertUltrasound($factors, $rmData)
    {
      if ($rmData['iAUltraNoise'] != '-')
      {
        $ultrasound = $this->insertElement($factors, 'Ultrasound');
        $this->insertElement($ultrasound, 'WorkingConditions', $rmData['iAUltraNoise']);
        $this->insertElement($ultrasound, 'NormativeAct', $rmData['aAUltraNoise'][0]['nd']);
        foreach ($rmData['aAUltraNoise'] as $key => $value) {
          $measuringPlace = $this->insertElement($ultrasound, 'MeasuringPlace');
          $this->insertElement($measuringPlace, 'Name', $value['point']);
          $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
          $this->insertElement($measuringPlace, 'TimeBehavior', '0');
          $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
          $this->insertElement($measuringPlace, 'FactorSource', $value['point']);
          $this->insertElement($measuringPlace, 'WorkingConditions', $value['asset']);
          foreach ($value['aOctave'] as $key2 => $value2) {
            $soundPressureOctave = $this->insertElement($measuringPlace, 'SoundPressureOctave');
            $this->insertElement($soundPressureOctave, 'Octave', $key2);
            $this->insertElement($soundPressureOctave, 'Value', $value2['Value']);
            $this->insertElement($soundPressureOctave, 'NormValue', $value2['NormValue']);
          }

          $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
          $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
        }
      }
    }
//---------------------------------------------

  public function insertLocalVibration($factors, $rmData)
  {
    if ($rmData['iAVibroL'] != '-')
    {
      $localVibration = $this->insertElement($factors, 'LocalVibration');
      $this->insertElement($localVibration, 'ValueX', $rmData['dEqVibroLX']);
      $this->insertElement($localVibration, 'ValueY', $rmData['dEqVibroLY']);
      $this->insertElement($localVibration, 'ValueZ', $rmData['dEqVibroLZ']);
      $this->insertElement($localVibration, 'NormValue', $rmData['aAVibroL'][0]['pdkX']);
      $this->insertElement($localVibration, 'WorkingConditions', $rmData['iAVibroL']);
      $this->insertElement($localVibration, 'NormativeAct', $rmData['aAVibroL'][0]['nd']);
      foreach ($rmData['aAVibroL'] as $key => $value) {
        $measuringPlace = $this->insertElement($localVibration, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $value['point']);
        $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
        $this->insertElement($measuringPlace, 'TimeBehavior', '1');
        $this->insertElement($measuringPlace, 'ValueX', $value['factX']);
        $this->insertElement($measuringPlace, 'ValueY', $value['factY']);
        $this->insertElement($measuringPlace, 'ValueZ', $value['factZ']);
        $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
        $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

        $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
        $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
      }
    }
  }

  //---------------------------------------------

    public function insertOverallVibration($factors, $rmData)
    {
      if ($rmData['iAVibroO'] != '-')
      {
        $overallVibration = $this->insertElement($factors, 'OverallVibration');
        $this->insertElement($overallVibration, 'ValueX', $rmData['dEqVibroOX']);
        $this->insertElement($overallVibration, 'ValueY', $rmData['dEqVibroOY']);
        $this->insertElement($overallVibration, 'ValueZ', $rmData['dEqVibroOZ']);
        $this->insertElement($overallVibration, 'NormValueXY', $rmData['aAVibroO'][0]['pdkX']);
        $this->insertElement($overallVibration, 'NormValueZ', $rmData['aAVibroO'][0]['pdkZ']);
        $this->insertElement($overallVibration, 'WorkingConditions', $rmData['iAVibroO']);
        $this->insertElement($overallVibration, 'NormativeAct', $rmData['aAVibroO'][0]['nd']);
        foreach ($rmData['aAVibroO'] as $key => $value) {
          $measuringPlace = $this->insertElement($overallVibration, 'MeasuringPlace');
          $this->insertElement($measuringPlace, 'Name', $value['point']);
          $this->insertElement($measuringPlace, 'Date', $value['dtControl']);
          $this->insertElement($measuringPlace, 'TimeBehavior', '1');
          $this->insertElement($measuringPlace, 'ValueX', $value['factX']);
          $this->insertElement($measuringPlace, 'ValueY', $value['factY']);
          $this->insertElement($measuringPlace, 'ValueZ', $value['factZ']);
          $this->insertElement($measuringPlace, 'Duration', $value['pointTime']);
          $this->insertElement($measuringPlace, 'FactorSource', $value['point']);

          $this->insertToolsId($this->xmlData->getDevice(), $measuringPlace);
          $this->insertStuffId($this->xmlData->getStuff(), $measuringPlace);
        }
      }
    }
//---------------------------------------------

  public function insertMicroclimate($factors, $rmData)
  {
    if ($rmData['iAMicroclimat'] != '-')
    {
      $microclimate = $this->insertElement($factors, 'Microclimate');
      $this->insertElement($microclimate, 'WorkingConditions', $rmData['iAMicroclimat']);
      foreach ($rmData['aAMicroclimat'] as $key => $value) {
        $measuringPlace = $this->insertElement($microclimate, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $value[0]['point']);
        $this->insertElement($measuringPlace, 'Date', $value[0]['dtControl']);
        $this->insertElement($measuringPlace, 'Duration', $value[0]['pointTime']);
        $this->insertElement($measuringPlace, 'WorkingConditions', $value[0]['asset']);
        $this->insertElement($measuringPlace, 'WorkCategory', $value[0]['pdk']);
        $this->insertElement($measuringPlace, 'Posture', $value[0]['posture']);

        foreach ($value as $key2 => $value2) {

          if (strval($key2) != 'zoneAsset'){
          if ($value2['factorId'] == '2'){
              $airTemperature = $this->insertElement($measuringPlace, 'AirTemperature');
              $this->insertElement($airTemperature, 'Value', $value2['fact']);
              $this->insertElement($airTemperature, 'NormValueMin', $value2['pdkMin']);
              $this->insertElement($airTemperature, 'NormValueMax', $value2['pdkMax']);
              $this->insertElement($airTemperature, 'NormativeAct', $value2['nd']);
              $airTemperatureHeight = $this->insertElement($airTemperature, 'AirTemperatureHeight');
              $this->insertElement($airTemperatureHeight, 'Height', $value2['h1']);
              $this->insertElement($airTemperatureHeight, 'Value', $value2['fact']);
              $airTemperatureHeight = $this->insertElement($airTemperature, 'AirTemperatureHeight');
              $this->insertElement($airTemperatureHeight, 'Height', $value2['h2']);
              $this->insertElement($airTemperatureHeight, 'Value', $value2['fact']);

              $this->insertToolsId($this->xmlData->getDevice(), $airTemperature);
              $this->insertStuffId($this->xmlData->getStuff(), $airTemperature);
            }
          }
        }

          foreach ($value as $key2 => $value2) {
            if (strval($key2) != 'zoneAsset'){
            if ($value2['factorId'] == '6'){
                $airSpeed = $this->insertElement($measuringPlace, 'AirSpeed');
                $this->insertElement($airSpeed, 'Value', $value2['fact']);
                $this->insertElement($airSpeed, 'NormValue', $value2['pdkAirSpeed']);
                $this->insertElement($airSpeed, 'NormativeAct', $value2['nd']);
                $airSpeedHeight = $this->insertElement($airSpeed, 'AirSpeedHeight');
                $this->insertElement($airSpeedHeight, 'Height', $value2['h1']);
                $this->insertElement($airSpeedHeight, 'Value', $value2['fact']);
                $airSpeedHeight = $this->insertElement($airSpeed, 'AirSpeedHeight');
                $this->insertElement($airSpeedHeight, 'Height', $value2['h2']);
                $this->insertElement($airSpeedHeight, 'Value', $value2['fact']);

                $this->insertToolsId($this->xmlData->getDevice(), $airSpeed);
                $this->insertStuffId($this->xmlData->getStuff(), $airSpeed);
              }
            }
          }

            foreach ($value as $key2 => $value2) {
              if (strval($key2) != 'zoneAsset'){
              if ($value2['factorId'] == '5'){
                  $airHumidity = $this->insertElement($measuringPlace, 'AirHumidity');
                  $this->insertElement($airHumidity, 'Height', $value2['h1']);
                  $this->insertElement($airHumidity, 'Value', $value2['fact']);
                  $this->insertElement($airHumidity, 'NormValueMin', $value2['pdkMinAH']);
                  $this->insertElement($airHumidity, 'NormValueMax', $value2['pdkMaxAH']);
                  $this->insertElement($airHumidity, 'NormativeAct', $value2['nd']);

                  $this->insertToolsId($this->xmlData->getDevice(), $airHumidity);
                  $this->insertStuffId($this->xmlData->getStuff(), $airHumidity);
                }
              }
            }

              foreach ($value as $key2 => $value2) {
                if (strval($key2) != 'zoneAsset'){
                if ($value2['factorId'] == '56'){
                  $thermalLoadIndex = $this->insertElement($measuringPlace, 'ThermalLoadIndex');
                  $this->insertElement($thermalLoadIndex, 'Value', $value2['fact']);
                  $this->insertElement($thermalLoadIndex, 'NormValue', $value2['pdkTNS']);
                  $this->insertElement($thermalLoadIndex, 'WorkingConditions', $value2['asset']);
                  $this->insertElement($thermalLoadIndex, 'NormativeAct', $value2['nd']);

                  $this->insertToolsId($this->xmlData->getDevice(), $thermalLoadIndex);
                  $this->insertStuffId($this->xmlData->getStuff(), $thermalLoadIndex);
                }
              }
            }

              foreach ($value as $key2 => $value2) {
                if (strval($key2) != 'zoneAsset'){
                if ($value2['factorId'] == '7'){
                  $heatRadiationIntensity = $this->insertElement($measuringPlace, 'HeatRadiationIntensity');
                  $this->insertElement($heatRadiationIntensity, 'Value', $value2['fact']);
                  $this->insertElement($heatRadiationIntensity, 'FactorSource', $value2['point']);
                  $this->insertElement($heatRadiationIntensity, 'NormValue', $value2['pdkHRI']);
                  $this->insertElement($heatRadiationIntensity, 'WorkingConditions', $value2['asset']);
                  //TODO: Внести Экспозиционную дозу
                  $this->insertElement($heatRadiationIntensity, 'NormativeAct', $value2['nd']);

                  $heatRadiationIntensityHeight = $this->insertElement($heatRadiationIntensity, 'HeatRadiationIntensityHeight');
                  $this->insertElement($heatRadiationIntensityHeight, 'Height', $value2['h1']);
                  $this->insertElement($heatRadiationIntensityHeight, 'Value', $value2['fact']);
                  $heatRadiationIntensityHeight = $this->insertElement($heatRadiationIntensity, 'HeatRadiationIntensityHeight');
                  $this->insertElement($heatRadiationIntensityHeight, 'Height', $value2['h2']);
                  $this->insertElement($heatRadiationIntensityHeight, 'Value', $value2['fact']);
                  $heatRadiationIntensityHeight = $this->insertElement($heatRadiationIntensity, 'HeatRadiationIntensityHeight');
                  $this->insertElement($heatRadiationIntensityHeight, 'Height', $value2['h3']);
                  $this->insertElement($heatRadiationIntensityHeight, 'Value', $value2['fact']);

                  $this->insertToolsId($this->xmlData->getDevice(), $heatRadiationIntensity);
                  $this->insertStuffId($this->xmlData->getStuff(), $heatRadiationIntensity);
                }
              }
            }
      }
    }
  }
//---------------------------------------------

  public function insertLightEnvironment($factors, $rmData)
  {
    if ($rmData['iALight'] != '-')
    {
      $lightEnvironment = $this->insertElement($factors, 'LightEnvironment');
      $this->insertElement($lightEnvironment, 'WorkingConditions', $rmData['iALight']);
      foreach ($rmData['aALight'] as $key => $value) {
        $measuringPlace = $this->insertElement($lightEnvironment, 'MeasuringPlace');
        $this->insertElement($measuringPlace, 'Name', $value[0]['point']);
        $this->insertElement($measuringPlace, 'Date', $value[0]['dtControl']);
        $this->insertElement($measuringPlace, 'Duration', $value[0]['pointTime']);
        $this->insertElement($measuringPlace, 'VisualWorkCategory', $value[0]['catLW']);
        $this->insertElement($measuringPlace, 'WorkingConditions', $value[0]['asset']);

        foreach ($value as $key => $value2) {
          if ($value2['factorId'] == '18'){
            $generalIllumination = $this->insertElement($measuringPlace, 'GeneralIllumination');
            //echo($value2['fact'].'<br>');
            $this->insertElement($generalIllumination, 'Value', $value2['fact']);
            $this->insertElement($generalIllumination, 'NormValue', $value2['pdk']);
            $this->insertElement($generalIllumination, 'WorkingConditions', $value2['asset']);
            $this->insertElement($generalIllumination, 'NormativeAct', $value2['nd']);

            $this->insertToolsId($this->xmlData->getDevice(), $generalIllumination);
            $this->insertStuffId($this->xmlData->getStuff(), $generalIllumination);
          }
        }

        foreach ($value as $key => $value2) {
          if ($value2['factorId'] == '19'){
            $directGlare = $this->insertElement($measuringPlace, 'DirectGlare');
            $this->insertElement($directGlare, 'WorkingConditions', $value2['asset']);
            $this->insertElement($directGlare, 'FactorSource', $value2['point']);
            $this->insertElement($directGlare, 'NormativeAct', $value2['nd']);

            $this->insertStuffId($this->xmlData->getStuff(), $directGlare);
          }
        }

        foreach ($value as $key => $value2) {
          if ($value2['factorId'] == '20'){
            $reflectedGlare = $this->insertElement($measuringPlace, 'ReflectedGlare');
            $this->insertElement($reflectedGlare, 'WorkingConditions', $value2['asset']);
            $this->insertElement($reflectedGlare, 'FactorSource', $value2['point']);
            $this->insertElement($reflectedGlare, 'NormativeAct', $value2['nd']);

            $this->insertStuffId($this->xmlData->getStuff(), $reflectedGlare);
          }
        }
      }
    }
  }
//---------------------------------------------
  public function insertLabourSeverity($factors, $rmData)
  {
    if ($rmData['iAHeavyW'] != '-' && $rmData['iAHeavyM'] != '-' )
    {
      $labourSeverity = $this->insertElement($factors, 'LabourSeverity');
      $this->insertElement($labourSeverity, 'WorkingConditions', $rmData['iAHeavy']);
      $this->insertElement($labourSeverity, 'Date', $rmData['aAHeavy']['dHeavyDate']);

      $this->insertPhysicalDynamicLoad($labourSeverity, $rmData['aAHeavy']);
      $this->insertWeightLifting($labourSeverity, $rmData['aAHeavy']);
      $this->insertMotorStereotypy($labourSeverity, $rmData['aAHeavy']);
      $this->insertStaticalLoad($labourSeverity, $rmData['aAHeavy']);
      $this->insertWorkingPose($labourSeverity, $rmData['aAHeavy']);
      $this->insertBodyBending($labourSeverity, $rmData['aAHeavy']);
      $this->insertTravelling($labourSeverity, $rmData['aAHeavy']);
    }
  }

  //Мастер создания отдельных пунктов по тяжести
  public function insertPunktPhysical($razdel, $rmData, $parent, $sex, $name)
  {
    if ($rmData['aHeavyTotalM'][$razdel] != '-') {
      $child = $this->insertElement($parent, $name);
      $this->insertElement($child, 'Value', $rmData['aHeavyTotal'][$razdel]);
      $this->insertElement($child, 'NormValue', $rmData['aHeavyTotal'.$sex.'PDK'][$razdel]);
      $this->insertElement($child, 'WorkingConditions', $rmData['aHeavyTotal'.$sex][$razdel]);
      $this->insertElement($child, 'NormativeAct', $rmData['nd']);
      $this->insertToolsId($this->xmlData->getDevice(), $child);
      $this->insertStuffId($this->xmlData->getStuff(), $child);
    }
  }

  public function insertPhysicalDynamicLoad($labourSeverity, $rmData)
  {
    $physicalDynamicLoad = $this->insertElement($labourSeverity, 'PhysicalDynamicLoad');

    $this->insertPunktPhysical('11', $rmData, $physicalDynamicLoad, 'M', 'WeightMovementLess1mMale');
    $this->insertPunktPhysical('11', $rmData, $physicalDynamicLoad, 'W', 'WeightMovementLess1mFemale');
    $this->insertPunktPhysical('12', $rmData, $physicalDynamicLoad, 'M', 'WeightMovement1to5mMale');
    $this->insertPunktPhysical('12', $rmData, $physicalDynamicLoad, 'W', 'WeightMovement1to5mFemale');
    $this->insertPunktPhysical('13', $rmData, $physicalDynamicLoad, 'M', 'WeightMovementMore5mMale');
    $this->insertPunktPhysical('13', $rmData, $physicalDynamicLoad, 'W', 'WeightMovementMore5mFemale');
  }

  public function insertWeightLifting($labourSeverity, $rmData)
  {
    $weightLifting = $this->insertElement($labourSeverity, 'WeightLifting');

    $this->insertPunktPhysical('21', $rmData, $weightLifting, 'M', 'SingleWeightLiftingMale');
    $this->insertPunktPhysical('21', $rmData, $weightLifting, 'W', 'SingleWeightLiftingFemale');
    $this->insertPunktPhysical('22', $rmData, $weightLifting, 'M', 'ConstantWeightLiftingMale');
    $this->insertPunktPhysical('22', $rmData, $weightLifting, 'W', 'ConstantWeightLiftingFemale');
    $this->insertPunktPhysical('23', $rmData, $weightLifting, 'M', 'TotalWeightLiftingOffWorkSurfaceMale');
    $this->insertPunktPhysical('23', $rmData, $weightLifting, 'W', 'TotalWeightLiftingOffWorkSurfaceFemale');
    $this->insertPunktPhysical('24', $rmData, $weightLifting, 'M', 'TotalWeightLiftingOffFlorMale');
    $this->insertPunktPhysical('24', $rmData, $weightLifting, 'W', 'TotalWeightLiftingOffFlorFemale');

  }

  public function insertMotorStereotypy($labourSeverity, $rmData)
  {
    $motorStereotypy = $this->insertElement($labourSeverity, 'MotorStereotypy');

    $this->insertPunktPhysical('31', $rmData, $motorStereotypy, 'M', 'MotorStereotypyLocalLoad');
    $this->insertPunktPhysical('32', $rmData, $motorStereotypy, 'M', 'MotorStereotypyRegionalLoad');
  }

  public function insertStaticalLoad($labourSeverity, $rmData)
  {
    $staticalLoad = $this->insertElement($labourSeverity, 'StaticalLoad');

    $this->insertPunktPhysical('41', $rmData, $staticalLoad, 'M', 'StaticalLoadOneHandMale');
    $this->insertPunktPhysical('41', $rmData, $staticalLoad, 'W', 'StaticalLoadOneHandFemale');
    $this->insertPunktPhysical('42', $rmData, $staticalLoad, 'M', 'StaticalLoadTwoHandsMale');
    $this->insertPunktPhysical('42', $rmData, $staticalLoad, 'W', 'StaticalLoadTwoHandsFemale');
    $this->insertPunktPhysical('43', $rmData, $staticalLoad, 'M', 'StaticalLoadBodyMale');
    $this->insertPunktPhysical('43', $rmData, $staticalLoad, 'W', 'StaticalLoadBodyFemale');
  }

  public function insertWorkingPose($labourSeverity, $rmData)
  {
    $workingPose = $this->insertElement($labourSeverity, 'WorkingPose');
    $this->insertElement($workingPose, 'Value', $rmData['aHeavyTotal']['51']);
    $this->insertElement($workingPose, 'WorkingConditions', $rmData['aHeavyTotalM']['51']);
    $this->insertElement($workingPose, 'NormativeAct', $rmData['nd']);

    $this->insertToolsId($this->xmlData->getDevice(), $workingPose);
    $this->insertStuffId($this->xmlData->getStuff(), $workingPose);
  }

  public function insertBodyBending($labourSeverity, $rmData)
  {
    $bodyBending = $this->insertElement($labourSeverity, 'BodyBending');
    $this->insertElement($bodyBending, 'Value', $rmData['aHeavyTotal']['61']);
    $this->insertElement($bodyBending, 'NormValue', $rmData['aHeavyTotalMPDK']['61']);
    $this->insertElement($bodyBending, 'WorkingConditions', $rmData['aHeavyTotalM']['61']);
    $this->insertElement($bodyBending, 'NormativeAct', $rmData['nd']);

    $this->insertToolsId($this->xmlData->getDevice(), $bodyBending);
    $this->insertStuffId($this->xmlData->getStuff(), $bodyBending);
  }

  public function insertTravelling($labourSeverity, $rmData)
  {
    $travelling = $this->insertElement($labourSeverity, 'Travelling');

    $horizontalTravelling = $this->insertElement($travelling, 'HorizontalTravelling');
    $this->insertElement($horizontalTravelling, 'Value', $rmData['aHeavyTotal']['71']);
    $this->insertElement($horizontalTravelling, 'NormValue', $rmData['aHeavyTotalMPDK']['71']);
    $this->insertElement($horizontalTravelling, 'WorkingConditions', $rmData['aHeavyTotalM']['71']);
    $this->insertElement($horizontalTravelling, 'NormativeAct', $rmData['nd']);
    $this->insertToolsId($this->xmlData->getDevice(), $horizontalTravelling);
    $this->insertStuffId($this->xmlData->getStuff(), $horizontalTravelling);

    $verticalTravelling = $this->insertElement($travelling, 'VerticalTravelling');
    $this->insertElement($verticalTravelling, 'Value', $rmData['aHeavyTotal']['72']);
    $this->insertElement($verticalTravelling, 'NormValue', $rmData['aHeavyTotalMPDK']['72']);
    $this->insertElement($verticalTravelling, 'WorkingConditions', $rmData['aHeavyTotalM']['72']);
    $this->insertElement($verticalTravelling, 'NormativeAct', $rmData['nd']);
    $this->insertToolsId($this->xmlData->getDevice(), $verticalTravelling);
    $this->insertStuffId($this->xmlData->getStuff(), $verticalTravelling);
  }

//---------------------------------------------
    public function insertLabourIntensity($factors, $rmData)
    {
      if ($rmData['iATennese'] != '-')
      {
        $labourIntensity = $this->insertElement($factors, 'LabourIntensity');
        $this->insertElement($labourIntensity, 'WorkingConditions', $rmData['iATennese']);
        $this->insertElement($labourIntensity, 'Date', $rmData['aATennese']['dTenneseDate']);

        $this->insertLabourIntensityPunkt('1', $rmData['aATennese'], $labourIntensity, 'SignalDensity');
        $this->insertLabourIntensityPunkt('2', $rmData['aATennese'], $labourIntensity, 'ObjectsUnderControl');
        $this->insertLabourIntensityPunkt('3', $rmData['aATennese'], $labourIntensity, 'OpticalInstrumentUsage');
        $this->insertLabourIntensityPunkt('4', $rmData['aATennese'], $labourIntensity, 'VocalLoad');
        $this->insertLabourIntensityPunkt('5', $rmData['aATennese'], $labourIntensity, 'OperationMonotony');
        $this->insertLabourIntensityPunkt('6', $rmData['aATennese'], $labourIntensity, 'RaptAttentionTime');
      }
    }

    public function insertLabourIntensityPunkt($razdel, $rmData, $parent, $name)
    {
      $child = $this->insertElement($parent, $name);
      $this->insertElement($child, 'Value', $rmData['aTenneseTotal'][$razdel]);
      $this->insertElement($child, 'NormValueMin', $rmData['aTennesePDKMin'][$razdel]);
      $this->insertElement($child, 'NormValueMax', $rmData['aTennesePDKMax'][$razdel]);
      $this->insertElement($child, 'WorkingConditions', $rmData['aTenneseTotalAll'][$razdel]);
      $this->insertElement($child, 'NormativeAct', $rmData['nd']);

      if ($name != 'OperationMonotony' && $name != 'ObjectsUnderControl'){
        $this->insertToolsId($this->xmlData->getDevice(), $child);
      }
      $this->insertStuffId($this->xmlData->getStuff(), $child);
    }
//---------------------------------------------
  public function getDocument()
  {
    return $this->document->saveXML();
  }

  public function insertElement($root, $name, $val=null)
  {
    $element = $this->document->createElement($name, $val);
    $root->appendChild($element);
    return $element;
  }
}

?>

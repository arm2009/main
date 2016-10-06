<?php
include_once('LowLevel/dbConnect.php');
include_once('UserControl/userControl.php');
include_once('MainWork/WorkFactors.php');

class xmlData
{
  public $idGroup = -1;
  public $idUser = -1;
  public $dataGroup;
  public $attestationOrganisation;
  public $attestationExpert;
  public $attestationStuff;
  public $attestationCacheStuff;
  public $attestationDevice;
  public $attestationCacheDevice;
  public $attestationAccredit;
  public $attestationAccreditMy;
  public $attestationAccreditOther;
  public $aClassPattern;
  public $aCatWorkPattern;
  public $aCatWorkPdu;
  public $aCatWorkPduAirSpeed;
  public $aCatWorkPduTNS;
  public $aCatWorkHeavy;
  public $resultRm;
  public $resultComitee;

  public function __construct($idGroup)
  {
    $this->idGroup = $idGroup;

    //Паттерн классов
    $this->aClassPattern = array('0' => '-', '1' => '1', '2' => '2', '3' => '3.1', '4' => '3.2', '5' => '3.3', '6' => '3.4', '7' => '4');
    //Категории работ
    $this->aCatWorkPattern = array('0' => 'Iа', '1' => 'Iб', '2' => 'IIа', '3' => 'IIб', '4' => 'III');
    $this->aCatWorkPdu = array('min' => array('0' => '24.1', '1' => '23.1', '2' => '21.1', '3' => '19.1', '4' => '18.1'), 'max' => array('0' => '25', '1' => '24', '2' => '23', '3' => '22', '4' => '21'));
    $this->aCatWorkPduAirSpeed = array('0' => '0.1', '1' => '0.2', '2' => '0.3', '3' => '0.4', '4' => '0.4');
    $this->aCatWorkPduTNS = array('0' => '26.5', '1' => '25.9', '2' => '25.2', '3' => '24', '4' => '21.9');
    $this->aCatWorkHeavy = array('-'=> '1', '0' => '1', '1' => '4', '2' => '7', '3' => '11');

    //Группа данных
    $result = DbConnect::GetSqlQuery('SELECT * FROM Arm_group WHERE id ='.$idGroup.';');
    $this->dataGroup = mysql_fetch_row($result, MYSQL_ASSOC);

    //Владелец
    $this->idUser = $this->dataGroup['idParent'];

    //Организация проводившая измерения
    $this->attestationOrganisation = array();

    //Експерты
    $this->attestationExpert = DbConnect::GetSqlQuery('SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = '.$this->dataGroup['id'].' AND `bExpert` = 1;');
    $this->attestationStuff = DbConnect::GetSqlQuery('SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = '.$this->dataGroup['id'].' AND `bExpert` = 0;');

    //Приборы
    $this->attestationDevice = DbConnect::GetSqlQuery('SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = '.$this->dataGroup['id'].';');

    //Аттестат аккредитации
    $this->attestationAccreditOther = array();
    $this->attestationAccredit = DbConnect::GetSqlQuery('SELECT * FROM `Arm_groupAcredit` WHERE `idGroup` = '.$this->dataGroup['id'].';');

    $this->resultRm = DbConnect::GetSqlQuery('SELECT g.sName as `Division`,
(SELECT GROUP_CONCAT(Arm_rmPoints.sName SEPARATOR ", ") FROM Arm_rmPoints LEFT JOIN Arm_rmPointsRm ON Arm_rmPointsRm.idPoint = Arm_rmPoints.id WHERE Arm_rmPointsRm.idRm = w.id AND Arm_rmPoints.iType=1 GROUP BY Arm_rmPointsRm.idRm) as Equipment,
(SELECT GROUP_CONCAT(Arm_rmPoints.sName SEPARATOR ", ") FROM Arm_rmPoints LEFT JOIN Arm_rmPointsRm ON Arm_rmPointsRm.idPoint = Arm_rmPoints.id WHERE Arm_rmPointsRm.idRm = w.id AND Arm_rmPoints.iType=2 GROUP BY Arm_rmPointsRm.idRm) as Materials,
w.*  FROM `Arm_workplace` as w LEFT JOIN Arm_workplace as g ON w.idParent = g.id WHERE w.`idGroup` = '.$this->idGroup.' AND w.idParent > -1;');
    $this->resultComitee = DbConnect::GetSqlQuery('SELECT `sName`, `sPost` FROM Arm_comiss WHERE idParent ='.$this->idGroup);

    //Данные рабочих мест

  }

  public function getRm()
  {
    $aRM = array();
    while ($row = mysql_fetch_row($this->resultRm, MYSQL_ASSOC))
    {
      //Время смены
      $RMWorkDay = DbConnect::GetSqlCell("SELECT fWorkDay FROM Arm_workplace WHERE id =".$row['id'].";");

      //Мероприятия
      $aRecomendations = array();
      $Recomendations = DbConnect::GetSqlQuery('SELECT * FROM `Arm_activity` WHERE `iRmId` = '.$row['id'].' AND `iType` = 0');
      while ($aRow = mysql_fetch_row($Recomendations, MYSQL_ASSOC))
      {
        $aRecomendations[] = $aRow;
      }
      //Данные
      $row['sOk'] = sprintf("%05d", (int) substr($row['sOk'],0,5));

      //СНИЛС
      //TODO: Указать в форме заполнения ниаболее подходящий вариант разделения снилс, в поле
      $aSnils = array();
      preg_match_all("/\d{3}-\d{3}-\d{3}( |-)\d{2}/", $row[sSnils], $aSnils);
      if(count($aSnils[0]) == 0) $aSnils[0][] = "Отсутствует";
      $row[sSnils] = $aSnils[0];
      $row[iATotal] = $this->aClassPattern[$row[iATotal]];
      $row[iAChem] = $this->aClassPattern[$row[iAChem]];
      $row[iABio] = $this->aClassPattern[$row[iABio]];
      $row[iAAPFD] = $this->aClassPattern[$row[iAAPFD]];
      $row[iANoise] = $this->aClassPattern[$row[iANoise]];
      $row[iAInfraNoise] = $this->aClassPattern[$row[iAInfraNoise]];
      $row[iAUltraNoise] = $this->aClassPattern[$row[iAUltraNoise]];
      $row[iAVibroO] = $this->aClassPattern[$row[iAVibroO]];
      $row[iAVibroL] = $this->aClassPattern[$row[iAVibroL]];
      $row[iANoIon] = $this->aClassPattern[$row[iANoIon]];
      $row[iAIon] = $this->aClassPattern[$row[iAIon]];
      $row[iAMicroclimat] = $this->aClassPattern[$row[iAMicroclimat]];
      $row[iALight] = $this->aClassPattern[$row[iALight]];
      $row[iAHeavy] = $this->aClassPattern[$row[iAHeavy]];
      $row[iAHeavyW] = $this->aClassPattern[$row[iAHeavyW]];
      $row[iAHeavyM] = $this->aClassPattern[$row[iAHeavyM]];
      $row[iATennese] = $this->aClassPattern[$row[iATennese]];
      //FIXME: Рассчитать и внести в базу данных

      //Факторы
      $aChem = array();
      $aBioPM = array();
      $aBioMP = array();
      $aAAPFD = array();
      $aNOISE = array();
      $aInfraNoise = array();
      $aUltraNoise = array();
      $aVibroO = array();
      $aVibroL = array();
      $aNoIon = array();
      $aIon = array();
      $aMicroclimat = array();
      $aLight = array();
      $aHeavy = array();
      $aTennese = array();

      //Тяжесть под заполнение
      $dHeavyDate = '';
      $aHeavyTotal = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
      $aHeavyTotalM = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
      $aHeavyTotalW = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
      $aHeavyTotalMPDK = array(11=>5000,12=>25000,13=>46000,21=>30,22=>15,23=>870,24=>435,31=>40000,32=>20000,41=>36000,42=>70000,43=>10000,51=>1,61=>100,71=>8,72=>2.5);
      $aHeavyTotalWPDK = array(11=>3000,12=>15000,13=>28000,21=>10,22=>7,23=>350,24=>175,31=>40000,32=>20000,41=>22000,42=>42000,43=>60000,51=>1,61=>100,71=>8,72=>2.5);
      $bHeavy = false;

      //Напряженность под заполнение
      $dTenneseDate = '';
      $aTenneseTotal = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
			$aTenneseTotalAll = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
			$bTennese = false;
			$bTennese5 = false;

      $aPointList = WorkFactors::GetPointsList($row[id]); //Перечень зон
      foreach ($aPointList as $pkey => $pvalue) {
        $aFactorList = WorkFactors::GetFactorsList($pvalue[0],$row[id]);
        if(is_array($aFactorList))
        foreach ($aFactorList as $fkey => $fvalue) {
          //Химия
          if($fvalue[16] == 31)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['code'] = (int) trim(DbConnect::GetSqlCell('SELECT Nd_gn1313.sNum FROM `Nd_gn1313` WHERE `id` = '.$fvalue[15].';'));
            $aRowTmp['pdkM'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['pdkS'] = $fvalue[11] == -1 ? null : $fvalue[11];
            $aRowTmp['factM'] = $fvalue[3];
            $aRowTmp['factS'] = $fvalue[7];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            $aRowTmp['nd'] = 'ГН 2.2.5.1313-03';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aChem[] = $aRowTmp;
          }
          //Биология
          if($fvalue[16] == 33 && $fvalue[15] == 34)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['code'] = 0;
            $aRowTmp['pdk'] = $fvalue[5] == -1 ? 0 : $fvalue[5];
            $aRowTmp['fact'] = 1;
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            $aRowTmp['nd'] = 'Пр. №33н от 21.03.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aBioMP[] = $aRowTmp;
          }
          if($fvalue[16] == 33 && $fvalue[15] != 34)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['code'] = 0;
            $aRowTmp['fact'] = 1;
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            $aRowTmp['nd'] = 'Пр. №33н от 21.03.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aBioPM[] = $aRowTmp;
          }
          //АПФД
          if($fvalue[16] == 8)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['code'] = (int) trim(DbConnect::GetSqlCell('SELECT Nd_gn1313.sNum FROM `Nd_gn1313` WHERE `id` = '.$fvalue[15].';'));
            $aRowTmp['pdkM'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['pdkS'] = $fvalue[11] == -1 ? null : $fvalue[11];
            $aRowTmp['factM'] = $fvalue[3];
            $aRowTmp['factS'] = $fvalue[7];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            $aRowTmp['nd'] = 'ГН 2.2.5.1313-03';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aAAPFD[] = $aRowTmp;
          }
          //Шум
          if($fvalue[15] == 13)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['pdkM'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['factM'] = $fvalue[3];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            $aRowTmp['nd'] = 'ГОСТ Р ИСО 9612-2013';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aNOISE[] = $aRowTmp;
          }
          //Инфразвук
          if($fvalue[15] == 14)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['pdkM'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['factM'] = $fvalue[3];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            //FIXME: Указать корректный НД
            $aRowTmp['nd'] = 'Пр. 33н от 24.01.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aInfraNoise[] = $aRowTmp;
          }
          //Ультразвук
          if($fvalue[15] == 15)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['pdkM'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['factM'] = $fvalue[3];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            //FIXME: Указать корректный НД
            $aRowTmp['nd'] = 'Пр. 33н от 24.01.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            //FIXME: Октавные значения
            $aRowTmp['aOctave'] = array(
            '41' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '42' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '43' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '44' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '45' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '46' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '47' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '48' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '49' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']),
            '50' => array('Value' => $fvalue[3], 'NormValue' => $aRowTmp['pdkM']));
            $aUltraNoise[] = $aRowTmp;
          }
          //Локальная вибрация
          if($fvalue[15] == 54)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['pdkX'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['pdkY'] = $fvalue[11] == -1 ? null : $fvalue[11];
            $aRowTmp['pdkZ'] = $fvalue[12] == -1 ? null : $fvalue[12];
            $aRowTmp['factX'] = $fvalue[3];
            $aRowTmp['factY'] = $fvalue[7];
            $aRowTmp['factZ'] = $fvalue[8];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            //FIXME: Указать корректный НД
            $aRowTmp['nd'] = 'Пр. 33н от 24.01.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aVibroL[] = $aRowTmp;
          }
          //Общая вибрация
          if($fvalue[15] == 16)
          {
            //Забой
            $aRowTmp = array();
            $aRowTmp['pdkX'] = $fvalue[5] == -1 ? null : $fvalue[5];
            $aRowTmp['pdkY'] = $fvalue[11] == -1 ? null : $fvalue[11];
            $aRowTmp['pdkZ'] = $fvalue[12] == -1 ? null : $fvalue[12];
            $aRowTmp['factX'] = $fvalue[3];
            $aRowTmp['factY'] = $fvalue[7];
            $aRowTmp['factZ'] = $fvalue[8];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            //FIXME: Указать корректный НД
            $aRowTmp['nd'] = 'Пр. 33н от 24.01.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aVibroO[] = $aRowTmp;
          }
          //Микроклимат
          if($fvalue[16] == 1)
          {
            //Забой
            $aRowTmp = array();

            $aRowTmp['factorId'] = (string) $fvalue[15];
            $aRowTmp['pdk'] = $this->aCatWorkPattern[$fvalue[5]];
            $aRowTmp['pdkMin'] = $this->aCatWorkPdu['min'][$fvalue[5]];
            $aRowTmp['pdkMax'] = $this->aCatWorkPdu['max'][$fvalue[5]];
            $aRowTmp['pdkMinAH'] = 15;
            $aRowTmp['pdkMaxAH'] = 75;
            $aRowTmp['pdkHRI'] = 140;
            $aRowTmp['pdkTNS'] = $this->aCatWorkPduTNS[$fvalue[5]];
            $aRowTmp['pdkAirSpeed'] = $this->aCatWorkPduAirSpeed[$fvalue[5]];

            $aRowTmp['fact'] = $fvalue[3];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            //FIXME: Указать корректно позу 0-стоя, 1-сидя
            $aRowTmp['posture'] = 0;
            $aRowTmp['h1'] = 1;
            $aRowTmp['h2'] = 1.5;
            $aRowTmp['h3'] = 0.5;
            //FIXME: Указать корректный НД
            $aRowTmp['nd'] = 'Пр. 33н от 24.01.2014';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            echo("[".max($fvalue[18], $aMicroclimat[$fvalue[1]]['zoneAsset'])."]");
            $aMicroclimat[$fvalue[1]]['zoneAsset'] = max($fvalue[18], $aMicroclimat[$fvalue[1]]['zoneAsset']);
            $aMicroclimat[$fvalue[1]][] = $aRowTmp;
          }
          //Свет
          if($fvalue[16] == 17)
          {
            //Забой
            $aRowTmp = array();

            $aRowTmp['factorId'] = (string) $fvalue[15];
            //FIXME: Вывести разряд зрительной работы
            $aRowTmp['catLW'] = 4; //Разряд зрительной работы
            $aRowTmp['pdk'] = $fvalue[5];
            $aRowTmp['factAll'] = $fvalue[3];
            $aRowTmp['fact'] = $fvalue[7];
            $aRowTmp['asset'] = $this->aClassPattern[$fvalue[18]];
            $aRowTmp['point'] = $pvalue[2];
            $aRowTmp['pointTime'] = round($pvalue[3]/$RMWorkDay);
            //FIXME: Указать корректный НД
            $aRowTmp['nd'] = 'ГОСТ Р ИСО 9612-2013';
            $aRowTmp['dtControl'] = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            $aLight[$fvalue[1]][] = $aRowTmp;
          }
          //Тяжесть
          if($fvalue[16] == 37)
          {
            $dHeavyDate = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            switch($fvalue[15])
  					{
              //Физическая динамическая нагрузка
  						case 39:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyFD($fvalue[3], $fvalue[7], $fvalue[8]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[11] += $fvalue[3];
  							$aHeavyTotal[12] += $fvalue[7];
  							$aHeavyTotal[13] += $fvalue[8];
  						break;
  						//Масса поднимаемого и перемещаемого груза вручную
  						case 40:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPiP($fvalue[3], $fvalue[7], $fvalue[8], $fvalue[9]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[21] = max($fvalue[3], $aHeavyTotal[21]);
  							$aHeavyTotal[22] = max($fvalue[7], $aHeavyTotal[22]);
  							$aHeavyTotal[23] += $fvalue[8];
  							$aHeavyTotal[24] += $fvalue[9];
  						break;
  						//Стереотипные рабочие движения
  						case 41:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySD($fvalue[3], $fvalue[7]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[31] += $fvalue[3];
  							$aHeavyTotal[32] += $fvalue[7];
  						break;
  						//Статическая нагрузка
  						case 42:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySN($fvalue[3], $fvalue[7], $fvalue[8]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[41] += $fvalue[3];
  							$aHeavyTotal[42] += $fvalue[7];
  							$aHeavyTotal[43] += $fvalue[8];
  						break;
  						//Рабочая поза
  						case 43:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyRP($fvalue[3]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[51] = max($fvalue[3], $aHeavyTotal[51]);
  						break;
  						//Наклоны корпуса тела работника
  						case 44:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyNK($fvalue[3]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[61] += $fvalue[3];
  						break;
  						//Перемещение в пространстве
  						case 45:
  							$bHeavy = true;
  							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPP($fvalue[3], $fvalue[7]);
  							$tmparr = explode(',', $tmpAddonAsset);
  							$tmpAsset = max($tmparr);
  							//Общая тяжесть
  							$aHeavyTotal[71] += $fvalue[3];
  							$aHeavyTotal[72] += $fvalue[7];
  						break;
            }
          }
          //Напряженность
          if($fvalue[16] == 46)
          {
            $dTenneseDate = StringWork::StrToDateMysqlFormatLite($fvalue[19]);
            switch($fvalue[15])
  					{
              //Плотность сигналов (световых, звуковых) и сообщений в единицу времени
  						case 48:
  							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_PS($fvalue[3]);
  							//Общая напряженность
  							$bTennese = true;
  							$aTenneseTotal[1] = max($fvalue[3], $aTenneseTotal[1]);
  						break;
  						//Число производственных объектов одновременного набл юдения
  						case 49:
  							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_OC($fvalue[3]);
  							//Общая напряженность
  							$bTennese = true;
  							$aTenneseTotal[2] = max($fvalue[3], $aTenneseTotal[2]);
  						break;
  						//Работа с оптическими приборами
  						case 52:
  							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_OP($fvalue[3]);
  							//Общая напряженность
  							$bTennese = true;
  							$aTenneseTotal[3] += $fvalue[3];
  						break;
  						//Нагрузка на голосовой аппарат
  						case 53:
  							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_GA($fvalue[3]);
  							//Общая напряженность
  							$bTennese = true;
  							$aTenneseTotal[4] += $fvalue[3];
  						break;
  						//Число элементов (приемов), необходимых для реализации простого задания или многократно повторяющихся операций
  						case 65:
  							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_PO($fvalue[3]);
  							//Общая напряженность
  							$bTennese = true;
  							$aTenneseTotal[5] = min($fvalue[3], $aTenneseTotal[5]);
  						break;
  						//Монотонность производственной обстановки (время пассивного наблюдения за ходом технологического процесса в % от времени смены)
  						case 66:
  							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_MO($fvalue[3]);
  							//Общая напряженность
  							$bTennese = true;
  							$aTenneseTotal[6] += $fvalue[3];
  						break;
            }
          }
        }
      }

      if($bHeavy)
      {
        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyFD($aHeavyTotal[11], $aHeavyTotal[12], $aHeavyTotal[13]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[11] = $tmparr[0];
        $aHeavyTotalM[12] = $tmparr[1];
        $aHeavyTotalM[13] = $tmparr[2];
        $aHeavyTotalW[11] = $tmparr[3];
        $aHeavyTotalW[12] = $tmparr[4];
        $aHeavyTotalW[13] = $tmparr[5];

        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPiP($aHeavyTotal[21], $aHeavyTotal[22], $aHeavyTotal[23], $aHeavyTotal[24]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[21] = $tmparr[0];
        $aHeavyTotalM[22] = $tmparr[1];
        $aHeavyTotalM[23] = $tmparr[2];
        $aHeavyTotalM[24] = $tmparr[3];
        $aHeavyTotalW[21] = $tmparr[4];
        $aHeavyTotalW[22] = $tmparr[5];
        $aHeavyTotalW[23] = $tmparr[6];
        $aHeavyTotalW[24] = $tmparr[7];

        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySD($aHeavyTotal[31], $aHeavyTotal[32]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[31] = $tmparr[0];
        $aHeavyTotalM[32] = $tmparr[1];
        $aHeavyTotalW[31] = $tmparr[0];
        $aHeavyTotalW[32] = $tmparr[1];

        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySN($aHeavyTotal[41], $aHeavyTotal[42], $aHeavyTotal[43]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[41] = $tmparr[0];
        $aHeavyTotalM[42] = $tmparr[1];
        $aHeavyTotalM[43] = $tmparr[2];
        $aHeavyTotalW[41] = $tmparr[3];
        $aHeavyTotalW[42] = $tmparr[4];
        $aHeavyTotalW[43] = $tmparr[5];

        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyRP($aHeavyTotal[51]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[51] = $tmparr[0];
        $aHeavyTotalW[51] = $tmparr[0];

        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyNK($aHeavyTotal[61]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[61] = $tmparr[0];
        $aHeavyTotalW[61] = $tmparr[0];

        $tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPP($aHeavyTotal[71], $aHeavyTotal[72]);
        $tmparr = explode(',', $tmpAddonAsset);
        $aHeavyTotalM[71] = $tmparr[0];
        $aHeavyTotalM[72] = $tmparr[1];
        $aHeavyTotalW[71] = $tmparr[0];
        $aHeavyTotalW[72] = $tmparr[1];
      }

      //Перегон оценок
      foreach ($aHeavyTotalM as $key => $value) {
        $aHeavyTotalM[$key] = $this->aClassPattern[$aHeavyTotalM[$key]];
        $aHeavyTotalW[$key] = $this->aClassPattern[$aHeavyTotalW[$key]];
      }

      $row['aAHeavy']['dHeavyDate'] = $dHeavyDate;
      $row['aAHeavy']['nd'] = 'Пр. 33н от 24.01.2014';

      $row['aAHeavy']['aHeavyTotal'] = $aHeavyTotal;
      $row['aAHeavy']['aHeavyTotal'][51] = $this->aCatWorkHeavy[$row['aAHeavy']['aHeavyTotal'][51]];
      $row['aAHeavy']['aHeavyTotalM'] = $aHeavyTotalM;
      $row['aAHeavy']['aHeavyTotalW'] = $aHeavyTotalW;

      $row['aAHeavy']['aHeavyTotalMPDK'] = $aHeavyTotalMPDK;
      $row['aAHeavy']['aHeavyTotalWPDK'] = $aHeavyTotalWPDK;

      //Новая напряженность
			if($bTennese || $bTennese5)
			{
				$aTenneseTotalAll[1] = WorkFactors::GetFactorAsset_Tennese_PS($aTenneseTotal[1]);
				$aTenneseTotalAll[2] = WorkFactors::GetFactorAsset_Tennese_OC($aTenneseTotal[2]);
				$aTenneseTotalAll[3] = WorkFactors::GetFactorAsset_Tennese_OP($aTenneseTotal[3]);
				$aTenneseTotalAll[4] = WorkFactors::GetFactorAsset_Tennese_GA($aTenneseTotal[4]);
				if($bTennese5)
				$aTenneseTotalAll[5] = WorkFactors::GetFactorAsset_Tennese_PO($aTenneseTotal[5]);
				$aTenneseTotalAll[6] = WorkFactors::GetFactorAsset_Tennese_MO($aTenneseTotal[6]);
			}

      foreach ($aTenneseTotalAll as $key => $value) {
        $aTenneseTotalAll[$key] = $this->aClassPattern[$aTenneseTotalAll[$key]];
      }

      $row['aATennese']['dTenneseDate'] = $dTenneseDate;
      $row['aATennese']['nd'] = 'Пр. 33н от 24.01.2014';
      $row['aATennese']['aTenneseTotal'] = $aTenneseTotal;
      $row['aATennese']['aTenneseTotalAll'] = $aTenneseTotalAll;

      $row['aATennese']['aTennesePDKMin'] = array('1' => '0', '2' => '0', '3' => '0', '4' => '0', '5' => '6', '6' => '0');
      $row['aATennese']['aTennesePDKMax'] = array('1' => '175', '2' => '10', '3' => '50', '4' => '80', '5' => '1000', '6' => '80');

      //Вывод конечных данных
      $row['aChem'] = $aChem;
      $row['aBioPM'] = $aBioPM;
      $row['aBioMP'] = $aBioMP;
      $row['aAAPFD'] = $aAAPFD;
      $row['aNOISE'] = $aNOISE;
      $row['aAInfraNoise'] = $aInfraNoise;
      $row['aAUltraNoise'] = $aUltraNoise;
      $row['aAVibroO'] = $aVibroO;
      $row['aAVibroL'] = $aVibroL;
      $row['aANoIon'] = $aNoIon;
      $row['aAIon'] = $aIon;
        //Приведение оценки по зоне
        foreach ($aMicroclimat as $key => $value) {
          $aMicroclimat[$key]['zoneAsset'] = $this->aClassPattern[$value['zoneAsset']];
        }
      $row['aAMicroclimat'] = $aMicroclimat;
      $row['aALight'] = $aLight;


      $row['Recomendations'] = $aRecomendations;
      $aRM[] = $row;
    }
    return $aRM;
  }

  public function getAttestationAccreditMy()
  {
    $this->attestationAccreditMy = mysql_fetch_row($this->attestationAccredit, MYSQL_ASSOC);
    return $this->attestationAccreditMy;
  }

  public function getDataGroup()
  {
    return $this->dataGroup;
  }

  public function getAttestationAccreditOther()
  {
    $bFirst = true;
    while ($row = mysql_fetch_row($this->attestationAccredit, MYSQL_ASSOC)) {
      if($bFirst) $bFirst = false; else
      $this->attestationAccreditOther[] = $row;
    }
    return $this->attestationAccreditOther;
  }

  public function getAttestationOrganisation()
  {
    $this->attestationOrganisation['sOrgName'] = UserControl::GetUserFieldValueFromId('sOrgName',$this->idUser);
    $this->attestationOrganisation['sOrgRegNum'] = UserControl::GetUserFieldValueFromId('sOrgRegNum',$this->idUser);
    $this->attestationOrganisation['sOrgDate'] = StringWork::StrToDateMysqlFormatLite(UserControl::GetUserFieldValueFromId('sOrgDate',$this->idUser));
    $this->attestationOrganisation['sOrgOgrn'] = UserControl::GetUserFieldValueFromId('sOrgOgrn',$this->idUser);
    $this->attestationOrganisation['sOrgInn'] = UserControl::GetUserFieldValueFromId('sOrgInn',$this->idUser);
    $this->attestationOrganisation['sOrgPhone'] = UserControl::GetUserFieldValueFromId('sOrgPhone',$this->idUser);
    $this->attestationOrganisation['sOrgAdress'] = UserControl::GetUserFieldValueFromId('sOrgAdress',$this->idUser);
    $this->attestationOrganisation['sName'] = UserControl::GetUserFieldValueFromId('sName',$this->idUser);
    $this->attestationOrganisation['sFirstFaceName'] = UserControl::GetUserFieldValueFromId('sFirstFaceName',$this->idUser);
    $this->attestationOrganisation['sOrgAdress'] = UserControl::GetUserFieldValueFromId('sOrgAdress',$this->idUser);
    $this->attestationOrganisation['sOrgAdress'] = UserControl::GetUserFieldValueFromId('sOrgAdress',$this->idUser);
    $this->attestationOrganisation['sOrgAdress'] = UserControl::GetUserFieldValueFromId('sOrgAdress',$this->idUser);
    return $this->attestationOrganisation;
  }

  public function getReportDate()
  {
    // TODO: Во вкладке дополнительно http://arm2009.ru/work_MainInfo.php?action=edit&id=702 добавить редактируемое поле дата утверждения отчета 'dReportDate'
    if($this->dataGroup['dReportDate'] == '0000-00-00')
    $this->dataGroup['dReportDate'] = date('Y-m-d');
    return $this->dataGroup['dReportDate'];
  }

  public function getRmCountTotal()
  {
    //iRmTotalCount
    $rmTotalCount = $this->dataGroup['iRmTotalCount'];
      $baseRmTotalCount = mysql_num_rows($this->resultRm);

    if ($baseRmTotalCount>$rmTotalCount)
    {
      return $baseRmTotalCount;
    }
    return $rmTotalCount;
  }

  public function getRmCount()
  {
    return mysql_num_rows($this->resultRm);
  }

  public function getRegion()
  {
    $reg = $this->dataGroup['sRegion'];
    if ($reg == '') { $reg = '77';}
    return $reg;
  }

  public function getExpertConclusion()
  {
    $docNum = $this->dataGroup['sExpEndDoc'];
    $docDate = $this->dataGroup['sExpEndDate'];
    return $docNum.', '.$docDate;
  }

  public function getComitee()
  {
    //Председатель
    $aComitee = array(array('sName' => $this->dataGroup['sPredsName'], 'sPost' => $this->dataGroup['sPredsPost']));
    //Комиссия
    while ($ComiteeRow = mysql_fetch_array($this->resultComitee, MYSQL_ASSOC))
    {
      $aComitee[] = $ComiteeRow;
    }
    return $aComitee;
  }
  public function getExpert()
  {
    $aExpert = array();
    while ($ExpertRow = mysql_fetch_array($this->attestationExpert, MYSQL_ASSOC))
    {
      $aExpert[] = $ExpertRow;
    }
    return $aExpert;
  }
  public function getStuff()
  {
    if(is_array($this->attestationCacheStuff))
    {
      return $this->attestationCacheStuff;
    }
    else {
      $aStuff = array();
      while ($StuffRow = mysql_fetch_array($this->attestationStuff, MYSQL_ASSOC))
      {
        $aStuff[] = $StuffRow;
      }
      $this->attestationCacheStuff = $aStuff;
      return $aStuff;
    }
  }
  public function getDevice()
  {
    if(is_array($this->attestationCacheDevice))
    {
      return $this->attestationCacheDevice;
    }
    else {
      $aDevice = array();
      while ($DeviceRow = mysql_fetch_array($this->attestationDevice, MYSQL_ASSOC))
      {
        $DeviceRow['dCheckDate'] = StringWork::StrToDateMysqlFormatLite($DeviceRow['dCheckDate']);
        if(!preg_match('/(\d{5}-\d{2})|(\d{4}-\d{2})|(\d{3}-\d{2})|(\d{2}-\d{2})|(Не сертифицируется)/', $DeviceRow['sReestrNum'])) $DeviceRow['sReestrNum'] = 'Не сертифицируется';

        $aDevice[] = $DeviceRow;
      }
      $this->attestationCacheDevice = $aDevice;
      return $aDevice;
    }
  }
}

?>

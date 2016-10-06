<?
	ini_set('memory_limit','128M');
	//Базовые данные
	//Рабочего места
	$sql = "SELECT * FROM `Arm_workplace` WHERE `id` = ".$target.";";
	$vResultRm = DbConnect::GetSqlRow($sql);
	
	switch($vResultRm[iSIZOEffect])
	{
		case 1:
			$vResultRm[iSIZOEffect] = '—';
		break;
		case 2:
			$vResultRm[iSIZOEffect] = 'Не оценивалась';
		break;
		default:
			$vResultRm[iSIZOEffect] = '—';
		break;
	}
	
	$sql = "SELECT * FROM `Arm_group` WHERE `id` = ".$vResultRm[idGroup].";";
	$vResultOrg = DbConnect::GetSqlRow($sql);
	
	//Значение группы для формируемого документа
	$pdf->tmpOrgName = '<br>'.$vResultOrg[sFullName];
	$pdf->tmpDocType = 'Карта специальной оценки условий труда № '.$vResultRm[iNumber];

	$sDocName = '0.4_'.$vResultRm[iNumber].'_SOUT_Card.pdf';
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');

	//Наименование рабочего места
	if(strlen(trim($vResultRm[sOk])) > 0)
	$vResultRm[sName] = $vResultRm[sName] .' ('.$vResultRm[sOk].')';
	
	if (strlen($vResultRm[sNumAnalog]) > 0)
	{
		$vResultRm[iNumber] = $vResultRm[iNumber].'А';
	}

	//Аналогичные места
	$tmpCount = explode(',', $vResultRm[sNumAnalog]);
	
	//Получение количества работников
	try
	{
		$sTmp = preg_replace("|[^0-9,.]|i", "", $vResultRm[sNumAnalog]);
		$aTmpCheck = explode(',', $sTmp);
		$bAllNumeric = true;
		foreach ($aTmpCheck as $aValCheck)
		{
			if (!is_numeric($aValCheck))
			{
				$bAllNumeric = false;
			}
		}
/*		$sTmp = str_replace('а', '', $vResultRm[sNumAnalog]);
		$sTmp = str_replace('А', '', $sTmp);
		$sTmp = str_replace('a', '', $sTmp);
		$sTmp = str_replace('A', '', $sTmp);*/
		if(strlen($sTmp)>0 && $bAllNumeric)
		{
			$sql = "SELECT SUM(`iCount`) AS `Summa1`, SUM(`iCountWoman`) AS `Summa2`, SUM(`iCountYouth`) AS `Summa3`, SUM(`iCountDisabled`) AS `Summa4` FROM `Arm_workplace` WHERE `iNumber` IN (".$sTmp.") AND `idGroup` = ".$vResultRm[idGroup].';';
			$vResultTmp = DbConnect::GetSqlRow($sql);
				if (is_numeric($vResultTmp[Summa1]))
				{
					$iWorkCountAnalog = $vResultTmp[Summa1] + $vResultRm[iCount];
					$iWorkCountAnalog1 = $vResultTmp[Summa2] + $vResultRm[iCountWoman];
					$iWorkCountAnalog2 = $vResultTmp[Summa3] + $vResultRm[iCountYouth];
					$iWorkCountAnalog3 = $vResultTmp[Summa4] + $vResultRm[iCountDisabled];
				}
				else
				{
					$iWorkCountAnalog = 0;
					$iWorkCountAnalog1 = $vResultRm[iCountWoman];
					$iWorkCountAnalog2 = $vResultRm[iCountYouth];
					$iWorkCountAnalog3 = $vResultRm[iCountDisabled];
				}                                                       
		}
		else
		{
			$iWorkCountAnalog = 0;
			$iWorkCountAnalog1 = $vResultRm[iCountWoman];
			$iWorkCountAnalog2 = $vResultRm[iCountYouth];
			$iWorkCountAnalog3 = $vResultRm[iCountDisabled];
		}
	}
	catch(Exception $ex){$iWorkCountAnalog = 0;}
	
	if(count($tmpCount) > 0  && strlen(trim($vResultRm[sNumAnalog])) > 0)
	{ $vResultRm[sNumAnalog] = count($tmpCount) .' ('.$vResultRm[sNumAnalog].').'; }
	else
	{ $vResultRm[sNumAnalog] = StringWork::CheckNullStrFull(''); }
	
	//Наименование подразделения
	$sql = "SELECT `sName` FROM `Arm_workplace` WHERE `id` = ".$vResultRm[idParent].";";
	$vResultParent = DbConnect::GetSqlRow($sql);
	
	//Список оборудования и сырья
	$sqlP = "SELECT `Arm_rmPoints`.`sName`, `Arm_rmPointsRm`.`sTime` FROM `Arm_rmPoints`, `Arm_rmPointsRm` WHERE `Arm_rmPoints`.`id` = `Arm_rmPointsRm`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = ".$target." AND `Arm_rmPoints`.`iType` <> 0 ORDER BY `Arm_rmPoints`.`iType`;";
	$vResultP = DbConnect::GetSqlQuery($sqlP);
	if (mysql_num_rows($vResultP) > 0)
	{				
		while($vRowP = mysql_fetch_array($vResultP))
		{
			if(strlen($vResultOborud) > 0){$vResultOborud .= ', ';}
			$vResultOborud .= $vRowP[sName];
		}
	}
	if(strlen($vResultOborud) > 0){$vResultOborud .= '.';}
	
	//30 строка тяжесть
	if($vResultRm[iAHeavy] > 0)
	{
		if($vResultRm[iAHeavyM] == $vResultRm[iAHeavyW])
		{
			$sHeavycomment = '';
			$vResultRm[iAHeavy] = StringWork::iToClassNameLite($vResultRm[iAHeavy]);
		}
		else
		{
			$sHeavycomment = '<br />** &#8212; В числителе оценка для мужчин, в знаменателе для женщин.';
			$vResultRm[iAHeavy] = StringWork::iToClassNameLite($vResultRm[iAHeavyM]).'/'.StringWork::iToClassNameLite($vResultRm[iAHeavyW]).'**';
		}
	}
	else
	{
		$sHeavycomment = '';
		$vResultRm[iAHeavy] = StringWork::iToClassNameLite($vResultRm[iAHeavy]);
	}
	
	//Мероприятия
	$sql = "SELECT `sActivityName` FROM `Arm_activity` WHERE `iRmId` = ".$target.";";
	$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
	$sActivity = '';
	while ($vRow = mysql_fetch_assoc($vResult))
	{
		if(strlen($sActivity) > 0){$sActivity .= ';<br />';}
		$sActivity .= $vRow[sActivityName];
	}
	
	//Шапка
	$html ='
<table border="0.5" cellspacing="0" cellpadding="5" width="100%">
 <tr>
    <td width="100%" colspan="5"><p align="center">'.$vResultOrg[sFullName].'</p></td>
  </tr>
  <tr>
    <td width="100%" colspan="5"><p align="center">'.$vResultOrg[sPlace].', '.$vResultOrg[sPostDirector].' '.$vResultOrg[sNameDirector].', '.$vResultOrg[sPhone].', '.$vResultOrg[sEmail].'</p></td>
  </tr>
  <tr>
    <td width="20%" align="center" valign="middle"><p align="center">ИНН</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">ОКПО</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">ОКОГУ</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">ОКВЭД</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">ОКТМО</p></td>
  </tr>
  <tr>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultOrg[sInn].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultOrg[sOkpo].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultOrg[sOkogu].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultOrg[sOkved].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultOrg[sOkato].'</p></td>
  </tr>
</table>
<font face="calibrib"><h2 align="center">Карта № '.$vResultRm[iNumber].'<br />специальной оценки условий труда<br />'.$vResultRm[sName].'</h2></font>

Наименование структурного подразделения: <font face="calibrib">'.$vResultParent[sName].'</font>.<br />
Количество и номера аналогичных рабочих мест: <font face="calibrib">'.$vResultRm[sNumAnalog].'</font><br /><br />

<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30%" valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 010.</font><br />Выпуск ЕТКС, ЕКС</td>
    <td width="70%" valign="top"><font face="calibrib">'.$vResultRm[sETKS].'</font></td>
  </tr>
</table>
<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30%" valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 020.</font><br />Численность работающих</td>
    <td width="70%" valign="top"><font face="calibrib">на рабочем месте: '.$vResultRm[iCount].'<br />на всех аналогичных рабочих местах: '.($iWorkCountAnalog).'<br />из них:<br />женщин: '.($iWorkCountAnalog1).'<br />лиц в возрасте до 18 лет: '.($iWorkCountAnalog2).'<br />инвалидов, допущенных к выполнению работ на данном рабочем месте: '.($iWorkCountAnalog3).'</font></td>
  </tr>
</table>
<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30%" valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 021.</font><br />СНИЛС работников</td>
    <td width="70%" valign="top"><font face="calibrib">'.StringWork::CheckNullStrFull($vResultRm[sSnils]).'</font></td>
  </tr>
</table>
<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30%" valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 022.</font><br />Используемое оборудование, материалы и сырье</td>
    <td width="70%" valign="top"><font face="calibrib">'.StringWork::CheckNullStrFull($vResultOborud).'</font></td>
  </tr>
</table>
<br /><br />
<table width="30%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 030.</font><br />
    Оценка условий труда по вредным  (опасным) факторам</td>
  </tr>
</table>
<br /><br />
<table border="0.5" cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td width="40%" align="center" valign="middle" bgcolor="#d9d9d9">Наименование факторов производственной  среды и трудового процесса</td>
    <td width="20%" align="center" valign="middle" bgcolor="#d9d9d9">Класс (подкласс) условий труда</td>
    <td width="20%" align="center" valign="middle" bgcolor="#d9d9d9">Эффективность СИЗ*, +/-/не оценивалась</td>
    <td width="20%" align="center" valign="middle" bgcolor="#d9d9d9">Класс (подкласс) условий труда при эффективном использовании СИЗ</td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Химический</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAChem]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Биологический</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iABio]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Аэрозоли преимущественно фиброгенного действия</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAAPFD]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Шум</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iANoise]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Инфразвук</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAInfraNoise]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Ультразвук воздушный</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAUltraNoise]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Вибрация общая</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAVibroO]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Вибрация локальная</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAVibroL]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Неионизирующие излучения</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iANoIon]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Ионизирующие излучения</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAIon]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Параметры микроклимата</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iAMicroclimat]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Параметры световой среды</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iALight]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Тяжесть трудового процесса</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iAHeavy].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p>Напряженность трудового процесса</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::iToClassNameLite($vResultRm[iATennese]).'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.$vResultRm[iSIZOEffect].'</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
  <tr>
    <td width="40%" align="left" valign="middle"><p><font face="calibrib">Итоговый класс (подкласс) условий труда</font></p></td>
    <td width="20%" align="center" valign="middle"><p align="center"><font face="calibrib">'.StringWork::iToClassNameLite($vResultRm[iATotal]).'</font></p></td>
    <td width="20%" align="center" valign="middle" bgcolor="#d9d9d9"><p align="center">Не заполняется.</p></td>
    <td width="20%" align="center" valign="middle"><p align="center">'.StringWork::CheckNullStrLite('').'</p></td>
  </tr>
</table>
* &#8212; Средства индивидуальной защиты.'.$sHeavycomment.'<br /><br />
<table width="30%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 040.</font><br />
    Гарантии и компенсации, предоставляемые работнику (работникам), занятым на данном рабочем месте</td>
  </tr>
</table>
<br /><br />
<table border="0.5" cellspacing="0" cellpadding="2" width="100%">
  <tr>
    <td width="5%" rowspan="2" align="center" valign="middle" bgcolor="#d9d9d9">№ п/п</td>
    <td width="30%" rowspan="2" align="center" valign="middle" bgcolor="#d9d9d9"><p align="center">Виды гарантий и    компенсаций</p></td>
    <td width="15%" rowspan="2" align="center" valign="middle" bgcolor="#d9d9d9"><p align="center">Фактическое    наличие</p></td>
    <td width="50%" colspan="2" align="center" valign="middle" bgcolor="#d9d9d9"><p align="center">По результатам    оценки условий труда</p></td>
  </tr>
  <tr>
    <td width="20%" align="center" valign="middle" bgcolor="#d9d9d9"><p align="center">необходимость  в установлении<br />
      (да, нет)</p></td>
    <td width="30%" align="center" valign="middle" bgcolor="#d9d9d9"><p align="center">основание</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">1.</p></td>
    <td width="30%" align="left" valign="middle"><p>Повышенная оплата труда работника (работников)</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactSurcharge]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompSurcharge]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBaseSurcharge]).'</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">2.</p></td>
    <td width="30%" align="left" valign="middle"><p>Ежегодный дополнительный оплачиваемый отпуск</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactVacation]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompVacation]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBaseVacation]).'</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">3.</p></td>
    <td width="30%" align="left" valign="middle"><p>Сокращенная продолжительность рабочего времени</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactShortWorkDay]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompShortWorkDay]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBaseShortWorkDay]).'</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">4.</p></td>
    <td width="30%" align="left" valign="middle"><p>Молоко или другие     равноценные пищевые продукты</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactMilk]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompMilk]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBaseMilk]).'</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">5.</p></td>
    <td width="30%" align="left" valign="middle"><p>Лечебно-профилактическое питание</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactFood]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompFood]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBaseFood]).'</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">6.</p></td>
    <td width="30%" align="left" valign="middle"><p>Право на досрочное назначение страховой пенсии</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactPension]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompPension]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBasePension]).'</p></td>
  </tr>
  <tr>
    <td width="5%" align="center" valign="middle"><p align="center">7.</p></td>
    <td width="30%" align="left" valign="middle"><p>Проведение медицинских осмотров</p></td>
    <td width="15%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[sCompFactPhysical]).'</p></td>
    <td width="20%" align="left" valign="middle"><p align="center">'.StringWork::iToCompString($vResultRm[iCompPhysical]).'</p></td>
    <td width="30%" align="left" valign="middle"><p align="center">'.StringWork::CheckNullStrFull($vResultRm[sCompBasePhysical]).'</p></td>
  </tr>
</table>
<br /><br />
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="30%" valign="top" bgcolor="#d9d9d9" style="border-top:solid 1px #000000"><font face="calibrib">Строка 050.</font><br />Рекомендации по улучшению условий труда, по режимам  труда и отдыха, по подбору работников</td>
    <td width="70%" valign="top"><font face="calibrib">'.StringWork::CheckNullStrFull($sActivity).'</font></td>
  </tr>
</table>
<br /><br />
Дата составления: '.StringWork::StrToDateFormatFull($vResultRm[dCreateDate]).'
<br /><br />
	';	
	
	$pdf->writeHTML($html, true, false, true, false, '');
	
	//Коммисия!
	PDF_insert_Podpis($pdf, $vResultRm[idGroup]);	

	//Подписанты
	$html = '
	<p><font face="calibrib" size="+2">С результатами специальной оценки условий труда ознакомлены:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">';	
	
	for($i = 0; $i < ($vResultRm[iCount] + 3); $i++)
	{
		$html .= '
		<tr>
		<td valign="top" style="border-bottom:#000 solid 2px;"></td>
		</tr>
		<tr>
		<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
		</tr>
		';
	}
	$html .= '</table>';
	$pdf->writeHTML($html, true, false, true, false, '');
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');
	$html ='';
?>

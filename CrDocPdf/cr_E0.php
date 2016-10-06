<?
	ini_set('memory_limit','128M');
	$sDocName = '2.0_SOUT_Information.pdf';
	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$pdf->tmpOrgName = '<br>'.$agroup[sFullName];
	$pdf->tmpDocType = 'Сведения о результатах проведения СОУТ';
	
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);

	//РМ - По классам условий труда
	//Данные общие
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount = mysql_num_rows($vResult);
	if($agroup['iRmTotalCount'] < $tmpRMCount) $agroup['iRmTotalCount'] = $tmpRMCount;
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 1;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount1 = mysql_num_rows($vResult);
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 2;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount2 = mysql_num_rows($vResult);
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 3;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount3 = mysql_num_rows($vResult);
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 4;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount4 = mysql_num_rows($vResult);
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 5;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount5 = mysql_num_rows($vResult);
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 6;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount6 = mysql_num_rows($vResult);
	
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 7;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount7 = mysql_num_rows($vResult);

	//Аккредитация
	$sql = "SELECT * FROM `Arm_groupAcredit` WHERE `idGroup` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen($orgAccr)>0) $orgAccr .= ', ';
			$orgAccr .= $vRow[sName].' действителен c '.StringWork::StrToDateFormatLite($vRow[dDateCreate]).' по '.StringWork::StrToDateFormatLite($vRow[dDateFinish]);
		}
	}
	
	//Персонал
	$sql = "SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = ".$target." AND `bExpert` = 1;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$aStuff = array();
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen($orgPersonal)>0) $orgPersonal .= ', ';
			if(strlen(trim($vRow[sReestrNum]))>0)
			$orgPersonal .= StringWork::CheckNullStrLite($vRow[sPost]).' '.StringWork::CheckNullStrLite($vRow[sName]).' - № '.StringWork::CheckNullStrLite($vRow[sReestrNum]).' реестре экспертов организаций, проводящих специальную оценку условий труда';
			else
			$orgPersonal .= StringWork::CheckNullStrLite($vRow[sPost]).' '.StringWork::CheckNullStrLite($vRow[sName]);
		}
	}
	
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." ORDER BY `dtControl`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	$sDateIzm = '';
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen($sDateIzm) > 0) $sDateIzm .= ', ';
			$sDateIzm .= StringWork::StrToDateFormatLite($vRow[DC]);
		}
	}
	
	//Средства измерения
	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen($orgDevice)>0) $orgDevice .= ', ';
			$orgDevice .= StringWork::CheckNullStrLite($vRow[sName]).' № '.StringWork::CheckNullStrLite($vRow[sReestrNum]).' в Федеральном информационном фонде по обеспечению единства измерений, заводской номер '.StringWork::CheckNullStrLite($vRow[sFactoryNum]).' поверка до '.StringWork::CheckNullStrLite(StringWork::StrToDateFormatLite($vRow[dCheckDate])).' ('.StringWork::CheckNullStrLite($vRow[sFactName]).')';			
		}
	}
	if(strlen($orgDevice)>0) $orgDevice .= ' - проведение измерений '.StringWork::CheckNullStrLite($sDateIzm);

	$html ='
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="left"><p>Организациия, проводящая<br />специальную оценку условий труда<br />'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid)).'</p></td>
<td align="right"><p>В территориальный орган<br />Федеральной службы<br />по труду и занятости</p></td>
</tr>
<tr>
<td align="left">&nbsp;</td>
</tr>
</table>
	
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><h1><font face="calibrib">Cведения о результатах проведения<br />специальной оценки условий труда</font></h1></td>
</tr>
<tr>
<td align="left">&nbsp;</td>
</tr>
</table>

<p style="text-align:justify;">В соответствии с требованиями Федерального закона Российской федерации «О специальной оценке условий труда» 426-ФЗ от 28 декабря 2013г. ст.ст. 18, 28 и приказа Министерства труда и социальной защиты Российской Федерации от 3 июля 2014 г. N 436н "Об утверждении Порядка передачи сведений о результатах проведения специальной оценки условий труда" направляем в Ваш адрес сведения о результатах проведения специальной оценки условий труда:</p>

<h2>Сведения о работодателе:</h2>
<p style="text-align:justify;">
Полное наименование: '.StringWork::CheckNullStrFull($agroup[sFullName]).'.<br />
Место нахождения и место осуществления деятельности: '.StringWork::CheckNullStrFull($agroup[sPlace]).'.<br />
Идентификационный номер налогоплательщика: '.StringWork::CheckNullStrFull($agroup[sInn]).'.<br />
Основной государственный регистрационный номер: '.StringWork::CheckNullStrFull($agroup[sOgrn]).'.<br />
Код основного вида экономической деятельности по Общероссийскому классификатору видов экономической деятельности: '.StringWork::CheckNullStrFull($agroup[sOkved]).'.<br />
Количество рабочих мест: '.$agroup['iRmTotalCount'].'.<br />
Количество рабочих мест, на которых проведена специальная оценка условий труда: '.$tmpRMCount.'.<br />
Распределение рабочих мест по классам (подклассам) условий труда: класс 1.0 - '.$tmpRMCount1.', класс 2.0 - '.$tmpRMCount2.', класс 3.1 - '.$tmpRMCount3.', класс 3.2 - '.$tmpRMCount4.', класс 3.3 - '.$tmpRMCount5.', класс 3.4 - '.$tmpRMCount6.', класс 4.0 - '.$tmpRMCount7.'.
</p>
<h2>Сведения о рабочих местах:</h2><br />
';
$pdf->writeHTML($html, true, false, true, false, '');

//Пошли рабочие места
$sql = "SELECT * FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1 ORDER BY `iNumber`;";
$vResult = DbConnect::GetSqlQuery($sql);
$tmphtml = '';
if (mysql_num_rows($vResult) > 0)
{
	while($vRow = mysql_fetch_array($vResult))
	{
		if($vRow[iCompPension] == 0) $vRow[sCompBasePension] = StringWork::CheckNullStrLite('');
		$num = StringWork::CheckNullStrLite($vRow[iNumber]).'.';
		$text = StringWork::CheckNullStrLite($vRow[sName]).', код по ОК 016-94 '.StringWork::CheckNullStrLite($vRow[sOk]).', количество работников: '.StringWork::CheckNullStrLite($vRow[iCount]).', СНИЛС: '.StringWork::CheckNullStrLite($vRow[sSnils]).', досрочная трудовая пенсию по старости: '.$vRow[sCompBasePension].'.';
		$rowcount = max($pdf->getNumLines($text, 140),$pdf->getNumLines(StringWork::iToClassNameLite($vRow[iATotal]), 20),$pdf->getNumLines($num, 10));
		$h = $rowcount*4.5;
		$num_pages = $pdf->getNumPages();
		$pdf->startTransaction();
		$pdf->MultiCell	(10,$h,$num,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$pdf->MultiCell	(140,$h,$text,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$pdf->MultiCell	(20,$h,StringWork::iToClassNameLite($vRow[iATotal]),1,'C',0,1,'','',1,0,0,1,$h,'M');
		
		if($num_pages < $pdf->getNumPages())
		{
			$pdf->rollbackTransaction(true);
			$pdf->AddPage();
			//Вставка содержимого
			$pdf->MultiCell	(150,$h,$text,1,'L',0,0,'','',1,0,0,1,$h,'M');
			$pdf->MultiCell	(20,$h,StringWork::iToClassNameLite($vRow[iATotal]),1,'C',0,1,'','',1,0,0,1,$h,'M');
		}
		else
		{
			//Otherwise we are fine with this row, discard undo history.
			$pdf->commitTransaction();
		}

		//Факторы
		$sql = "SELECT `Arm_rmFactors`.*, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, (`Arm_rmFactorsPdu`.`id`) AS `PduId`, `Arm_rmPointsRm`.`sTime`, `Arm_rmFactorsPdu`.`sAddonAsset`, `Arm_rmFactorsPdu`.`iAsset` FROM `Arm_rmFactorsPdu` LEFT JOIN `Arm_rmFactors` ON `Arm_rmFactorsPdu`.`idFactor` = `Arm_rmFactors`.`id` LEFT JOIN `Arm_rmPointsRm` ON (`Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_rmFactorsPdu`.`idRm` = ".$vRow[id].";";
		$vResultf = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		if (mysql_num_rows($vResultf) > 0)
		{
			while($vRowf = mysql_fetch_array($vResultf))
			{
				
//================================================
	$sPdu = $vRowf[fPdu1];
	$sVar = $vRowf[var1];


	switch ($vRowf[idFactorGroup])
	{
		//Отлавливаем и облагораживаем химию
		case 8:
		case 31:
$sPdu = $vRowf[fPdu1] .'
'.$vRowf[fPdu2];
$sVar = $vRowf[var1] .'
'.$vRowf[var2];
			$sPdu = str_replace('-1','-',$sPdu);
		break;
		default:
			//Отлавливаем и облагораживаем физику
			switch ($vRowf[idFactor])
			{
				case 16:
				case 54:
				case 26:
$sPdu = $vRowf[fPdu1].'
'.$vRowf[fPdu2].'
'.$vRowf[fPdu3];
$sVar = $vRowf[var1].'
'.$vRowf[var2].'
'.$vRowf[var3];
				break;
				case 42:
$sPdu = '<22000
<42000
<60000';
$sVar = $vRowf[var1].'
'.$vRowf[var2].'
'.$vRowf[var3];
				break;
				case 39:
$sPdu = '<3000
<15000
<28000';
$sVar = $vRowf[var1].'
'.$vRowf[var2].'
'.$vRowf[var3];
				break;
				case 40:
$sPdu = '<30/10
<15/7
<870/350
<435/175';
$sVar = $vRowf[var1].'
'.$vRowf[var2].'
'.$vRowf[var3].'
'.$vRowf[var4];
				break;
				case 41:
$sPdu = '<40000
<20000';
$sVar = $vRowf[var1].'
'.$vRowf[var2];
				break;
				case 44:
					$sPdu = '100';
				break;
				case 45:
$sPdu = 'до 8
до 2.5';
$sVar = $vRowf[var1].'
'.$vRowf[var2];
				break;
				case 22:
				case 45:
				case 18:
$sPdu = $vRowf[fPdu1].'
'.$vRowf[fPdu2];
$sVar = $vRowf[var1].'
'.$vRowf[var2];
				break;
				case 5:
					$sPdu = '15 - 75';
				break;
				case 2:
					switch ($vRowf[fPdu1])
					{
						case 0:
							$sPdu = '20.0-25.0';
						break;
						case 1:
							$sPdu = '19.0-24.0';
						break;
						case 2:
							$sPdu = '17.0-23.0';
						break;
						case 3:
							$sPdu = '15.0-22.0';
						break;
						case 4:
							$sPdu = '13.0-21.0';
						break;
					}
				break;
				case '6':
					switch ($vRowf[fPdu1])
					{
						case 0:
							$sPdu = '0.1';
						break;
						case 1:
							$sPdu = '0.2';
						break;
						case 2:
							$sPdu = '0.3';
						break;
						case 3:
							$sPdu = '0.4';
						break;
						case 4:
							$sPdu = '0.4';
						break;
					}
				break;
				case '56':
					switch ($vRowf[fPdu1])
					{
						case 0:
							$sPdu = '26.5';
						break;
						case 1:
							$sPdu = '25.9';
						break;
						case 2:
							$sPdu = '25.2';
						break;
						case 3:
							$sPdu = '24.0';
						break;
						case 4:
							$sPdu = '21.9';
						break;
					}
				break;
				case '43':
					$sPdu = 'Уд.';
					switch($vRowf[var1])
					{
						case 0:
							$sVar = 'Уд.';
						break;
						case 1:
							$sVar = 'Неуд.';
						break;
						case 2:
							$sVar = 'Неуд.';
						break;
						case 3:
							$sVar = 'Неуд.';
						break;
					}
					
				break;
				case 48:
					$sPdu = '175';
				break;
				case 49:
					$sPdu = '10';
				break;
				case 52:
					$sPdu = '50';
				break;
				case 53:
					$sPdu = '20';
				break;
				case 65:
					$sPdu = '6';
				break;
				case 66:
					$sPdu = '80';
				break;
				case 51:
				case 50:
				case 47:
				case 19:
				case 20:
					$sPdu = '-';
					$sVar = '';					
				break;
				default:
				$sPdu = $vRowf[fPdu1];
				$sVar = $vRowf[var1];
				break;
			}
		break;
	}
//=============================================				
				
				$sName = $vRowf[sName];
				$sTime = $vRowf[sTime] .' ч.';
				$sAsset = StringWork::iToClassNameLite($vRowf[iAsset]);
				
				
				
				$pdf->SetFont($infontnamebold, 'BI', 8, '', 'false');
				$num_pages = $pdf->getNumPages();
				$pdf->startTransaction();
				$rowcount = max($pdf->getNumLines($sName, 105),$pdf->getNumLines($sTime, 15),$pdf->getNumLines($sPdu, 15),$pdf->getNumLines($sVar, 15),$pdf->getNumLines($sAsset, 20));
				$h = $rowcount*3.8;

				$pdf->MultiCell	(105,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
				$pdf->MultiCell	(15,$h,$sTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
				$pdf->MultiCell	(15,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
				$pdf->MultiCell	(15,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
				$pdf->MultiCell	(20,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
				
				if($num_pages < $pdf->getNumPages())
				{
					$pdf->rollbackTransaction(true);
					$pdf->AddPage();
					//Вставка содержимого
	
				}
				else
				{
					//Otherwise we are fine with this row, discard undo history.
					$pdf->commitTransaction();
				}
				$pdf->SetFont($infontnamebold, 'BI', 10, '', 'false');
			}
		}
		
/*		
		if(strlen($tmphtml)>0) $tmphtml .= '<br>';
		$tmphtml .= '<font face="calibrib">№ '.StringWork::CheckNullStrLite($vRow[iNumber]).', код ОК '.StringWork::CheckNullStrLite($vRow[sOk]).' ('.StringWork::CheckNullStrLite($vRow[sName]).')</font><br />
Количество работников: '.StringWork::CheckNullStrLite($vRow[iCount]).', СНИЛС: '.StringWork::CheckNullStrLite($vRow[sSnils]).', Основание для формирования прав на досрочную трудовую пенсию по старости: '.$vRow[sCompBasePension].', Класс (подкласс) условий труда на данном рабочем месте: '.StringWork::iToClassNameLite($vRow[iATotal]).'.';

		*/
	}
}

$html ='
<br /><h2>Сведения о организации, проводившей специальную оценку условий труда:</h2>
<p style="text-align:justify;">
Полное наименование: '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid)).'.<br />
Регистрационный номер записи в реестре организаций, проводящих специальную оценку условий труда: '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgRegNum',$sSOUTORGid)).'.<br />
Идентификационный номер налогоплательщика: '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgInn',$sSOUTORGid)).'.<br />
Основной государственный регистрационный номер: '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgOgrn',$sSOUTORGid)).'.<br />
Сведения об аккредитации испытательной лаборатории (центра), в том числе номер и срок действия аттестата аккредитации испытательной лаборатории (центра): '.$orgAccr.'.<br />
Сведения об экспертах организации, проводившей специальную оценку условий труда, участвовавших в ее проведении, в том числе фамилия, имя, отчество, должность и регистрационный номер записи в реестре экспертов организаций, проводящих специальную оценку условий труда: '.$orgPersonal.'.<br />
Сведения о применявшихся испытательной лабораторией (центром) средствах измерений, включающие в себя наименование средства измерения и его номер в Федеральном информационном фонде по обеспечению единства измерений, заводской номер средства измерений, дату окончания срока действия его поверки, дату проведения измерений, наименования измерявшихся вредного и (или) опасного производственных факторов: '.$orgDevice.'.</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0" nobr="true">
<tr>
<td width="40%" align="left"><p>Руководитель организации, проводящей специальную оценку условий труда</p></td>
<td width="60%" align="right" valign="bottom" style="border-bottom:#000 solid 2px; vertical-align:bottom;">'.StringWork::FullNameToInitials(UserControl::GetUserFieldValueFromId('sFirstFaceName',$sSOUTORGid)).'</td>
</tr>
<tr>
<td width="40%" align="left">&nbsp;</td>
<td width="60%" align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
</tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetFillColor(217,217,217);
	
//Коммисия!
$pdf->SetFont($fontname, 'BI', 10, '', 'false');
$html ='';
	
function PDF_insert_RM($inPDF, $idWorkGroup, $infontname, $infontnamebold)
{
	PDF_InsertHeaderRmList($inPDF);	
	$sql = "SELECT * FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if (strlen($vRow[sNumAnalog]) > 0)
			{
				$vRow[iNumber] = $vRow[iNumber].'А';
			}
			
			//Начало цикла вставки
			$num_pages = $inPDF->getNumPages();
            $inPDF->startTransaction();
			
			//Вставка содержимого
			$rowcount = $inPDF->getNumLines($vRow[sName], 37);
			$rowheight = $rowcount*3.8;
			$inPDF->MultiCell(10,$rowheight,$vRow[iNumber],1,'C',0,0);
			$inPDF->MultiCell(37,$rowheight,$vRow[sName],1,'L',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAChem]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iABio]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAAPFD]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoise]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAInfraNoise]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAUltraNoise]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroO]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroL]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoIon]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAIon]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAMicroclimat]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iALight]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAHeavy]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATennese]),1,'C',0,0);

			$inPDF->SetFont($infontnamebold, 'BI', 8, '', 'false');
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATotal]),1,'C',0,0);
			$inPDF->SetFont($infontname, 'BI', 8, '', 'false');
			$inPDF->MultiCell(10,$rowheight,'—',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompSurcharge]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompVacation]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompShortWorkDay]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompMilk]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompFood]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompPension]),1,'C',0,1);
			
			if($num_pages < $inPDF->getNumPages())
            {
				$inPDF->rollbackTransaction(true);
				$inPDF->AddPage();
				//Вставка заголовка	
				PDF_InsertHeaderRmList($inPDF);		
				
				//Вставка содержимого
				$rowcount = $inPDF->getNumLines($vRow[sName], 37);
				$rowheight = $rowcount*3.8;
				$inPDF->MultiCell(10,$rowheight,$vRow[iNumber],1,'C',0,0);
				$inPDF->MultiCell(37,$rowheight,$vRow[sName],1,'L',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAChem]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iABio]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAAPFD]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoise]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAInfraNoise]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAUltraNoise]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroO]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroL]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoIon]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAIon]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAMicroclimat]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iALight]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAHeavy]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATennese]),1,'C',0,0);
				
				$inPDF->SetFont($infontnamebold, 'BI', 8, '', 'false');
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATotal]),1,'C',0,0);
				$inPDF->SetFont($infontname, 'BI', 8, '', 'false');
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompSurcharge]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompVacation]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompShortWorkDay]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompMilk]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompFood]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompPension]),1,'C',0,1);
			}
            else
            {
                //Otherwise we are fine with this row, discard undo history.
                $inPDF->commitTransaction();
            }
		}
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$inPDF->MultiCell(10,0,'1',1,'C',1,0);
	$inPDF->MultiCell(37,0,'2',1,'C',1,0);
	$inPDF->MultiCell(10,0,'3',1,'C',1,0);
	$inPDF->MultiCell(10,0,'4',1,'C',1,0);
	$inPDF->MultiCell(10,0,'5',1,'C',1,0);
	$inPDF->MultiCell(10,0,'6',1,'C',1,0);
	$inPDF->MultiCell(10,0,'7',1,'C',1,0);
	$inPDF->MultiCell(10,0,'8',1,'C',1,0);
	$inPDF->MultiCell(10,0,'9',1,'C',1,0);
	$inPDF->MultiCell(10,0,'10',1,'C',1,0);
	$inPDF->MultiCell(10,0,'11',1,'C',1,0);
	$inPDF->MultiCell(10,0,'12',1,'C',1,0);
	$inPDF->MultiCell(10,0,'13',1,'C',1,0);
	$inPDF->MultiCell(10,0,'14',1,'C',1,0);
	$inPDF->MultiCell(10,0,'15',1,'C',1,0);
	$inPDF->MultiCell(10,0,'16',1,'C',1,0);
	$inPDF->MultiCell(10,0,'17',1,'C',1,0);
	$inPDF->MultiCell(10,0,'18',1,'C',1,0);
	$inPDF->MultiCell(10,0,'19',1,'C',1,0);
	$inPDF->MultiCell(10,0,'20',1,'C',1,0);
	$inPDF->MultiCell(10,0,'21',1,'C',1,0);
	$inPDF->MultiCell(10,0,'22',1,'C',1,0);
	$inPDF->MultiCell(10,0,'23',1,'C',1,0);
	$inPDF->MultiCell(10,0,'24',1,'C',1,1);
}

function PDF_isFactorGroup($idPoint,$idGroupID,$rTimeHour)
{
	$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint." AND `idFactorGroup` = ".$idGroupID.";";
	$vResultP = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResultP) > 0)
	{	
		return $rTimeHour .' ч.';
	}
	else
	{
		return '-';
	}
}
function PDF_isFactorId($idPoint,$idFactor,$rTimeHour)
{
	$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint." AND `idFactor` = ".$idFactor.";";
	$vResultP = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResultP) > 0)
	{	
		return $rTimeHour .' ч.';
	}
	else
	{
		return '-';
	}
}
function PDF_isFactorIds($idPoint,$idFactor,$rTimeHour)
{
	$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint." AND `idFactor` IN (".$idFactor.");";
//	echo($sql);
	$vResultP = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResultP) > 0)
	{	
		return $rTimeHour .' ч.';
	}
	else
	{
		return '-';
	}
}
?>

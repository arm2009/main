<?
	ini_set('memory_limit','128M');
	$sDocName = '1.5_Protocol_Vibroaqustic.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	//ID организации
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);

	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$sProtokolNum = StringWork::CheckNullStrLite($agroup[sPNumNoise]);
	$pdf->tmpOrgName = '<br>'.UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid);
	$pdf->tmpDocType = 'Протокол оценки виброакустических факторов № '.$sProtokolNum;

	//====================================================================================================
	//====================================================================================================
	//====================================================================================================
	//Шапка
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." AND `Arm_rmFactors`.`idFactorGroup` = 10 ORDER BY `dtControl`;";
	$vResult = DbConnect::GetSqlQuery($sql);

	$sDateIzm = '';
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen($sDateIzm) > 0) $sDateIzm .= 'г., ';
			$sDateIzm .= StringWork::StrToDateFormatLite($vRow[DC]);
		}
		if(strlen($sDateIzm) > 0) $sDateIzm .= 'г.';
	}
	else
	{
		$sOborud ='<font face="calibrib">Отсутствует.</font>';
	}

	//Средства измерения
	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target." AND (`sFactName` LIKE '%Шум%' OR `sFactName` LIKE '%Вибрац%' OR `sFactName` LIKE '%Виброакуст%');";
	$vResult = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($vResult) > 0)
	{
		$sOborud ='<br /><br /><table width="100%" border="0.5" cellspacing="0" cellpadding="2" bordercolor="#000">
		<tr>
		<td width="50%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наименование средства измерений</font></h5></td>
		<td width="15%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Заводской номер<br />средства измерений</font></h5></td>
		<td width="35%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Номер свидетельства и дата окончания<br />срока поверки средства измерений</font></h5></td>
		</tr>';
		//Счетчик
		$iNum = 1;
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen(trim($vRow[sMethodName]))>0) $sOborudMethod .= '<li>'.$vRow[sMethodName].'</li>';
			$sOborud .='<tr>
			<td width="50%" align="left" valign="middle">'.$vRow[sName].'</td>
			<td width="15%" align="center" valign="middle">'.$vRow[sFactoryNum].'</td>
			<td width="35%" align="center" valign="middle">№ '.$vRow[sCheckNum].' до '.StringWork::StrToDateFormatLite($vRow[dCheckDate]).' г.</td>
			</tr>';
		}
		$sOborud .='</table><br />';
	}
	else
	{
		$sOborud ='<font face="calibrib">Отсутствует.</font>';
	}


	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><h2><font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid)).'</font></h2></td>
</tr>
<tr>
<td align="center"><font face="calibrib">Юридический адрес: '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgPlace',$sSOUTORGid)).', Телефон: '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgPhone',$sSOUTORGid)).'.</font></td>
</tr>
<tr>
<td align="center"><font face="calibrib">№ '.UserControl::GetUserFieldValueFromId('sOrgRegNum',$sSOUTORGid).' в реестре аккредитованных организаций, оказывающие услуги в области охраны труда от '.StringWork::StrToDateFormatLite(UserControl::GetUserFieldValueFromId('sOrgDate',$sSOUTORGid)).' г.</font></td>
</tr>';

	$sql = "SELECT * FROM `Arm_groupAcredit` WHERE `idGroup` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$html .='<tr><td align="center"><font face="calibrib">Аттестат аккредитации: '.$vRow[sName].' от '.StringWork::StrToDateFormatLite($vRow[dDateCreate]).', действителен до '.StringWork::StrToDateFormatLite($vRow[dDateFinish]).'.</font></td></tr>';
		}
	}


$html .='<tr style="border-bottom:1px #000000 solid;">
<td align="left" style="border-bottom:1px #000000 solid;">&nbsp;</td>
</tr>
<tr>
<td align="center"><font face="calibrib"><h2>ПРОТОКОЛ № '.$sProtokolNum.'<br />результатов исследования, измерения и оценки условий труда<br />по параметрам виброакустических факторов</h2></font></td>
</tr>
<tr><td align="Left">1. Полное наименование работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sFullName]).'</font></td></tr>
<tr><td align="Left">2. Место нахождения и место осуществления деятельности работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sPlace]).'</font></td></tr>
<tr><td align="Left">3. Дата проведения исследований, измерений: <font face="calibrib">'.StringWork::CheckNullStrFull($sDateIzm).'</font></td></tr>
<tr><td align="Left">4. Сведения о применяемых средствах измерений: '.$sOborud.'</td></tr>
<tr nobr="true"><td align="Left">5. Наименование примененных методов исследований, измерений, нормативно правовых актов регламентирующих нормативные уровни (ПДК/ПДУ): <font face="calibrib"><ul><li>Приказ Минтруда России №33н от 24 января 2014 г. "Об утверждении методики проведения специальной оценки условий труда, классификатора вредных и (или) опасных производственных факторов, формы отчета о проведении специальной оценки условий труда и инструкции по её заполнению";</li><li>ГОСТ Р ИСО 9612-2013 "Национальный стандарт Российской федерации. Акустика. Измерения шума для оценки его воздействия на человека. Метод измерений на рабочих местах", утв. и введен в действие Приказом Росстандарта от 05.12.2013 № 2180-ст;</li><li>СН 2.2.4/2.1.8.562-96 Утв. Постановлением Госкомсанэпиднадзора России от 31 октября 1996 г. № 36. «Шум на рабочих местах, в помещениях жилых, общественных зданий и на территории жилой застройки»;</li><li>СН 2.2.4/2.1.8.566-96 утв. и введены в действие Постановлением Госкомсанэпиднадзора России от 31 октября 1996 г. N 40. «Производственная вибрация, вибрация в помещениях жилых и общественных зданий»;</li>'.$sOborudMethod.'</ul></font></td></tr></table>';
	//====================================================================================================
	//====================================================================================================
	//====================================================================================================


$pdf->SetFont($fontname, 'BI', 11, '', 'false');
//$pdf->SetAutoPageBreak(false);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetFillColor(217,217,217);
$pdf->AddPage();

$pdf->SetAutoPageBreak(True, 25);
$pdf->SetFont($fontname, 'BI', 10, '', 'false');
PDF_insert_RM($pdf, $target, $fontname, $fontname_bold);
PDF_insert_Podpis_Protocol($pdf, $target);
//PDF_insert_EndText($pdf, '', 25, 20, '1, 2, 3, 4', $target);
$pdf->SetFont($fontname, 'BI', 10, '', 'false');
$html ='';

function PDF_insert_EndText($inPDF, $sPrime, $iCountAll, $iCountDanger, $sWarningNum, $iTarget)
{
	$html ='<table width="100%" border="0" cellspacing="0" cellpadding="0">';

	if(strlen(trim($sPrime)) > 0)
	{
		$html .='<tr><td align="Left"><font face="calibrib">Примечание:</font> '.$sPrime.'</td></tr>';
	}

	if($iCountDanger == 0)
	{
		//Соответсвует
		$html .='<tr><td align="Left"><font face="calibrib">Заключение:</font> По результатам исследования, измерения и оценки, условия труда на '.StringWork::Rms($iCountAll).' - соответствуют требованиям нормативных документов.</td></tr>';
	}
	else
	{
		//Не соответсвует
		$html .='<tr><td align="Left"><font face="calibrib">Заключение:</font> По результатам исследования, измерения и оценки, условия труда на '.$iCountDanger.' из '.StringWork::Rms($iCountAll).' р.м. (№ '.$sWarningNum.') не соответствует требованиям нормативных документов.</td></tr>';
	}
	//Примечания
	$sql2 = "SELECT `sNNoise` FROM `Arm_group` WHERE `id` = ".$iTarget.";";
	$sNote = DbConnect::GetSqlCell($sql2);

        if ($sNote != '')
	{
	$html .='<br><tr><td align="Left"><font face="calibrib">Примечание:</font> '.$sNote.'</td></tr>';
	}


	$html .='</table>';
	$inPDF->Ln();
	$inPDF->writeHTML($html, true, false, true, false, '');
}

function PDF_insert_RM($inPDF, $idWorkGroup, $infontname, $infontnamebold)
{
	//Стандартные переменные
	$iRmCount = 0;
	$iRmCountWarning = 0;
	$sRmCountWarning = '';

	$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	//Шапка протокола
	$h = 50;
	$inPDF->MultiCell	(10,$h,'№',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(141,$h,'Наименование структурного подразделения, рабочего места; места проведения измерения; исследуемого фактора, ед. изм.',1,'C',1,0,'','',1,0,0,1,$h,'M');
//	$inPDF->SetFont($infontname, 'BI', 8, '', 'false');

	$inPDF->StartTransform();
	$inPDF->Rotate(90, 166, 65);
	$inPDF->MultiCell	($h,13,'Время воздействия, ч.',1,'C',1,0,166,65,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Характер / категория*',1,'C',1,0,166,78,1,0,0,1,13,'M');
	$inPDF->StopTransform();

	$inPDF->MultiCell	(30,$h,'Нормативное значение',1,'C',1,0,192,15,1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Фактическое значение',1,'C',1,0,222,15,1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Итоговый класс',1,'C',1,1,252,15,1,0,0,1,$h,'M');
	PDF_InsertHeaderRmList($inPDF);

	//Комментарий
	$sHeavycomment = '** &#8212; В числителе оценка для мужчин, в знаменателе для женщин.';
	$bHeavycomment = false;


	$sql = "SELECT `id`, `sName`, `idParent`, `iNumber`, `sNumAnalog`, `fWorkDay`, `iANoise`, `iAInfraNoise`, `iAUltraNoise`, `iAVibroO`, `iAVibroL` FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$bRow = false;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$sql = "SELECT `Arm_rmFactors`.`idFactorGroup`,`Arm_rmFactors`.`idFactor`,`Arm_rmFactors`.`sName`, `Arm_rmFactors`.`var1`, `Arm_rmFactors`.`var2`, `Arm_rmFactors`.`var3`, `Arm_rmFactors`.`var4`, `Arm_rmFactors`.`var5`, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, `Arm_rmFactorsPdu`.`iAsset`, `Arm_rmPoints`.`sName` AS `sZoneName`, `Arm_rmPointsRm`.`sTime`, `Arm_rmPoints`.`sLightPolygone`, `Arm_rmPoints`.`sLightHeight`, `Arm_rmPoints`.`sLightDark`, `Arm_rmPoints`.`sLightType` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) LEFT JOIN `Arm_rmPoints` ON (`Arm_rmPoints`.`id` = `Arm_rmFactors`.`idPoint`) LEFT JOIN `Arm_rmPointsRm` ON (`Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_workplace`.`id`) WHERE `Arm_workplace`.`id` =".$vRow[id]." AND `Arm_rmFactors`.`idFactorGroup` = 10 AND `Arm_rmFactors`.`idFactor` <> 13 ORDER BY `Arm_rmPoints`.`sName`, `Arm_rmFactors`.`idFactor`;";
			$vResultF = DbConnect::GetSqlQuery($sql);
			if (mysql_num_rows($vResultF) > 0)
			{
				$bRow = true;
				$iRmCount++;

				$tmpMaxNoise = max($vRow[iAInfraNoise],$vRow[iAUltraNoise],$vRow[iAVibroO],$vRow[iAVibroL]);
				if($tmpMaxNoise > 2)
				{
					$iRmCountWarning++;
					if(strlen($sRmCountWarning) > 0) $sRmCountWarning .= ', ';
					$sRmCountWarning .= $vRow[iNumber];
				}
				$tmpMaxNoise = StringWork::iToClassNameLite($tmpMaxNoise);

				//Аналогичность
				if (strlen($vRow[sNumAnalog]) > 0)
				{
					$vRow[iNumber] = $vRow[iNumber].'А';
				}

				//Название подразделения
				$tmpUnitName = html_entity_decode(DbConnect::GetSqlCell("SELECT `sName` FROM `Arm_workplace` WHERE `id` = ".$vRow[idParent].";").', '. $vRow[sName]);
				$inPDF->SetFont($infontnamebold, 'BI', 10, '', 'false');

				//Данные рабочего места
				$num_pages = $inPDF->getNumPages();
				$inPDF->startTransaction();

				$rowcount = max($inPDF->getNumLines($tmpUnitName, 227),$inPDF->getNumLines($vRow[iNumber], 10));
				$h = $rowcount*4.5;
				$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
				$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
				$inPDF->MultiCell	(30,$h,$tmpMaxNoise,1,'C',0,1,'','',1,0,0,1,$h,'M');
				$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				if($num_pages < $inPDF->getNumPages())
				{
					$inPDF->rollbackTransaction(true);
					$inPDF->AddPage();
					//Вставка заголовка
					PDF_InsertHeaderRmList($inPDF);
					$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(30,$h,$tmpMaxNoise,1,'C',0,1,'','',1,0,0,1,$h,'M');
					$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				}
				else
				{
					$inPDF->commitTransaction();
				}

				$tmpZone = '';

				$aNoiseLevel = array();
				$aNoiseTime = array();
				$aInfraNoiseLevel = array();
				$aInfraNoiseTime = array();
				$aUltraNoiseLevel = array();
				$aUltraNoisePdu = array();
				$aUltraNoiseTime = array();
				$aVibroOLevelX = array();
				$aVibroOLevelY = array();
				$aVibroOLevelZ = array();
				$aVibroOTime = array();
				$aVibroLLevelX = array();
				$aVibroLLevelY = array();
				$aVibroLLevelZ = array();
				$aVibroLTime = array();

				//Переменные
				while($vRowF = mysql_fetch_array($vResultF))
				{
					if($tmpZone != $vRowF[sZoneName] && $vRowF[idFactor] != 13)
					{
						$tmpZone = $vRowF[sZoneName];
						$num_pages = $inPDF->getNumPages();
						$inPDF->startTransaction();
						//Указание зоны
						$rowcount = $inPDF->getNumLines($tmpZone, 257);
						$h = $rowcount*4.5;
						$inPDF->MultiCell (10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
						$inPDF->MultiCell (257,$h,$tmpZone,1,'L',0,1,'','',1,0,0,1,$h,'M');

						if($num_pages < $inPDF->getNumPages())
						{
							$inPDF->rollbackTransaction(true);
							$inPDF->AddPage();
							//Вставка заголовка
							PDF_InsertHeaderRmList($inPDF);
							$rowcount = $inPDF->getNumLines($tmpZone, 257);
							$h = $rowcount*4.5;
							$inPDF->MultiCell (10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
							$inPDF->MultiCell (257,$h,$tmpZone,1,'L',0,1,'','',1,0,0,1,$h,'M');
						}
						else
						{
							$inPDF->commitTransaction();
						}
					}

					//Подготовка
					switch($vRowF[idFactor])
					{
/*						case 13:
							switch($vRowF[var5])
							{
								case 0:	$tmpType = 'ШП'; break;
								case 1:	$tmpType = 'ТП'; break;
								case 2:	$tmpType = 'Шк'; break;
								case 3:	$tmpType = 'Шп'; break;
								case 4:	$tmpType = 'Ши'; break;
								case 5:	$tmpType = 'Тк'; break;
								case 6:	$tmpType = 'Тп'; break;
								case 7:	$tmpType = 'Ти'; break;
								default: $tmpType = '—'; break;
							}
							PDF_InsertFactor($inPDF, ' - Шум, дБА', $vRowF[var1], $vRowF[fPdu1], '—', $vRowF[sTime], $tmpType);
							//Эквивалент
							array_push($aNoiseLevel, $vRowF[var1]);
							array_push($aNoiseTime, $vRowF[sTime]);
						break;*/
						case 14:
							array_push($aInfraNoiseLevel, $vRowF[var1]);
							array_push($aInfraNoiseTime, $vRowF[sTime]);
							PDF_InsertFactor($inPDF, ' - Инфразвук, дБЛин', $vRowF[var1], $vRowF[fPdu1], '—', $vRowF[sTime], '—');
						break;
						case 15:
							array_push($aUltraNoiseLevel, $vRowF[var1]);
							array_push($aUltraNoisePdu, $vRowF[fPdu1]);
							array_push($aUltraNoiseTime, $vRowF[sTime]);
							PDF_InsertFactor($inPDF, ' - Ультразвук воздушный, дБ', $vRowF[var1], $vRowF[fPdu1], StringWork::iToClassNameLite($vRowF[iAsset]), $vRowF[sTime], '—');
						break;
						case 16:
							array_push($aVibroOLevelX, $vRowF[var1]);
							array_push($aVibroOLevelY, $vRowF[var2]);
							array_push($aVibroOLevelZ, $vRowF[var3]);
							array_push($aVibroOTime, $vRowF[sTime]);
							switch($vRowF[var5])
							{
								case 0:	$tmpType = '1'; break;
								case 1:	$tmpType = '2'; break;
								case 2:	$tmpType = '3а'; break;
								case 3:	$tmpType = '3б'; break;
								case 4:	$tmpType = '3в'; break;
								default: $tmpType = '—'; break;
							}
							PDF_InsertFactor($inPDF, ' - Общая вибрация, ось Х, дБ', $vRowF[var1], $vRowF[fPdu1], '—', $vRowF[sTime], $tmpType);
							PDF_InsertFactor($inPDF, ' - Общая вибрация, ось Y, дБ', $vRowF[var2], $vRowF[fPdu2], '—', $vRowF[sTime], $tmpType);
							PDF_InsertFactor($inPDF, ' - Общая вибрация, ось Z, дБ', $vRowF[var3], $vRowF[fPdu3], '—', $vRowF[sTime], $tmpType);
						break;
						case 54:
							array_push($aVibroLLevelX, $vRowF[var1]);
							array_push($aVibroLLevelY, $vRowF[var2]);
							array_push($aVibroLLevelZ, $vRowF[var3]);
							array_push($aVibroLTime, $vRowF[sTime]);
							PDF_InsertFactor($inPDF, ' - Локальная вибрация, ось Х, дБ', $vRowF[var1], $vRowF[fPdu1], '—', $vRowF[sTime], '—');
							PDF_InsertFactor($inPDF, ' - Локальная вибрация, ось Y, дБ', $vRowF[var2], $vRowF[fPdu2], '—', $vRowF[sTime], '—');
							PDF_InsertFactor($inPDF, ' - Локальная вибрация, ось Z, дБ', $vRowF[var3], $vRowF[fPdu3], '—', $vRowF[sTime], '—');
						break;
					}
				}
			$inPDF->SetFont($infontnamebold, 'BI', 10, '', 'false');
			if(count($aNoiseLevel)>0) PDF_InsertFactor($inPDF, ' - Шум, эквивалентный уровень звука, дБА',
			NoiseEql($aNoiseLevel, $aNoiseTime, $fWorkDay, true)[0], '80', StringWork::iToClassNameLite($vRow[iANoise]), '—', '—');
			if(count($aInfraNoiseLevel)>0) PDF_InsertFactor($inPDF, ' - Инфразвук, эквивалентный общий уровень звукового давления, дБЛин', NoiseEql($aInfraNoiseLevel, $aInfraNoiseTime, $fWorkDay), '110', StringWork::iToClassNameLite($vRow[iAInfraNoise]), '—', '—');
			if(count($aVibroOLevelX)>0)
			{
				PDF_InsertFactor($inPDF, ' - Общая вибрация, ось Х, эквивалентный корр. уровень виброускорения, дБ', NoiseEql($aVibroOLevelX, $aVibroOTime, $fWorkDay), '112', StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_TotalVibro(NoiseEql($aVibroOLevelX, $aVibroOTime, $fWorkDay),'X')), '—', '—');
				PDF_InsertFactor($inPDF, ' - Общая вибрация, ось Y, эквивалентный корр. уровень виброускорения, дБ', NoiseEql($aVibroOLevelY, $aVibroOTime, $fWorkDay), '112', StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_TotalVibro(NoiseEql($aVibroOLevelY, $aVibroOTime, $fWorkDay),'Y')), '—', '—');
				PDF_InsertFactor($inPDF, ' - Общая вибрация, ось Z, эквивалентный корр. уровень виброускорения, дБ', NoiseEql($aVibroOLevelZ, $aVibroOTime, $fWorkDay), '115', StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_TotalVibro(NoiseEql($aVibroOLevelZ, $aVibroOTime, $fWorkDay),'Z')), '—', '—');
			}
			if(count($aVibroLLevelX)>0)
			{
				PDF_InsertFactor($inPDF, ' - Локальная вибрация, ось Х, эквивалентный корр. уровень виброускорения, дБ', NoiseEql($aVibroLLevelX, $aVibroLTime, $fWorkDay), '126', StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_LocalVibro(NoiseEql($aVibroLLevelX, $aVibroLTime, $fWorkDay))), '—', '—');
				PDF_InsertFactor($inPDF, ' - Локальная вибрация, ось Y, эквивалентный корр. уровень виброускорения, дБ', NoiseEql($aVibroLLevelY, $aVibroLTime, $fWorkDay), '126', StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_LocalVibro(NoiseEql($aVibroLLevelY, $aVibroLTime, $fWorkDay))), '—', '—');
				PDF_InsertFactor($inPDF, ' - Локальная вибрация, ось Z, эквивалентный корр. уровень виброускорения, дБ', NoiseEql($aVibroLLevelZ, $aVibroLTime, $fWorkDay), '126', StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_LocalVibro(NoiseEql($aVibroLLevelZ, $aVibroLTime, $fWorkDay))), '—', '—');
			}

/*
			if(count($aVibroOLevelX)>0) $arrAssets[iAVibroO] = max(WorkFactors::GetFactorAsset_TotalVibro(NoiseEql($aVibroOLevelX, $aVibroOTime, $fWorkDay),'X'),WorkFactors::GetFactorAsset_TotalVibro(NoiseEql($aVibroOLevelY, $aVibroOTime, $fWorkDay),'Y'),WorkFactors::GetFactorAsset_TotalVibro(NoiseEql($aVibroOLevelZ, $aVibroOTime, $fWorkDay),'Z'));
			if(count($aVibroLLevelX)>0) $arrAssets[iAVibroL] = max(WorkFactors::GetFactorAsset_LocalVibro(NoiseEql($aVibroLLevelX, $aVibroLTime, $fWorkDay)),WorkFactors::GetFactorAsset_LocalVibro(NoiseEql($aVibroLLevelY, $aVibroLTime, $fWorkDay)),WorkFactors::GetFactorAsset_LocalVibro(NoiseEql($aVibroLLevelZ, $aVibroLTime, $fWorkDay)));*/
			$inPDF->SetFont($infontname, 'BI', 10, '', 'false');

			}
		}
	}

	if(!$bRow)
	{
		$inPDF->MultiCell(267,10,'Результаты отсутствуют.',1,'C',0,1,'','',1,0,0,1,10,'M');
	}
	else
	{
		$inPDF->SetFont($infontname, 'BI', 12, '', 'false');
		PDF_insert_EndText($inPDF, '* - Для шума: Ш - широкополосный; Т - тональный; П - постоянный; к, п, и - непостоянный колеблющийся, прерывистый, импульсный.<br />Для вибрации: 1 - категория 1; 2 - категория 2; 3а, 3б, 3в - 3 категория соответствующего типа.', $iRmCount, $iRmCountWarning, $sRmCountWarning, $idWorkGroup);
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$h = 0;
	$inPDF->MultiCell	(10,$h,'1',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(141,$h,'2',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'3',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'4',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'5',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'6',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'7',1,'C',1,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertFactor($inPDF, $sName, $sVar, $sPdu, $sAsset, $fTime, $sType)
{
	$num_pages = $inPDF->getNumPages();
	$inPDF->startTransaction();
	//Указание зоны
	$rowcount = max($inPDF->getNumLines($sName, 141),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($sType, 13));
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(141,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,$sType,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');

	if($num_pages < $inPDF->getNumPages())
	{
		$inPDF->rollbackTransaction(true);
		$inPDF->AddPage();
		//Вставка заголовка
		PDF_InsertHeaderRmList($inPDF);
		$rowcount = max($inPDF->getNumLines($sName, 141),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($sType, 13));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(141,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sType,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
	}
	else
	{
		$inPDF->commitTransaction();
	}
}
?>

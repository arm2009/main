<?
	ini_set('memory_limit','128M');
	$sDocName = '1.2_Protocol_Light.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	//ID организации
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);

	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$sProtokolNum = StringWork::CheckNullStrLite($agroup[sPNumLight]);
	$pdf->tmpOrgName = '<br>'.UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid);
	$pdf->tmpDocType = 'Протокол оценки световой среды № '.$sProtokolNum;

	//====================================================================================================
	//====================================================================================================
	//====================================================================================================
	//Шапка
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." AND `Arm_rmFactors`.`idFactorGroup` = 17 ORDER BY `dtControl`;";
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
	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target." AND (`sFactName` LIKE '%Свет%' OR `sFactName` LIKE '%Освещ%');";
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
<td align="center"><font face="calibrib"><h2>ПРОТОКОЛ № '.$sProtokolNum.'<br />результатов исследования, измерения и оценки условий труда<br />по параметрам световой среды</h2></font></td>
</tr>
<tr><td align="Left">1. Полное наименование работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sFullName]).'</font></td></tr>
<tr><td align="Left">2. Место нахождения и место осуществления деятельности работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sPlace]).'</font></td></tr>
<tr><td align="Left">3. Дата проведения исследований, измерений: <font face="calibrib">'.StringWork::CheckNullStrFull($sDateIzm).'</font></td></tr>
<tr><td align="Left">4. Сведения о применяемых средствах измерений: '.$sOborud.'</td></tr>
<tr nobr="true"><td align="Left">5. Наименование примененных методов исследований, измерений, нормативно правовых актов регламентирующих нормативные уровни (ПДК/ПДУ): <font face="calibrib"><ul><li>Приказ Минтруда России №33н от 24 января 2014 г. "Об утверждении методики проведения специальной оценки условий труда, классификатора вредных и (или) опасных производственных факторов, формы отчета о проведении специальной оценки условий труда и инструкции по её заполнению";</li><li>Санитарные правила и нормы СанПиН 2.2.1/2.1.1.1278-03 утвержден Главным государственным санитарным врачом РФ 6 апреля 2003 г. «Гигиенические требования к естественному, искусственному и совмещенному освещению жилых и общественных зданий»;</li><li>Строительные нормы и правила СНиП 23-05-95 "Естественное и искусственное освещение" утверждены постановлением Минстроя РФ от 2 августа 1995 г. N 18-78;</li>'.$sOborudMethod.'</ul></font></td></tr></table>';		
	//====================================================================================================
	//====================================================================================================
	//====================================================================================================


$pdf->SetFont($fontname, 'BI', 11, '', 'false');
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
	$sql2 = "SELECT `sNLight` FROM `Arm_group` WHERE `id` = ".$iTarget.";";
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
	$inPDF->MultiCell	(102,$h,'Наименование структурного подразделения, рабочего места; места проведения измерения; исследуемого фактора, ед. изм.',1,'C',1,0,'','',1,0,0,1,$h,'M');
//	$inPDF->SetFont($infontname, 'BI', 8, '', 'false');

	$inPDF->StartTransform();
	$inPDF->Rotate(90, 127, 65);
	$inPDF->MultiCell	($h,13,'Время воздействия, ч.',1,'C',1,0,127,65,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Рабочая поверхность, плоскость нормирования, м.',1,'C',1,0,127,78,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Высота подвеса ламп, м.',1,'C',1,0,127,91,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Не горящие лампы, %.',1,'C',1,0,127,104,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Тип источника света',1,'C',1,0,127,117,1,0,0,1,13,'M');
	$inPDF->StopTransform();
	
	$inPDF->MultiCell	(30,$h,'Нормативное значение',1,'C',1,0,192,15,1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Фактическое значение',1,'C',1,0,222,15,1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Итоговый класс',1,'C',1,1,252,15,1,0,0,1,$h,'M');
	PDF_InsertHeaderRmList($inPDF);
	
	//Комментарий
	$sHeavycomment = '** &#8212; В числителе оценка для мужчин, в знаменателе для женщин.';
	$bHeavycomment = false;

	
	$sql = "SELECT `id`, `sName`, `idParent`, `iNumber`, `sNumAnalog`, `iALight` FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$bRow = false;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$sql = "SELECT `Arm_rmFactors`.`idFactorGroup`,`Arm_rmFactors`.`idFactor`,`Arm_rmFactors`.`sName`, `Arm_rmFactors`.`var1`, `Arm_rmFactors`.`var2`, `Arm_rmFactors`.`var3`, `Arm_rmFactors`.`var4`, `Arm_rmFactors`.`var5`, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, `Arm_rmFactorsPdu`.`iAsset`, `Arm_rmPoints`.`sName` AS `sZoneName`, `Arm_rmPointsRm`.`sTime`, `Arm_rmPoints`.`sLightPolygone`, `Arm_rmPoints`.`sLightHeight`, `Arm_rmPoints`.`sLightDark`, `Arm_rmPoints`.`sLightType` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) LEFT JOIN `Arm_rmPoints` ON (`Arm_rmPoints`.`id` = `Arm_rmFactors`.`idPoint`) LEFT JOIN `Arm_rmPointsRm` ON (`Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_workplace`.`id`) WHERE `Arm_workplace`.`id` =".$vRow[id]." AND `Arm_rmFactors`.`idFactorGroup` = 17 ORDER BY `Arm_rmPoints`.`sName`, `Arm_rmFactors`.`idFactor`;";
			$vResultF = DbConnect::GetSqlQuery($sql);
			if (mysql_num_rows($vResultF) > 0)
			{
				$bRow = true;
				$iRmCount++;
				
				if($vRow[iALight] > 2)
				{
					$iRmCountWarning++;
					if(strlen($sRmCountWarning) > 0) $sRmCountWarning .= ', ';
					$sRmCountWarning .= $vRow[iNumber];
				}
				$vRow[iALight] = StringWork::iToClassNameLite($vRow[iALight]);
				
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
				$inPDF->MultiCell	(30,$h,$vRow[iALight],1,'C',0,1,'','',1,0,0,1,$h,'M');
				$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				if($num_pages < $inPDF->getNumPages())
				{
					$inPDF->rollbackTransaction(true);
					$inPDF->AddPage();
					//Вставка заголовка	
					PDF_InsertHeaderRmList($inPDF);
					$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(30,$h,$vRow[iALight],1,'C',0,1,'','',1,0,0,1,$h,'M');
					$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				}
				else
				{
					$inPDF->commitTransaction();
				}

				$tmpZone = '';
				
				//Переменные
				while($vRowF = mysql_fetch_array($vResultF))
				{
					if($tmpZone != $vRowF[sZoneName] .' - Освещенность рабочей поверхности при искусственном освещении, лк.')
					{
						$tmpZone = $vRowF[sZoneName] .' - Освещенность рабочей поверхности при искусственном освещении, лк.';
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
						case 18:
							$num_pages = $inPDF->getNumPages();
							$inPDF->startTransaction();
							PDF_InsertHeavyS($inPDF, 'Освещенность рабочей поверхности при искусственном освещении, лк.', $vRowF[var1], $vRowF[fPdu1], $vRowF[var2], $vRowF[fPdu2], StringWork::iToClassNameLite($vRowF[iAsset]), $vRowF[sTime], $vRowF[sLightPolygone], $vRowF[sLightHeight], $vRowF[sLightType], $vRowF[sLightDark]);
							if($num_pages < $inPDF->getNumPages())
							{
								$inPDF->rollbackTransaction(true);
								$inPDF->AddPage();
								//Вставка заголовка	
								PDF_InsertHeaderRmList($inPDF);
								PDF_InsertHeavyS($inPDF, 'Освещенность рабочей поверхности при искусственном освещении, лк.', $vRowF[var1], $vRowF[fPdu1], $vRowF[var2], $vRowF[fPdu2], StringWork::iToClassNameLite($vRowF[iAsset]), $vRowF[sTime], $vRowF[sLightPolygone], $vRowF[sLightHeight], $vRowF[sLightType], $vRowF[sLightDark]);
							}
							else
							{
								$inPDF->commitTransaction();
							}
						break;
						case 19:

						break;
						case 20:

						break;
					}
				}
				
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
		PDF_insert_EndText($inPDF, '', $iRmCount, $iRmCountWarning, $sRmCountWarning, $idWorkGroup);
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$h = 0;
	$inPDF->MultiCell	(10,$h,'1',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(102,$h,'2',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'3',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'4',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'5',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'6',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'7',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'8',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'9',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'10',1,'C',1,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertHeavySSlep($inPDF, $sName, $sVar, $sPdu, $sVar1, $sPdu1, $sAsset, $fTime, $sPolygone, $sHeigt, $sLightType, $sNolight)
{	
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
		$rowcount = max($inPDF->getNumLines($sName, 102),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($sPolygone, 13),$inPDF->getNumLines($sHeigt, 13),$inPDF->getNumLines($sNolight, 13),$inPDF->getNumLines($sLightType, 13),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset), 30));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(102,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sPolygone,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sHeigt,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sNolight,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sLightType,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
}

function PDF_InsertHeavyS($inPDF, $sName, $sVar, $sPdu, $sVar1, $sPdu1, $sAsset, $fTime, $sPolygone, $sHeigt, $sLightType, $sNolight)
{	
	if($sVar1 == 0 && $sPdu1 == 0)
	{
		$sName = ' - Общее освещение';
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
		$rowcount = max($inPDF->getNumLines($sName, 102),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($sPolygone, 13),$inPDF->getNumLines($sHeigt, 13),$inPDF->getNumLines($sNolight, 13),$inPDF->getNumLines($sLightType, 13),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset), 30));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(102,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sPolygone,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sHeigt,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sNolight,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sLightType,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	}
	else
	{
		$sName = ' - Общее освещение';
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
		$rowcount = max($inPDF->getNumLines($sName, 102),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($sPolygone, 13),$inPDF->getNumLines($sHeigt, 13),$inPDF->getNumLines($sNolight, 13),$inPDF->getNumLines($sLightType, 13),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset), 30));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(102,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sPolygone,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sHeigt,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sNolight,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sLightType,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_Light($sVar, $sPdu)),1,'C',0,1,'','',1,0,0,1,$h,'M');
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
		$sName = ' - Комбинированное освещение';
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
		$rowcount = max($inPDF->getNumLines($sName, 102),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($sPolygone, 13),$inPDF->getNumLines($sHeigt, 13),$inPDF->getNumLines($sNolight, 13),$inPDF->getNumLines($sLightType, 13),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset), 30));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(102,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sPolygone,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sHeigt,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sNolight,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$sLightType,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPdu1,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVar1,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,StringWork::iToClassNameLite(WorkFactors::GetFactorAsset_Light($sVar1, $sPdu1)),1,'C',0,1,'','',1,0,0,1,$h,'M');
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	}
}
?>

<?
	ini_set('memory_limit','128M');
	$sDocName = '1.4_Protocol_Air.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	//ID организации
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);

	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$sProtokolNum = StringWork::CheckNullStrLite($agroup[sPNumAir]);
	$pdf->tmpOrgName = '<br>'.UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid);
	$pdf->tmpDocType = 'Протокол оценки воздуха рабочей зоны № '.$sProtokolNum;

	//====================================================================================================
	//====================================================================================================
	//====================================================================================================
	//Шапка
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." AND `Arm_rmFactors`.`idFactorGroup` IN (8, 31) ORDER BY `dtControl`;";
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
	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target." AND (`sFactName` LIKE '%Хими%' OR `sFactName` LIKE '%Аэрозол%' OR `sFactName` LIKE '%АПФД%');";
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
<td align="center"><font face="calibrib"><h2>ПРОТОКОЛ № '.$sProtokolNum.'<br />результатов исследования, измерения и оценки условий труда<br />по веществам в воздухе рабочей зоны</h2></font></td>
</tr>
<tr><td align="Left">1. Полное наименование работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sFullName]).'</font></td></tr>
<tr><td align="Left">2. Место нахождения и место осуществления деятельности работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sPlace]).'</font></td></tr>
<tr><td align="Left">3. Дата проведения исследований, измерений: <font face="calibrib">'.StringWork::CheckNullStrFull($sDateIzm).'</font></td></tr>
<tr><td align="Left">4. Сведения о применяемых средствах измерений: '.$sOborud.'</td></tr>
<tr nobr="true"><td align="Left">5. Наименование примененных методов исследований, измерений, нормативно правовых актов регламентирующих нормативные уровни (ПДК/ПДУ): <font face="calibrib"><ul><li>Приказ Минтруда России №33н от 24 января 2014 г. "Об утверждении методики проведения специальной оценки условий труда, классификатора вредных и (или) опасных производственных факторов, формы отчета о проведении специальной оценки условий труда и инструкции по её заполнению";</li><li>ГН 2.2.5.1313-03 утв. Главным государственным санитарным врачом Российской Федерации, Первым заместителем Министра здравоохранения Российской Федерации 27 апреля 2003 г. «Предельно допустимые концентрации вредных веществ в воздухе рабочей зоны. Гигиенические нормативы» (с изменениями ГН 2.2.5.1827-03, ГН 2.2.5.2100-06, ГН 2.2.5.2241-07);</li>'
.$sOborudMethod.'</ul></font></td></tr></table>';		
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
	$sql2 = "SELECT `sNAir` FROM `Arm_group` WHERE `id` = ".$iTarget.";";
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
	$bNiUrOp = false;
	
	$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	//Шапка протокола
	$h = 50;
	$inPDF->MultiCell	(10,$h,'№',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(128,$h,'Наименование структурного подразделения, рабочего места; места проведения измерения; исследуемого вещества',1,'C',1,0,'','',1,0,0,1,$h,'M');
//	$inPDF->SetFont($infontname, 'BI', 8, '', 'false');

	$inPDF->StartTransform();
	$inPDF->Rotate(90, 153, 65);
	$inPDF->MultiCell	($h,13,'Время воздействия, ч.',1,'C',1,0,153,65,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Класс опасности',1,'C',1,0,153,78,1,0,0,1,13,'M');
	$inPDF->MultiCell	($h,13,'Особенности воздействия',1,'C',1,0,153,91,1,0,0,1,13,'M');
	$inPDF->StopTransform();
	
	$inPDF->MultiCell	(30,$h,'Нормативное значение, мг/м³',1,'C',1,0,192,15,1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Фактическое значение, мг/м³',1,'C',1,0,222,15,1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Итоговый класс',1,'C',1,1,252,15,1,0,0,1,$h,'M');
	PDF_InsertHeaderRmList($inPDF);
		
	$sql = "SELECT `id`, `sName`, `idParent`, `iNumber`, `sNumAnalog`, `iAChem`, `iAAPFD` FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$bRow = false;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{		
			//Массивы однонправленных действий химии
			$aChem = array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array());
			$aChemSs = array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array());			
			
$sql = "SELECT `Arm_rmFactors`.`idFactorGroup`,`Arm_rmFactors`.`idFactor`,`Arm_rmFactors`.`sName`, `Arm_rmFactors`.`var1`, `Arm_rmFactors`.`var2`, `Arm_rmFactors`.`var3`, `Arm_rmFactors`.`var4`, `Arm_rmFactors`.`var5`, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, `Arm_rmFactorsPdu`.`iAsset`, `Arm_rmPoints`.`sName` AS `sZoneName`, `Arm_rmPointsRm`.`sTime`, `Arm_rmPoints`.`sLightPolygone`, `Arm_rmPoints`.`sLightHeight`, `Arm_rmPoints`.`sLightDark`, `Arm_rmPoints`.`sLightType`, `Arm_rmFactorsPdu`.`sAddonAsset` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) LEFT JOIN `Arm_rmPoints` ON (`Arm_rmPoints`.`id` = `Arm_rmFactors`.`idPoint`) LEFT JOIN `Arm_rmPointsRm` ON (`Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_workplace`.`id`) WHERE `Arm_workplace`.`id` =".$vRow[id]." AND (`Arm_rmFactors`.`idFactorGroup` = 8 OR `Arm_rmFactors`.`idFactorGroup` = 31) ORDER BY `Arm_rmPoints`.`sName`, `Arm_rmFactors`.`idFactorGroup`, `Arm_rmFactors`.`idFactor`;";
			$vResultF = DbConnect::GetSqlQuery($sql);
			if (mysql_num_rows($vResultF) > 0)
			{
				$bRow = true;
				$iRmCount++;
				

				$vRow[iAChem]	= max($vRow[iAChem],$vRow[iAAPFD]);
				if($vRow[iAChem] > 2)
				{
					$iRmCountWarning++;
					if(strlen($sRmCountWarning) > 0) $sRmCountWarning .= ', ';
					$sRmCountWarning .= $vRow[iNumber];
				}
				$vRow[iAChem] = StringWork::iToClassNameLite($vRow[iAChem]);
				
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
				$inPDF->MultiCell	(30,$h,$vRow[iAChem],1,'C',0,1,'','',1,0,0,1,$h,'M');
				$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				if($num_pages < $inPDF->getNumPages())
				{
					$inPDF->rollbackTransaction(true);
					$inPDF->AddPage();
					//Вставка заголовка	
					PDF_InsertHeaderRmList($inPDF);
					$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(30,$h,$vRow[iAChem],1,'C',0,1,'','',1,0,0,1,$h,'M');
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
					if($tmpZone != $vRowF[sZoneName])
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
					
					//Заполнение массивов для суммации массивы нужно предварительно создать.
					$retChem = Summ_Add_Chem($vRowF[sAddonAsset], $vRowF[var1], $vRowF[var2], $vRowF[fPdu1], $vRowF[fPdu2], $aChem, $aChemSs);
					$aChem = $retChem[0]; $aChemSs = $retChem[1];

					//Подготовка
					if($vRowF[var1] == 0) {$vRowF[var1] = "< min*";$bNiUrOp = true;}
					if($vRowF[var2] == 0) {$vRowF[var2] = "< min*";$bNiUrOp = true;}
					PDF_InsertFactor($inPDF, $vRowF[idFactor], $vRowF[var1], $vRowF[fPdu1], $vRowF[var2], $vRowF[fPdu2], StringWork::iToClassNameLite($vRowF[iAsset]), $vRowF[sTime]);
				}
				//Функция конечной оценки суммации, возвращает массив из четырех массивов ($aSummMr,$aSumMrAss,$aSummSs,$aSumSsAss)
				//В каждом массиве 17 значений. $aSummMr,$aSummSs - значения суммаций, $aSumMrAss,$aSumSsAss - оценки суммаций.
				$aChemSumm = Summ_Chem_Ass($aChem, $aChemSs);
				$inPDF->SetFont($infontnamebold, 'BI', 10, '', 'false');
				for($i=0;$i<17;$i++)
				{
					if(max($aChemSumm[0][$i],$aChemSumm[2][$i])>0)
					{
						if($aChemSumm[0][$i] > $aChemSumm[2][$i])
						PDF_InsertSummacia($inPDF, $i, '', $aChemSumm[0][$i], StringWork::iToClassNameLite($aChemSumm[1][$i]));
						else
						PDF_InsertSummacia($inPDF, $i, '', $aChemSumm[2][$i], StringWork::iToClassNameLite($aChemSumm[3][$i]));
					}
				}
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
		if($bNiUrOp) {$prime = "* – полученные в ходе измерений значения меньше минимального предела определения средств измерения.";} else {$prime = "";}
		$inPDF->SetFont($infontname, 'BI', 12, '', 'false');
		PDF_insert_EndText($inPDF, $prime.'', $iRmCount, $iRmCountWarning, $sRmCountWarning, $idWorkGroup);
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	}
}

function PDF_InsertSummacia ($inPDF, $SummaciaId, $SummaciaText, $SummaciaValue, $SummaciaAsset)
{
	switch($SummaciaId)
	{
		case 0:
			$SummaciaText = 'вещества раздражающего типа действия (кислоты и щелочи)'.$SummaciaText;
		break;
		case 1:
			$SummaciaText = 'аэрозоли преимущественно фиброгенного действия'.$SummaciaText;
		break;
		case 2:
			$SummaciaText = 'химические вещества наркотического типа действия (комбинации спиртов), кроме наркотических анальгетиков'.$SummaciaText;
		break;
		case 3:
			$SummaciaText = 'аэрозоли преимущественно фиброгенного действия'.$SummaciaText;
		break;
		case 4:
			$SummaciaText = 'химические вещества канцерогенные для человека'.$SummaciaText;
		break;
		case 5:
			$SummaciaText = 'химические вещества опасные для репродуктивного здоровья человека'.$SummaciaText;
		break;
		case 6:
			$SummaciaText = 'ферменты микробного происхождения'.$SummaciaText;
		break;
		case 7:
			$SummaciaText = 'хлорированные углеводороды (предельные и непредельные)'.$SummaciaText;
		break;
		case 8:
			$SummaciaText = 'бромированные углеводороды (предельные и непредельные)'.$SummaciaText;
		break;
		case 9:
			$SummaciaText = 'различные спирты'.$SummaciaText;
		break;
		case 10:
			$SummaciaText = 'различные щелочи'.$SummaciaText;
		break;
		case 11:
			$SummaciaText = 'ароматические углеводороды'.$SummaciaText;
		break;
		case 12:
			$SummaciaText = 'аминосоединения'.$SummaciaText;
		break;
		case 13:
			$SummaciaText = 'нитросоединения'.$SummaciaText;
		break;
		case 14:
			$SummaciaText = 'оксиды азота и оксид углерода'.$SummaciaText;
		break;
		case 15:
			$SummaciaText = 'аминосоединения и оксид углерода'.$SummaciaText;
		break;
		case 16:
			$SummaciaText = 'нитросоединения и оксид углерода'.$SummaciaText;
		break;
	}

$SummaciaText = ' - Эффект суммации, '.$SummaciaText;	
	$num_pages = $inPDF->getNumPages();
	$inPDF->startTransaction();
	$rowcount = max($inPDF->getNumLines($SummaciaText, 167),$inPDF->getNumLines($SummaciaValue, 30),$inPDF->getNumLines($SummaciaAsset, 30));
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,$SummaciaText,1,'L',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'1',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$SummaciaValue,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$SummaciaAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
	
	if($num_pages < $inPDF->getNumPages())
	{
		$inPDF->rollbackTransaction(true);
		$inPDF->AddPage();
		//Вставка заголовка	
		PDF_InsertHeaderRmList($inPDF);
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(167,$h,$SummaciaText,1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,'1',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$SummaciaValue,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$SummaciaAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
	}
	else
	{
		$inPDF->commitTransaction();
	}

}
function PDF_InsertHeaderRmList($inPDF)
{
	$h = 0;
	$inPDF->MultiCell	(10,$h,'1',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(128,$h,'2',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'3',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'4',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(13,$h,'5',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'6',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'7',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'8',1,'C',1,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertFactor($inPDF, $inId, $sVarMR, $sPduMR, $sVarSS, $sPduSS, $sAsset, $fTime)
{
	$sql = "SELECT * FROM `Nd_gn1313` WHERE `id` = ".$inId.";";
	$cRow = DbConnect::GetSqlRow($sql);
	$cRow[sName] = trim($cRow[sName]);
	$cRow[sClass] = trim($cRow[sClass]);
	$cRow[sFeat] = trim($cRow[sFeat]);
	
	if($sPduMR > -1)
	{
		$num_pages = $inPDF->getNumPages();
		$inPDF->startTransaction();
		//Указание зоны
		$rowcount = max($inPDF->getNumLines(' - '.$cRow[sName].', Максимально разовая концентрация', 128),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($cRow[sClass], 13),$inPDF->getNumLines($cRow[sFeat], 13),$inPDF->getNumLines($sPduMR, 30),$inPDF->getNumLines($sVarMR, 30),$inPDF->getNumLines($sAsset, 30));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(128,$h,' - '.$cRow[sName].', максимально разовая концентрация',1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sClass]),1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sFeat]),1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPduMR,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVarMR,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
		
		if($num_pages < $inPDF->getNumPages())
		{
			$inPDF->rollbackTransaction(true);
			$inPDF->AddPage();
			//Вставка заголовка	
			PDF_InsertHeaderRmList($inPDF);
			$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(128,$h,' - '.$cRow[sName].', максимально разовая концентрация',1,'L',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sClass]),1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sFeat]),1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(30,$h,$sPduMR,1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(30,$h,$sVarMR,1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
		}
		else
		{
			$inPDF->commitTransaction();
		}
	}
	if($sPduSS > -1)
	{
		$num_pages = $inPDF->getNumPages();
		$inPDF->startTransaction();
		//Указание зоны
		$rowcount = max($inPDF->getNumLines(' - '.$cRow[sName].', среднесменная концентрация', 128),$inPDF->getNumLines($fTime, 13),$inPDF->getNumLines($cRow[sClass], 13),$inPDF->getNumLines($cRow[sFeat], 13),$inPDF->getNumLines($sPduSS, 30),$inPDF->getNumLines($sVarSS, 30),$inPDF->getNumLines($sAsset, 30));
		$h = $rowcount*4.5;
		$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(128,$h,' - '.$cRow[sName].', среднесменная концентрация',1,'L',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sClass]),1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sFeat]),1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sPduSS,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sVarSS,1,'C',0,0,'','',1,0,0,1,$h,'M');
		$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
		
		if($num_pages < $inPDF->getNumPages())
		{
			$inPDF->rollbackTransaction(true);
			$inPDF->AddPage();
			//Вставка заголовка	
			PDF_InsertHeaderRmList($inPDF);
			$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(128,$h,' - '.$cRow[sName].', среднесменная концентрация',1,'L',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(13,$h,$fTime,1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sClass]),1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(13,$h,StringWork::CheckNullStrLite($cRow[sFeat]),1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(30,$h,$sPduSS,1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(30,$h,$sVarSS,1,'C',0,0,'','',1,0,0,1,$h,'M');
			$inPDF->MultiCell	(30,$h,$sAsset,1,'C',0,1,'','',1,0,0,1,$h,'M');
		}
		else
		{
			$inPDF->commitTransaction();
		}
	}
}
?>

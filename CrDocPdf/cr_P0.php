<?
	ini_set('memory_limit','128M');
	$sDocName = '1.0_Protocol_Heavy.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	//ID организации
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);



	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$sProtokolNum = StringWork::CheckNullStrLite($agroup[sPNumHeavy]);
	$pdf->tmpOrgName = '<br>'.UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid);
	$pdf->tmpDocType = 'Протокол оценки тяжести трудового процесса № '.$sProtokolNum;

	//====================================================================================================
	//====================================================================================================
	//====================================================================================================
	//Шапка
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." AND `Arm_rmFactors`.`idFactorGroup` = 37 ORDER BY `dtControl`;";
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
	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target." AND `sFactName` LIKE '%Тяжесть трудового процесса%';";
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
<td align="center"><font face="calibrib"><h2>ПРОТОКОЛ № '.$sProtokolNum.'<br />результатов исследования, измерения и оценки условий труда<br />по показателям тяжести трудового процесса</h2></font></td>
</tr>
<tr><td align="Left">1. Полное наименование работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sFullName]).'</font></td></tr>
<tr><td align="Left">2. Место нахождения и место осуществления деятельности работодателя: <font face="calibrib">'.StringWork::CheckNullStrFull($agroup[sPlace]).'</font></td></tr>
<tr><td align="Left">3. Дата проведения исследований, измерений: <font face="calibrib">'.StringWork::CheckNullStrFull($sDateIzm).'</font></td></tr>
<tr><td align="Left">4. Сведения о применяемых средствах измерений: '.$sOborud.'</td></tr>
<tr nobr="true"><td align="Left">5. Наименование примененных методов исследований, измерений, нормативно правовых актов регламентирующих нормативные уровни (ПДК/ПДУ): <font face="calibrib"><ul><li>Приказ Минтруда России №33н от 24 января 2014 г. "Об утверждении методики проведения специальной оценки условий труда, классификатора вредных и (или) опасных производственных факторов, формы отчета о проведении специальной оценки условий труда и инструкции по её заполнению"</li>'.$sOborudMethod.'</ul></font></td></tr>
</table>
';		
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
	$sql2 = "SELECT `sNHeavy` FROM `Arm_group` WHERE `id` = ".$iTarget.";";
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
	$h = 10;
	$inPDF->MultiCell	(10,$h,'№',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,'Наименование структурного подразделения, рабочего места / исследуемого фактора, ед. изм.',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Нормативное значение',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Фактическое значение',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'Итоговый класс',1,'C',1,1,'','',1,0,0,1,$h,'M');
	PDF_InsertHeaderRmList($inPDF);
	
	//Комментарий
	$sHeavycomment = '** &#8212; В числителе оценка для мужчин, в знаменателе для женщин.';
	$bHeavycomment = false;

	
	$sql = "SELECT `id`, `sName`, `idParent`, `iNumber`, `sNumAnalog`, `iAHeavyW`, `iAHeavyM`, `iAHeavy` FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$bRow = false;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$sql = "SELECT `Arm_rmFactors`.`idFactorGroup`,`Arm_rmFactors`.`idFactor`,`Arm_rmFactors`.`sName`, `Arm_rmFactors`.`var1`, `Arm_rmFactors`.`var2`, `Arm_rmFactors`.`var3`, `Arm_rmFactors`.`var4`, `Arm_rmFactors`.`var5`, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, `Arm_rmFactorsPdu`.`iAsset` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`id` = ".$vRow[id]." AND `Arm_rmFactors`.`idFactorGroup` = 37 ORDER BY `Arm_rmFactors`.`idFactor`;";
			$vResultF = DbConnect::GetSqlQuery($sql);
			if (mysql_num_rows($vResultF) > 0)
			{
				$bRow = true;
				//Аналогичность
				if (strlen($vRow[sNumAnalog]) > 0)
				{
					$vRow[iNumber] = $vRow[iNumber].'А';
				}

				//Название подразделения
				$tmpUnitName = html_entity_decode(DbConnect::GetSqlCell("SELECT `sName` FROM `Arm_workplace` WHERE `id` = ".$vRow[idParent].";").', '. $vRow[sName]);
				$inPDF->SetFont($infontnamebold, 'BI', 10, '', 'false');
				
				//Оценка по тяжести
				if($vRow[iAHeavy] > 0)
				{
					//Счетчик для заключения
					$iRmCount++;
					if($vRow[iAHeavy] > 2)
					{
						$iRmCountWarning++;
						if(strlen($sRmCountWarning) > 0) $sRmCountWarning .= ', ';
						$sRmCountWarning .= $vRow[iNumber];
					}
					
					if($vRow[iAHeavyM] == $vRow[iAHeavyW])
					{
						$vRow[iAHeavy] = StringWork::iToClassNameLite($vRow[iAHeavy]);
					}
					else
					{
						$bHeavycomment = true;
						$vRow[iAHeavy] = StringWork::iToClassNameLite($vRow[iAHeavyM]).'/'.StringWork::iToClassNameLite($vRow[iAHeavyW]).'**';
					}
				}
				else
				{
					$vRow[iAHeavy] = StringWork::iToClassNameLite($vRow[iAHeavy]);
				}

				//Данные рабочего места
				$num_pages = $inPDF->getNumPages();
				$inPDF->startTransaction();

				$rowcount = max($inPDF->getNumLines($tmpUnitName, 227),$inPDF->getNumLines($vRow[iNumber], 10));
				$h = $rowcount*4.5;
				$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
				$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
				$inPDF->MultiCell	(30,$h,$vRow[iAHeavy],1,'C',0,1,'','',1,0,0,1,$h,'M');
				$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				if($num_pages < $inPDF->getNumPages())
				{
					$inPDF->rollbackTransaction(true);
					$inPDF->AddPage();
					//Вставка заголовка	
					PDF_InsertHeaderRmList($inPDF);
					$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(30,$h,$vRow[iAHeavy],1,'C',0,1,'','',1,0,0,1,$h,'M');
					$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				}
				else
				{
					$inPDF->commitTransaction();
				}

				
				//Переменные
				$aHeavyTotal = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
				$aHeavyTotalM = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
				$aHeavyTotalW = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);

				while($vRowF = mysql_fetch_array($vResultF))
				{
					//Подготовка
					switch($vRowF[idFactor])
					{
						case '39':
							$aHeavyTotal[11] += $vRowF[var1];
							$aHeavyTotal[12] += $vRowF[var2];
							$aHeavyTotal[13] += $vRowF[var3];
						break;
						//Масса поднимаемого и перемещаемого груза вручную
						case '40':
							$aHeavyTotal[21] = max($vRowF[var1], $aHeavyTotal[21]);
							$aHeavyTotal[22] = max($vRowF[var2], $aHeavyTotal[22]);
							$aHeavyTotal[23] += $vRowF[var3];
							$aHeavyTotal[24] += $vRowF[var4];
						break;
						//Стереотипные рабочие движения
						case '41':
							$aHeavyTotal[31] += $vRowF[var1];
							$aHeavyTotal[32] += $vRowF[var2];
						break;
						//Статическая нагрузка
						case '42':
							$aHeavyTotal[41] += $vRowF[var1];
							$aHeavyTotal[42] += $vRowF[var2];
							$aHeavyTotal[43] += $vRowF[var3];
						break;
						//Рабочая поза
						case '43':
							$aHeavyTotal[51] = max($vRowF[var1], $aHeavyTotal[51]);
						break;
						//Наклоны корпуса тела работника
						case '44':
							$aHeavyTotal[61] += $vRowF[var1];
						break;
						//Перемещение в пространстве
						case '45':
							$aHeavyTotal[71] += $vRowF[var1];
							$aHeavyTotal[72] += $vRowF[var2];
						break;
					}
				}
				
				//Оценки
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
				
				//Забой				
				PDF_InsertHeavy($inPDF, 11, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Физическая динамическая нагрузка, при региональной нагрузке перемещаемого работником груза (с преимущественным участием мышц рук и плечевого пояса работника) при перемещении груза на расстояние до 1 м., кг*м.', 'до 5000', 'до 3000');
				PDF_InsertHeavy($inPDF, 12, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Физическая динамическая нагрузка, при общей нагрузке перемещаемого работником груза (с участием мышц рук, корпуса, ног тела работника), при перемещении работником груза на расстояние от 1 до 5 м., кг*м.', 'до 25000', 'до 15000');
				PDF_InsertHeavy($inPDF, 13, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Физическая динамическая нагрузка, при общей нагрузке перемещаемого работником груза (с участием мышц рук, корпуса, ног тела работника), при перемещении работником груза на расстояние более 5 м., кг*м.', 'до 46000', 'до 28000');
				PDF_InsertHeavy($inPDF, 21, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Подъем и перемещение (разовое) тяжести при чередовании с другой работой (до 2 раз в час), кг.', 'до 30', 'до 10');
				PDF_InsertHeavy($inPDF, 22, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Подъем и перемещение тяжести постоянно в течение рабочего дня (смены) (более 2 раз в час), кг.', 'до 15', 'до 7');
				PDF_InsertHeavy($inPDF, 23, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Суммарная масса грузов, перемещаемых в течение каждого часа рабочего дня (смены) с рабочей поверхности, кг.', 'до 870', 'до 350');
				PDF_InsertHeavy($inPDF, 24, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Суммарная масса грузов, перемещаемых в течение каждого часа рабочего дня (смены) с поля, кг.', 'до 435', 'до 175');
				PDF_InsertHeavy($inPDF, 31, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Количество стереотипных рабочих движений работника при локальной нагрузке (с участием мышц кистей и пальцев рук), единиц.', 'до 40000', 'до 40000');
				PDF_InsertHeavy($inPDF, 32, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Количество стереотипных рабочих движений работника при региональной нагрузке (при работе с преимущественным участием мышц рук и плечевого пояса), единиц.', 'до 20000', 'до 20000');
				PDF_InsertHeavy($inPDF, 41, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Статическая нагрузка при удержании груза одной рукой, кгс*с.', 'до 36000', 'до 22000');
				PDF_InsertHeavy($inPDF, 42, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Статическая нагрузка при удержании груза двумя руками, кгс*с.', 'до 70000', 'до 42000');
				PDF_InsertHeavy($inPDF, 43, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Статическая нагрузка при удержании груза с участием мышц корпуса и ног, кгс*с.', 'до 100000', 'до 60000');
				PDF_InsertHeavy($inPDF, 61, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Наклоны корпуса тела работника более 30°, количество за рабочий день (смену).', '51-100', '51-100');
				PDF_InsertHeavy($inPDF, 51, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Рабочее положение тела работника в течение рабочего дня (смены)', 'Свободное удобное положение с возможностью смены рабочего положения тела (сидя, стоя). Нахождение в положении "стоя" до 40% времени рабочего дня (смены).', 'Свободное удобное положение с возможностью смены рабочего положения тела (сидя, стоя). Нахождение в положении "стоя" до 40% времени рабочего дня (смены).');
				PDF_InsertHeavy($inPDF, 71, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Перемещения работника в пространстве по горизонтали, обусловленные технологическим процессом, в течение рабочей смены, км.', 'до 8', 'до 8');
				PDF_InsertHeavy($inPDF, 72, $aHeavyTotal, $aHeavyTotalM, $aHeavyTotalW, 'Перемещения работника в пространстве по вертикали, обусловленные технологическим процессом, в течение рабочей смены, км.', 'до 2,5', 'до 2,5');
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
		if($bHeavycomment)
		PDF_insert_EndText($inPDF, $sHeavycomment, $iRmCount, $iRmCountWarning, $sRmCountWarning, $idWorkGroup);
		else
		PDF_insert_EndText($inPDF, '', $iRmCount, $iRmCountWarning, $sRmCountWarning, $idWorkGroup);
		$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$h = 0;
	$inPDF->MultiCell	(10,$h,'1',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,'2',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'3',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'4',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'5',1,'C',1,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertHeavyS($inPDF, $sName, $sVar, $sPdu, $sAsset)
{
	if($sName == 'Рабочее положение тела работника в течение рабочего дня (смены)')
	{
		switch($sVar)
		{
			case 0:
				$sVar = 'Свободное удобное положение с возможностью смены рабочего положения тела (сидя, стоя). Нахождение в положении "стоя" до 40% времени рабочего дня (смены).';
			break;
			case 1:
				$sVar = 'Периодическое, до 25% времени смены, нахождение в неудобном  и (или) фиксированном  положении. Нахождение в положении "стоя" до 60% времени рабочего дня (смены).';
			break;
			case 2:
				$sVar = 'Периодическое, до 50% времени смены, нахождение в неудобном и (или) фиксированном положении; периодическое, до 25% времени рабочего дня (смены), пребывание в вынужденном положении . Нахождение в положении "стоя" до 80% времени рабочего дня (смены). Нахождение в положении "сидя" без перерывов от 60 до 80% времени рабочего дня (смены).';
			break;
			case 3:
				$sVar = 'Периодическое, более 50% времени рабочего дня (смены), нахождение в неудобном и (или) фиксированном положении; периодическое, более 25% времени рабочего дня (смены), пребывание в вынужденном положении. Нахождение в положении "стоя" более 80% времени рабочего дня (смены). Нахождение в положении "сидя" без перерывов более 80% времени рабочего дня (смены).';
			break;
		}
	}
	
	$rowcount = max($inPDF->getNumLines($sName, 167),$inPDF->getNumLines($sVar, 30),$inPDF->getNumLines($sPdu, 30),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset), 30));
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,StringWork::iToClassNameLite($sAsset),1,'C',0,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertHeavyD($inPDF, $sName, $sVar, $sPdu1, $sPdu2, $sAsset1, $sAsset2)
{
	$rowcount = $inPDF->getNumLines($sName, 257);
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(257,$h,$sName,1,'L',0,1,'','',1,0,0,1,$h,'M');
	
	$rowcount = max($inPDF->getNumLines($sVar, 30),$inPDF->getNumLines($sPdu1, 30),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset1), 30));
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,' - мужчины:',1,'L',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sPdu1,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,StringWork::iToClassNameLite($sAsset1),1,'C',0,1,'','',1,0,0,1,$h,'M');

	$rowcount = max($inPDF->getNumLines($sVar, 30),$inPDF->getNumLines($sPdu2, 30),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset2), 30));
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,' - женщины:',1,'L',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sPdu2,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,StringWork::iToClassNameLite($sAsset2),1,'C',0,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertHeavy($inPDF, $iParam, $aHeavyTotal ,$aHeavyTotalM, $aHeavyTotalW, $sName, $sPduM, $sPduW = '')
{
	if($aHeavyTotal[$iParam] > 0) 
	{
		$num_pages = $inPDF->getNumPages();
		$inPDF->startTransaction();
		
		if($sPduM != $sPduW)
		PDF_InsertHeavyD($inPDF, $sName, $aHeavyTotal[$iParam], $sPduM, $sPduW, $aHeavyTotalM[$iParam], $aHeavyTotalW[$iParam]);
		else
		PDF_InsertHeavyS($inPDF, $sName, $aHeavyTotal[$iParam], $sPduM, $aHeavyTotalM[$iParam]);
		
		if($num_pages < $inPDF->getNumPages())
		{
			$inPDF->rollbackTransaction(true);
			$inPDF->AddPage();
			//Вставка заголовка	
			PDF_InsertHeaderRmList($inPDF);

			if($sPduM != $sPduW)
			PDF_InsertHeavyD($inPDF, $sName, $aHeavyTotal[$iParam], $sPduM, $sPduW, $aHeavyTotalM[$iParam], $aHeavyTotalW[$iParam]);
			else
			PDF_InsertHeavyS($inPDF, $sName, $aHeavyTotal[$iParam], $sPduM, $aHeavyTotalM[$iParam]);

		}
		else
		{
			$inPDF->commitTransaction();
		}
	}
}
?>

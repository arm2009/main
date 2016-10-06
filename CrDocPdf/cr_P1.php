<?
	ini_set('memory_limit','128M');
	$sDocName = '1.1_Protocol_Tennese.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	//ID организации
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);

	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$sProtokolNum = StringWork::CheckNullStrLite($agroup[sPNumTenesy]);
	$pdf->tmpOrgName = '<br>'.UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid);
	$pdf->tmpDocType = 'Протокол оценки напряженности трудового процесса № '.$sProtokolNum;

	//====================================================================================================
	//====================================================================================================
	//====================================================================================================
	//Шапка
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." AND `Arm_rmFactors`.`idFactorGroup` = 46 ORDER BY `dtControl`;";
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
	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target." AND `sFactName` LIKE '%Напряженность трудового процесса%';";
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
<td align="center"><font face="calibrib"><h2>ПРОТОКОЛ № '.$sProtokolNum.'<br />результатов исследования, измерения и оценки условий труда<br />по показателям напряженности трудового процесса</h2></font></td>
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
	$sql2 = "SELECT `sNTens` FROM `Arm_group` WHERE `id` = ".$iTarget.";";
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

	
	$sql = "SELECT `id`, `sName`, `idParent`, `iNumber`, `sNumAnalog`, `iATennese` FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$bRow = false;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$sql = "SELECT `Arm_rmFactors`.`idFactorGroup`,`Arm_rmFactors`.`idFactor`,`Arm_rmFactors`.`sName`, `Arm_rmFactors`.`var1`, `Arm_rmFactors`.`var2`, `Arm_rmFactors`.`var3`, `Arm_rmFactors`.`var4`, `Arm_rmFactors`.`var5`, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, `Arm_rmFactorsPdu`.`iAsset` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`id` = ".$vRow[id]." AND `Arm_rmFactors`.`idFactorGroup` = 46 ORDER BY `Arm_rmFactors`.`idFactor`;";
			$vResultF = DbConnect::GetSqlQuery($sql);
			if (mysql_num_rows($vResultF) > 0)
			{
				$bRow = true;
				$iRmCount++;
				
				if($vRow[iATennese] > 2)
				{
					$iRmCountWarning++;
					if(strlen($sRmCountWarning) > 0) $sRmCountWarning .= ', ';
					$sRmCountWarning .= $vRow[iNumber];
				}
				$vRow[iATennese] = StringWork::iToClassNameLite($vRow[iATennese]);
				
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
				$inPDF->MultiCell	(30,$h,$vRow[iATennese],1,'C',0,1,'','',1,0,0,1,$h,'M');
				$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				if($num_pages < $inPDF->getNumPages())
				{
					$inPDF->rollbackTransaction(true);
					$inPDF->AddPage();
					//Вставка заголовка	
					PDF_InsertHeaderRmList($inPDF);
					$inPDF->MultiCell	(10,$h,$vRow[iNumber],1,'C',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(227,$h,$tmpUnitName,1,'L',0,0,'','',1,0,0,1,$h,'M');
					$inPDF->MultiCell	(30,$h,$vRow[iATennese],1,'C',0,1,'','',1,0,0,1,$h,'M');
					$inPDF->SetFont($infontname, 'BI', 10, '', 'false');
				}
				else
				{
					$inPDF->commitTransaction();
				}

				
				//Переменные
				$aTenneseTotal = array(1=>-1,2=>-1,3=>-1,4=>-1,5=>-1,6=>-1);
				$aTenneseTotalAll = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);

				while($vRowF = mysql_fetch_array($vResultF))
				{
					//Подготовка
					switch($vRowF[idFactor])
					{
						case 48:
							if($aTenneseTotal[1] == -1) $aTenneseTotal[1] = 0;
							$aTenneseTotal[1] = max($vRowF[var1], $aTenneseTotal[1]);
						break;
						//Число производственных объектов одновременного набл юдения
						case 49:
							if($aTenneseTotal[2] == -1) $aTenneseTotal[2] = 0;
							$aTenneseTotal[2] = max($vRowF[var1], $aTenneseTotal[2]);
						break;
						//Работа с оптическими приборами
						case 52:
							if($aTenneseTotal[3] == -1) $aTenneseTotal[3] = 0;
							$aTenneseTotal[3] += $vRowF[var1];
						break;
						//Нагрузка на голосовой аппарат
						case 53:
							if($aTenneseTotal[4] == -1) $aTenneseTotal[4] = 0;
							$aTenneseTotal[4] += $vRowF[var1];
						break;
						//Число элементов (приемов), необходимых для реализации простого задания или многократно повторяющихся операций
						case 65:
							if($aTenneseTotal[5] == -1) $aTenneseTotal[5] = 0;
							$aTenneseTotal[5] = min($vRowF[var1], $aTenneseTotal[5]);
						break;
						//Монотонность производственной обстановки (время пассивного наблюдения за ходом технологического процесса в % от времени смены)
						case 66:
							if($aTenneseTotal[6] == -1) $aTenneseTotal[6] = 0;
							$aTenneseTotal[6] += $vRowF[var1];
						break;
					}
				}
				$aTenneseTotalAll[1] = WorkFactors::GetFactorAsset_Tennese_PS($aTenneseTotal[1]);
				$aTenneseTotalAll[2] = WorkFactors::GetFactorAsset_Tennese_OC($aTenneseTotal[2]);
				$aTenneseTotalAll[3] = WorkFactors::GetFactorAsset_Tennese_OP($aTenneseTotal[3]);
				$aTenneseTotalAll[4] = WorkFactors::GetFactorAsset_Tennese_GA($aTenneseTotal[4]);
				$aTenneseTotalAll[5] = WorkFactors::GetFactorAsset_Tennese_PO($aTenneseTotal[5]);
				$aTenneseTotalAll[6] = WorkFactors::GetFactorAsset_Tennese_MO($aTenneseTotal[6]);
//				DbConnect::Log($aTenneseTotal[1].' / '.$aTenneseTotal[2].' / '.$aTenneseTotal[3].' / '.$aTenneseTotal[4].' / '.$aTenneseTotal[5].' / '.$aTenneseTotal[6],'debug');
				PDF_InsertHeavy($inPDF, 1, $aTenneseTotal, $aTenneseTotalAll, 'Плотность сигналов (световых и звуковых) и сообщений в среднем за 1 час работы, ед.', '76-175');
				PDF_InsertHeavy($inPDF, 2, $aTenneseTotal, $aTenneseTotalAll, 'Число производственных объектов одновременного наблюдения, ед.', '6-10');
				PDF_InsertHeavy($inPDF, 3, $aTenneseTotal, $aTenneseTotalAll, 'Работа с оптическими приборами, % времени смены', '26-50');
				PDF_InsertHeavy($inPDF, 4, $aTenneseTotal, $aTenneseTotalAll, 'Нагрузка на голосовой аппарат (суммарное количество часов, наговариваемое в неделю), час.', 'до 20');
				PDF_InsertHeavy($inPDF, 5, $aTenneseTotal, $aTenneseTotalAll, 'Число элементов (приемов), необходимых для реализации простого задания или многократно повторяющихся операций, ед.', '9-6');
				PDF_InsertHeavy($inPDF, 6, $aTenneseTotal, $aTenneseTotalAll, 'Монотонность производственной обстановки (время пассивного наблюдения за ходом технологического процесса в% от времени смены), час.', '76-80');
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
	$inPDF->MultiCell	(167,$h,'2',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'3',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'4',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,'5',1,'C',1,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertHeavyS($inPDF, $sName, $sVar, $sPdu, $sAsset)
{	
	$rowcount = max($inPDF->getNumLines($sName, 167),$inPDF->getNumLines($sVar, 30),$inPDF->getNumLines($sPdu, 30),$inPDF->getNumLines(StringWork::iToClassNameLite($sAsset), 30));
	$h = $rowcount*4.5;
	$inPDF->MultiCell	(10,$h,'',1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(167,$h,$sName,1,'L',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sPdu,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,$sVar,1,'C',0,0,'','',1,0,0,1,$h,'M');
	$inPDF->MultiCell	(30,$h,StringWork::iToClassNameLite($sAsset),1,'C',0,1,'','',1,0,0,1,$h,'M');
}

function PDF_InsertHeavy($inPDF, $iParam, $aTeneseVar ,$aTeneseAsset, $sName, $sPdu)
{
	if($aTeneseVar[$iParam] > -1) 
	{
		$num_pages = $inPDF->getNumPages();
		$inPDF->startTransaction();		
		PDF_InsertHeavyS($inPDF, $sName, $aTeneseVar[$iParam], $sPdu, $aTeneseAsset[$iParam]);
		
		if($num_pages < $inPDF->getNumPages())
		{
			$inPDF->rollbackTransaction(true);
			$inPDF->AddPage();
			//Вставка заголовка	
			PDF_InsertHeaderRmList($inPDF);
			PDF_InsertHeavyS($inPDF, $sName, $aTeneseVar[$iParam], $sPdu, $aTeneseAsset[$iParam]);
		}
		else
		{
			$inPDF->commitTransaction();
		}
	}
}
?>

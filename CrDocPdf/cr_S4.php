<?
	ini_set('memory_limit','64M');
	//Базовые данные
	//Рабочего места
	$sql = "SELECT * FROM `Arm_workplace` WHERE `id` = ".$target.";";
	$vResultRm = DbConnect::GetSqlRow($sql);
	$sql = "SELECT * FROM `Arm_group` WHERE `id` = ".$vResultRm[idGroup].";";
	$vResultOrg = DbConnect::GetSqlRow($sql);
	
	//Значение группы для формируемого документа
	$pdf->tmpOrgName = '<br>'.$vResultOrg[sFullName];
	$pdf->tmpDocType = 'Протокол оценки эффективности СИЗ на рабочем месте № '.$vResultRm[iNumber];

	$sDocName = '0.5_'.$vResultRm[iNumber].'_SIZ_Protocol.pdf';
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');

	//Наименование подразделения
	$sql = "SELECT `sName` FROM `Arm_workplace` WHERE `id` = ".$vResultRm[idParent].";";
	$vResultParent = DbConnect::GetSqlRow($sql);
	
	//Переработка выводов
	if($vResultRm[iSIZEffect] == 1) $vResultRm[iSIZEffect] ='Положительная.'; else $vResultRm[iSIZEffect] ='Отрицательная.';
	if($vResultRm[iSIZOFact] == 1) $vResultRm[iSIZOFact] ='Рабочее место соответствует требованиям обеспеченности работника СИЗ.'; else $vResultRm[iSIZOFact] ='Рабочее место не соответствует требованиям обеспеченности работника СИЗ.';
//	if($vResultRm[iSIZOProtect] == 1) $vResultRm[iSIZOProtect] ='Рабочее место защищено СИЗ.'; else $vResultRm[iSIZOProtect] ='Рабочее место не защищено СИЗ.';
//	if($vResultRm[iSIZOEffect] == 1) $vResultRm[iSIZOEffect] ='На рабочем месте эффективно используются СИЗ.'; else $vResultRm[iSIZOEffect] ='На рабочем месте не эффективно используются СИЗ.';
	
	switch ($vResultRm[iSIZOProtect])
	{
		case 1:
			$vResultRm[iSIZOProtect] = 'Рабочее место защищено СИЗ.';
		break;
		case 2:
			$vResultRm[iSIZOProtect] = 'Оценка защищенности СИЗ на рабочем месте не проводилась.';
			$vResultRm[iSIZEffect] = 'Оценка эффективности выданных работнику СИЗ не проводилась.';
		break;
		default:
			$vResultRm[iSIZOProtect] = 'Рабочее место не защищено СИЗ.';
		break;
	}
	switch ($vResultRm[iSIZOEffect])
	{
		case 1:
			$vResultRm[iSIZOEffect] = 'На рабочем месте эффективно используются СИЗ.';
		break;
		case 2:
			$vResultRm[iSIZOEffect] = 'Оценка эффективности СИЗ на рабочем месте не проводилась.';
			$vResultRm[iSIZEffect] = 'Оценка эффективности выданных работнику СИЗ не проводилась.';
		break;
		default:
			$vResultRm[iSIZOEffect] = 'На рабочем месте не эффективно используются СИЗ.';
		break;
	}
	
	//Индекс А
	if (strlen($vResultRm[sNumAnalog]) > 0)
	{
		$vResultRm[iNumber] = $vResultRm[iNumber].'А';
	}
	
	//Шапка
	$html ='
<h2 align="center"><font face="calibrib">ПРОТОКОЛ<br />
оценки эффективности средств индивидуальной защиты на рабочем месте<br />
'.$vResultRm[sName].' № '.$vResultRm[iNumber].'</font></h2>

1. Дата проведения оценки: <font face="calibrib">'.StringWork::StrToDateFormatFull($vResultRm[dSizDate]).'</font><br /><br />
2. Основание для выдачи работнику средств индивидуальной защиты (СИЗ): <font face="calibrib">'.StringWork::CheckNullStrFull($vResultRm[sSIZbase]).'</font><br /><br />
3. Результат оценки обеспеченности работников СИЗ:<br />

<table width="100%" border="0.5" cellspacing="0" cellpadding="2" bordercolor="#000">
<tr>
<td width="4%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">№ п/п</font></h5></td>
<td width="40%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Перечень СИЗ, положенных работнику согласно действующим требованиям</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наличие СИЗ у работника<br />(есть, нет)</font></h5></td>
<td width="40%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наличие сертификата или декларации соответсвия<br />(номер и срок действия)</font></h5></td>
</tr>
<tr>
<td width="4%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">1</font></h5></td>
<td width="40%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">2</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">3</font></h5></td>
<td width="40%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">4</font></h5></td>
</tr>';

	//Перечнеь СИЗ
	$sql = "SELECT * FROM `Arm_Siz` WHERE `rmId` = ".$target." ORDER BY `SizName`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpNum = 1;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if($vRow[Fact] == 1) $vRow[Fact] ='Есть.'; else $vRow[Fact] ='Нет.';
			$html .='<tr nobr="true">
			<td width="4%" align="center">'.$tmpNum.'</td>
			<td width="40%" align="left">'.StringWork::CheckNullStrFull($vRow[SizName]).'</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrFull($vRow[Fact]).'</td>
			<td width="40%" align="center">'.StringWork::CheckNullStrFull($vRow[Sert]).'</td>
			</tr>';
			$tmpNum++;
		}
	}

$html .='</table><br /><br />

4. Наличие заполненной в установленном порядке личной карточки учета СИЗ: <font face="calibrib">'.StringWork::iToCompString($vResultRm[iSIZCard]).'</font><br /><br />

5.Результат оценки защищенности работника СИЗ:<br />

<table width="100%" border="0.5" cellspacing="0" cellpadding="2" bordercolor="#000">
<tr>
<td width="50%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наименование вредного и (или) опасного фактора производственной среды и трудового процесса</font></h5></td>
<td width="50%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наименование имеющегося СИЗ, обеспечивающего защиту</font></h5></td>
</tr>
<tr>
<td width="50%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">1</font></h5></td>
<td width="50%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">2</font></h5></td>
</tr>';

	//Перечнеь СИЗ
	$sql = "SELECT * FROM `Arm_Siz` WHERE `rmId` = ".$target." AND `protectFactor` NOT LIKE '';";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpNum = 1;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if($vRow[Fact] == 1) $vRow[Fact] ='Есть.'; else $vRow[Fact] ='Нет.';
			$html .='<tr nobr="true">
			<td width="50%" align="left">'.StringWork::CheckNullStrFull($vRow[protectFactor]).'</td>
			<td width="50%" align="left">'.StringWork::CheckNullStrFull($vRow[SizName]).'</td>
			</tr>';
			$tmpNum++;
		}
	}
	else
	{
		$html .='<tr nobr="true">
		<td width="50%" align="center">'.StringWork::CheckNullStrFull('').'</td>
		<td width="50%" align="center">'.StringWork::CheckNullStrFull('').'</td>
		</tr>';
	}

$html .='</table><br /><br />

6. Результаты оценки эффективности выданных работнику СИЗ: <font face="calibrib">'.$vResultRm[iSIZEffect].'</font><br /><br />

7. Итоговая оценка:<br />

а) по обеспеченности работника СИЗ:<br />
<font face="calibrib">'.$vResultRm[iSIZOFact].'</font><br />
б) по защищенности работника СИЗ:<br />
<font face="calibrib">'.$vResultRm[iSIZOProtect].'</font><br />
в) по оценке эффективности выданных работнику СИЗ:<br />
<font face="calibrib">'.$vResultRm[iSIZOEffect].'</font><br />



<br /><br />
	';	
	
	$pdf->writeHTML($html, true, false, true, false, '');
	
	//Коммисия!
	PDF_insert_Podpis($pdf, $vResultRm[idGroup]);	
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');
	$html ='';
?>

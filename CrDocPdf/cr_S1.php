<?
	ini_set('memory_limit','64M');
	$sDocName = '0.2_SOUT_OrgInfo.pdf';

	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$pdf->tmpOrgName = '<br>'.$agroup[sFullName];
	$pdf->tmpDocType = 'Сведения об организации, проводящей СОУТ';
	
	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$target.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);
	
	//Даты измерений
	$sql = "SELECT DISTINCT DATE(`dtControl`) AS `DC` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor`) LEFT JOIN `Arm_workplace` ON (`Arm_workplace`.`id` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_workplace`.`idGroup` = ".$target." ORDER BY `dtControl`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	$sDateIzm = '';
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen($sDateIzm) > 0) $sDateIzm .= '<br />';
			$sDateIzm .= StringWork::StrToDateFormatLite($vRow[DC]);
		}
	}
	
	//Телефон, адрес, email.
	
	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><h2><font face="calibrib">Сведения об организации,<br />проводящей специальную оценку условий труда</font></h2></td>
  </tr>
  <tr>
    <td align="left">&nbsp;</td>
  </tr>
  <tr>
    <td align="left">1. <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid)).'</font><br /><br />
      2. Место нахождения и осуществления деятельности: <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgPlace',$sSOUTORGid)).'</font>, контактный телефон: <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgPhone',$sSOUTORGid)).'</font>, адрес электронной почты: <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgAdress',$sSOUTORGid)).'</font>.<br /><br />
      3. Номер  в реестре организаций, проводящих специальную оценку условий труда (оказывающих  услуги в области охраны труда): <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgRegNum',$sSOUTORGid)).'</font><br /><br />
      4. Дата внесения в реестр организаций, проводящих специальную оценку условий труда (оказывающих услуги в области  охраны труда): <font face="calibrib">'.StringWork::CheckNullStrFull(StringWork::StrToDateFormatFull(UserControl::GetUserFieldValueFromId('sOrgDate',$sSOUTORGid))).'</font><br /><br />
5. ИНН организации: <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgInn',$sSOUTORGid)).'</font><br /><br />
6. ОГРН  организации: <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgOgrn',$sSOUTORGid)).'</font><br /><br />
7. Сведения об испытательной лаборатории  (центре) организации:</td>
</tr>
<tr>
<td align="left"><table width="100%" border="0.5" cellspacing="0" cellpadding="2" bordercolor="#000">
<tr>
<td width="35%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Регистрационный номер аттестата аккредитации организации</font></h5></td>
<td width="35%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Дата выдачи аттестата аккредитации организации</font></h5></td>
<td width="30%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Дата истечения срока действия аттестата  аккредитации организации</font></h5></td>
</tr>
<tr>
<td width="35%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">1</font></h5></td>
<td width="35%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">2</font></h5></td>
<td width="30%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">3</font></h5></td>
</tr>';

	$sql = "SELECT * FROM `Arm_groupAcredit` WHERE `idGroup` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$html .='<tr nobr="true">
			<td width="35%" align="center">'.$vRow[sName].'</td>
			<td width="35%" align="center">'.StringWork::StrToDateFormatLite($vRow[dDateCreate]).'</td>
			<td width="30%" align="center">'.StringWork::StrToDateFormatLite($vRow[dDateFinish]).'</td>
			</tr>';			
		}
	}
	else
	{
		$html .='<tr nobr="true">
		<td width="35%" align="center">&#8212;</td>
		<td width="35%" align="center">&#8212;</td>
		<td width="30%" align="center">&#8212;</td>
		</tr>';
	}

	$html .='</table>
</td>
</tr>
<tr>
<td align="left"><br /><br />8. Сведения  об экспертах и иных работниках организации, участвовавших в проведении  специальной оценки условий труда:</td>
</tr>
<tr>
<td align="left">
<table width="100%" border="0.5" cellspacing="0" cellpadding="2" bordercolor="#000">
<tr>
<td width="4%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">№ п/п</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Дата проведения измерений</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Ф.И.О. эксперта (работника)</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Должность</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Сведения о сертификате эксперта на право выполнения  работ по специальной оценке условий труда, номер</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Сведения о сертификате эксперта на право выполнения  работ по специальной оценке условий труда, дата выдачи</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Регистрационный номер в реестре экспертов организаций,  проводящих специальную оценку условий труда</font></h5></td>
</tr>
<tr>
<td width="4%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">1</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">2</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">3</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">4</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">5</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">6</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">7</font></h5></td>
</tr>';

	$sql = "SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);
	$aStuff = array();
	if (mysql_num_rows($vResult) > 0)
	{
		//Счетчик
		$iNum = 1;
		while($vRow = mysql_fetch_array($vResult))
		{
			if(!in_array($vRow[sName], $aStuff))
			{
			array_push($aStuff, $vRow[sName]);
			
			if(strlen(trim($vRow[sSertNum])) == 0)
			{$vRow[dSertDate] = '';} else {$vRow[dSertDate] = StringWork::StrToDateFormatLite($vRow[dSertDate]);}
			
			$html .='<tr nobr="true">
			<td width="4%" align="center">'.$iNum.'.</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrLite($sDateIzm).'</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sName]).'</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sPost]).'</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sSertNum]).'</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[dSertDate]).'</td>
			<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sReestrNum]).'</td>
			</tr>';
			$iNum++;	
		}
		}
	}
	else
	{
		$html .='<tr nobr="true">
<td width="4%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
</tr>';
	}

	$html .='</table>
</td>
</tr>
<tr>
<td align="left"><br /><br />9.  Сведения о средствах измерений испытательной лаборатории (центра) организации,  использовавшихся при проведении специальной оценки условий труда:</td>
</tr>
<tr>
<td align="left"><table width="100%" border="0.5" cellspacing="0" cellpadding="2" bordercolor="#000">
<tr>
<td width="4%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">№ п/п</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Дата проведения измерений</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наименование вредного и (или) опасного фактора  производственной среды и трудового процесса</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Наименование средства измерений</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Регистрационный номер в Государственном реестре  средств измерений</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Заводской номер средства измерений</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">Дата окончания срока поверки средства измерений</font></h5></td>
</tr>
<tr>
<td width="4%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">1</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">2</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">3</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">4</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">5</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">6</font></h5></td>
<td width="16%" align="center" valign="middle" bgcolor="#d9d9d9"><h5><font face="calibrib">7</font></h5></td>
</tr>';

	$sql = "SELECT * FROM `Arm_groupDevices` WHERE `idGroup` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		//Счетчик
		$iNum = 1;
		while($vRow = mysql_fetch_array($vResult))
		{
			$html .='<tr>
<td width="4%" align="center">'.$iNum.'.</td>
<td width="16%" align="center">'.StringWork::CheckNullStrLite($sDateIzm).'</td>
<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sFactName]).'</td>
<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sName]).'</td>
<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sReestrNum]).'</td>
<td width="16%" align="center">'.StringWork::CheckNullStrLite($vRow[sFactoryNum]).'</td>
<td width="16%" align="center">'.StringWork::CheckNullStrLite(StringWork::StrToDateFormatLite($vRow[dCheckDate])).'</td>
</tr>';
$iNum++;	
		}
	}
	else
	{
		$html .='<tr nobr="true">
<td width="4%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
<td width="16%" align="center">&#8212;</td>
</tr>';
	}

	$html .='
</table></td>
</tr>
<tr>
<td align="left">&nbsp;</td>
</tr>
<tr>
<td align="left">

<table width="100%" border="0" cellpadding="0" cellspacing="0" nobr="true">
<tr>
<td width="40%" align="left"><p>Руководитель организации, проводящей специальную оценку условий труда</p></td>
<td width="60%" align="right" valign="bottom" style="border-bottom:#000 solid 2px; vertical-align:bottom;">'.StringWork::FullNameToInitials(UserControl::GetUserFieldValueFromId('sFirstFaceName',$sSOUTORGid)).'</td>
</tr>
<tr>
<td width="40%" align="left">&nbsp;</td>
<td width="60%" align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
</tr>
<tr>
<td width="40%" align="center" style="color:#999;font-style:italic;">М.П.</td>
<td width="60%"></td>
</tr>
</table>

</td>
</tr>
</table>
';

?>

<?
	ini_set('memory_limit','64M');
	$sDocName = '0.1_SOUT_FirsPage.pdf';
	
	$sql = "SELECT * FROM `Arm_group` WHERE `id` = ".$target.";";
	$vResult = DbConnect::GetSqlRow($sql);
	
	$pdf->tmpOrgName = '<br>'.$vResult[sFullName];
	$pdf->tmpDocType = 'Титульный лист';
	
	$html ='
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="50%" align="center">&nbsp;</td>
    <td align="center">УТВЕРЖДАЮ</td>
  </tr>
  <tr>
    <td width="50%" align="center">&nbsp;</td>
    <td align="center">Председатель комиссии по проведению<br />специальной оценки условий труда</td>
  </tr>
  <tr>
    <td width="50%" align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" align="center">&nbsp;</td>
    <td align="center">____________________ '.StringWork::FullNameToInitials($vResult[sPredsName]).'</td>
  </tr>
  <tr>
    <td width="50%" align="center">&nbsp;</td>
    <td align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, фамилия, инициалы)</font></td>
  </tr>
  <tr>
    <td width="50%" align="center">&nbsp;</td>
    <td align="center">&quot;_____&quot; ____________________ ____________ г.</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="100" align="center">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><h1><font face="calibrib">ОТЧЕТ<br />о проведении специальной оценки условий труда<br />
      в '.$vResult[sFullName].'</font></h1></td>
  </tr>
  <tr>
    <td align="center"> <h2>'.$vResult[sPlace].'<br />ИНН '.$vResult[sInn].'<br />ОГРН '.$vResult[sOgrn].'<br />ОКВЭД '.$vResult[sOkved].' </h2></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="40%" height="100" align="left">&nbsp;</td>
    <td width="60%" height="100" align="center" valign="bottom">&nbsp;</td>
  </tr>
  <tr>
    <td width="40%" align="left">Члены комиссии по проведению<br />специальной оценки условий труда: </td>
    <td width="60%" align="center" valign="bottom">&nbsp;</td>
  </tr>';


	$sql = "SELECT * FROM `Arm_comiss` WHERE `idParent` = ".$target.";";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$html .= '
			<tr>
			<td width="40%" align="left">&nbsp;</td>
			<td width="60%" align="center" valign="bottom" style="border-bottom:#000 solid 2px; vertical-align:bottom;">'.$vRow[sName].'</td>
			</tr>
			<tr>
			<td width="40%" align="center">&nbsp;</td>
			<td width="60%" align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
			</tr>
			';
		}
		$html .= '</table>';
	}
	else
	{
		$html .= '
		<tr>
		<td width="40%" align="left">&nbsp;</td>
		<td width="60%" align="center" valign="bottom" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
		</tr>
		<tr>
		<td width="40%" align="center">&nbsp;</td>
		<td width="60%" align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
		</tr>
		<tr>
		<td width="40%" align="center">&nbsp;</td>
		<td width="60%" align="center" valign="bottom" style="border-bottom:#000 solid 2px;">&nbsp;</td>
		</tr>
		<tr>
		<td width="40%" align="center">&nbsp;</td>
		<td width="60%" align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
		</tr>
		<tr>
		<td width="40%" align="center">&nbsp;</td>
		<td width="60%" align="center" valign="bottom" style="border-bottom:#000 solid 2px;">&nbsp;</td>
		</tr>
		<tr>
		<td width="40%" align="center">&nbsp;</td>
		<td width="60%" align="center" style="color:#999;"><font face="calibril" size="-2">(подпись, ФИО, дата)</font></td>
		</tr>
		</table>';		
	}
?>

<?
	ini_set('memory_limit','64M');
	$sDocName = '4.0_Prikaz_Begin.pdf';
	
	$sql = "SELECT * FROM `Arm_group` WHERE `id` = ".$target.";";
	$vResult = DbConnect::GetSqlRow($sql);
	
	//Значение группы для формируемого документа
	$pdf->tmpOrgName = '<br>'.$vResult[sFullName];
	$pdf->tmpDocType = 'Приказ о создании комиссии';

	
	$sql = "SELECT * FROM `Arm_comiss` WHERE `idParent` = ".$target.";";
	$tmpResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($tmpResult) > 0)
	{
		while($tmpRow = mysql_fetch_array($tmpResult))
		{
			$tmphtml .= '<br />'.$tmpRow[sPost].' &#8212; '.$tmpRow[sName];
		}
	}
	$pdf->SetFont($fontname, 'BI', 12, '', 'false');
	
	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><h1>Приказ о создании постоянно действующей комиссии<br />по проведению специальной оценки условий труда</h1><h2>'.$vResult[sFullName].'</h2></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%" height="25" align="left">&nbsp;</td>
    <td width="50%" height="25" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td width="50%" align="left">№ ____________</td>
    <td width="50%" align="right">&quot;_____&quot; ___________ ______ г.</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="25">&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:justify;">В целях проведения работ по сертификации производственных объектов на соответствие требованиям по охране труда, в соответствие с Приказом Министерства труда и социального развития России № 33н от 24 января 2014 г. «Об утверждении Методики проведения специальной оценки условий труда, Классификатора вредных и (или) опасных производственных факторов, формы отчета о проведении специальной оценки условий труда и инструкции по ее заполнению».</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><font face="calibrib">Приказываю:</font></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><p style="text-align:justify;">1. Приступить к специальной оценке условий труда в  организации.</p>
      <p style="text-align:justify;">2. Для организации и проведения специальной оценке  условий труда создать постоянно действующую комиссию в следующем составе: </p>
	  
      <blockquote><font face="calibrii"><p>Председатель комиссии:</p><p>'.$vResult[sPredsPost].' &#8212; '.$vResult[sPredsName].'</p>
      <p>Члены комиссии:</p><p>'.$tmphtml.'</p></font></blockquote>
	  
      <p  style="text-align:justify;">3. Комиссии по  специальной оценке условий труда обеспечить методическое руково­дство и  контроль за проведением работ по специальной оценке условий труда, в полном  соответствии с методикой проведения специальной оценки условий труда.</p>
	<p>4. Контроль за выполнением приказа оставляю за собой.</p></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td align="right">&nbsp;</td>
  </tr>
  <tr>
    <td>'.$vResult[sPostDirector].'</td>
    <td align="right">'.$vResult[sNameDirector].'</td>
  </tr>
</table>
';
?>

<?
	ini_set('memory_limit','64M');
	$sDocName = '4.1_Declaration.pdf';

	$sql = "SELECT * FROM `Arm_group` WHERE `id` = ".$target.";";
	$vResult = DbConnect::GetSqlRow($sql);

	//Запрос рабочих мест с неидентифицированными факторами
	$sql = "SELECT `id`,`iNumber`, `sName`, `iCount` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `iATotal` <= 2 AND `idParent` > -1;";
	$vRmResult = DbConnect::GetSqlQuery($sql);
	$RMlist = "";
	if (mysql_num_rows($vRmResult) > 0)
	{

		while($vRow = mysql_fetch_array($vRmResult))
		{/*
			$sql2 = "SELECT id FROM Arm_rmPointsRm WHERE idRm = ".$vRow[id].";";
			$vRmPResult = DbConnect::GetSqlQuery($sql2);
			if (mysql_num_rows($vRmPResult) == 0)
			{*/
				if(strlen($RMlist) > 0) $RMlist .= ',<br />';
				$RMlist .= $vRow[iNumber].'. '.$vRow[sName].' - '.$vRow[iCount].' '.morph($vRow[iCount], 'занятый работник', 'занятых работника', 'занятых работников');
			//}
		}
	}

	//Значение группы для формируемого документа
	$pdf->tmpOrgName = '<br>'.$vResult[sFullName];
	$pdf->tmpDocType = 'Декларация соответствия условий труда';

	$pdf->SetFont($fontname, 'BI', 12, '', 'false');

	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><h1>Декларация соответствия условий труда государственным нормативным требованиям охраны труда</h1></td>
  </tr>
</table>';



	$html .='<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="25">&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:justify;">
	'.$vResult[sFullName].', находящийся и осуществляющий деятельность по адресу
	'.$vResult[sPlace].', ИНН
	'.$vResult[sInn].', ОГРН
	'.$vResult[sOgrn].' заявляет, что на '.morph(mysql_num_rows($vRmResult), 'рабочем месте', 'рабочих местах', 'рабочих местах').':<br /><br />
	<font face="calibrib">'.StringWork::CheckNullStrFull($RMlist).'</font><br /><br />
	не выявлены вредные и (или) опасные производственные факторы, условия труда соответствуют государственным нормативным требованиям охраны труда.
	</td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:justify;">Декларация подана на основании: <font face="calibrib">заключения эксперта проводившего специальную оценку условий труда № '.StringWork::CheckNullStrFull($vResult[sExpEndDoc]).' от '.StringWork::StrToDateFormatFull($vResult[sExpEndDate]).'</font></td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
  </tr>
  <tr>
    <td style="text-align:justify;">Специальная оценка условий труда проведена: <font face="calibrib">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgName',$vResult[idParent])).', номер  в реестре организаций, проводящих специальную оценку условий труда (оказывающих  услуги в области охраны труда): '.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgRegNum',$vResult[idParent])).'</font>.</td>
  </tr>
  <tr>
    <td height="25">&nbsp;</td>
  </tr>
  <tr>
    <td>Дата подачи декларации «___» ________________ 20____г.</td>
  </tr>
  <tr nobr="true">
    <td>
<table width="100%" border="0" cellpadding="0" cellspacing="5mm">
  <tr>
    <td align="left" valign="middle" width="35mm">М.П.</td>
    <td width="60mm">
    <table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><font face="calibril" size="-2">(подпись)</font></td>
      </tr>
    </table>
    </td>

    <td width="60mm"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">'.StringWork::FullNameToInitials($vResult[sNameDirector]).'</td>
      </tr>
      <tr>
        <td align="center"><font face="calibril" size="-2">(инициалы, фамилия)</font></td>
      </tr>
    </table></td>
  </tr>
</table>
	</td>
  </tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" nobr="true">
  <tr><td>

<p>Сведения о регистрации декларации</p>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><font face="calibril" size="-2">(наименование территориального органа Федеральной  службы по труду и занятости,<br />зарегистрировавшего декларацию)</font></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="10">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
        </tr>
      <tr>
        <td align="center"><font face="calibril" size="-2">(дата регистрации)</font></td>
        </tr>
    </table></td>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><font face="calibril" size="-2">(регистрационный номер)</font></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="0" cellspacing="5mm">
  <tr>
    <td align="left" valign="middle" width="35mm">М.П.</td>
    <td width="60mm"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><font face="calibril" size="-2">(подпись)</font></td>
      </tr>
    </table></td>
    <td width="60mm"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" style="border-bottom:#000 solid 2px; vertical-align:bottom;">&nbsp;</td>
      </tr>
      <tr>
        <td align="center"><font face="calibril" size="-2">(инициалы, фамилия должностного лица территориального органа Федеральной службы по труду и занятости, зарегистрировавшего декларацию)</font></td>
      </tr>
    </table></td>
  </tr>
</table>

	</td>
  </tr>
</table>
';
?>

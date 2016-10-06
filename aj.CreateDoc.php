<?
include_once "LowLevel/dbConnect.php";
include_once "LowLevel/dataCrypt.php";
include_once "UserControl/userControl.php";
include_once "CrDocPdf/tcpdf/tcpdf.php";
include_once "MainWork/GroupWork.php";
include_once "MainWork/WorkFactors.php";
include_once "Util/String.php";

function PDF_replace_null_micro($inValue)
{
	if (strlen($inValue) == 0) $inValue = '-';
	return $inValue;
}

if(isset($_POST[iTarget]) && isset($_POST[iDocType]) && isset($_POST[sPath]))
{
	$target = $_POST[iTarget];

	class MYPDF extends TCPDF {

		public $tmpDocType = 'Карта специальной оценки';
		public $tmpOrgName = '<br>ООО "Лютик"';
		public function Footer() {
		$this->SetTextColor(102, 102, 102);
		$this->SetLineStyle(array('width' => 0.85 / $this->k, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(153, 153, 153)));
		$iNumPage = (trim($this->PageNo())*1);
		$iNumPageAll = (trim($this->getAliasNbPages()));
		$tmpcolon = '
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="middle">АРМ 2009 | Специальная оценка условий труда | www.arm2009.ru'.$this->tmpOrgName.'</td>
    <td align="right" valign="middle">'.$this->tmpDocType.'<br>Страница '.$iNumPage.'</td>
  </tr>
</table>
		';

		$this->MultiCell(0, 10, $tmpcolon, 0, 'L', 0, 1, '','',0,0,1,1,10, 'M');
//		$this->MultiCell(0, 10, '.'<br>Hi', 0, 'R', 0, 1, '','',0,0,1,1,10, 'M');
		}
	}


	//Готовим разметку - книжная
	switch($_POST[iDocType])
	{
		case 'S2':
		case 'E2':
		case 'S5':
		case 'S6':
		case 'P0':
		case 'P1':
		case 'P2':
		case 'P3':
		case 'P4':
		case 'P5':
			$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetMargins(15, 15, 15, false); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)

		break;
		case 'P6':
			$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetMargins(15, 15, 15, false); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)

		break;
		default:
			$pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
			$pdf->SetMargins(20, 15, 20, false); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
		break;
	}
	$pdf->SetAuthor('АРМ 2009 | Специальная оценка условий труда | www.arm2009.ru');
	$pdf->SetTitle('Оформление результатов специальной оценки условий труда.');
	$pdf->SetSubject('Оформление результатов специальной оценки условий труда.');
	$fontname = $pdf->addTTFfont('Font/calibri.ttf', 'UTF-8', 'UTF-8', 32, '', 3, 1);
	$fontname_italian = $pdf->addTTFfont('Font/calibrii.ttf', 'UTF-8', 'UTF-8', 32, '', 3, 1);
	$fontname_light = $pdf->addTTFfont('Font/calibril.ttf', 'UTF-8', 'UTF-8', 32, '', 3, 1);
	$fontname_bold = $pdf->addTTFfont('Font/calibrib.ttf', 'UTF-8', 'UTF-8', 32, '', 3, 1);

	$pdf->setPrintHeader(false);
	$pdf->setHeaderFont(Array($fontname, '', 10));
	$pdf->setFooterFont(Array($fontname, '', 8));
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');
	$pdf->SetFooterMargin(15);
	$pdf->AddPage(); // создаем первую страницу, на которой будет содержимое

	//Вносим содержимое
	include_once "CrDocPdf/cr_".$_POST[iDocType].".php";
/*	switch($_POST[iDocType])
	{
		case 'S0':
			include_once "CrDocPdf/cr_S0.php";
			$pdf->setPrintFooter(false);
		break;
		case 'S1':
			include_once "CrDocPdf/cr_S1.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'S2':
			include_once "CrDocPdf/cr_S2.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'S3':
			include_once "CrDocPdf/cr_S3.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'S4':
			include_once "CrDocPdf/cr_S4.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'S5':
			include_once "CrDocPdf/cr_S5.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'S6':
			include_once "CrDocPdf/cr_S6.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'R0':
			include_once "CrDocPdf/cr_R0.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'P0':
			include_once "CrDocPdf/cr_P0.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'P1':
			include_once "CrDocPdf/cr_P1.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'P2':
			include_once "CrDocPdf/cr_P2.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'P3':
			include_once "CrDocPdf/cr_P3.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'P4':
			include_once "CrDocPdf/cr_P4.php";
			//$pdf->setPrintFooter(false);
		break;
		case 'P5':
			include_once "CrDocPdf/cr_P5.php";
			//$pdf->setPrintFooter(false);
		break;
	}
*/

	//Обрабатываем результаты
	if(strlen($html) > 0)
	$pdf->writeHTML($html, true, false, true, false, '');
	$filename = 'DownloadDoc/'.$_POST[sPath].'/'.$sDocName;
	$pdf->Output($filename, 'F');
	echo($filename);
/*
I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
D: send to the browser and force a file download with the name given by name.
F: save to a local server file with the name given by name.
S: return the document as a string (name is ignored).
FI: equivalent to F + I option
FD: equivalent to F + D option
E: return the document as base64 mime multi-part email attachment (RFC 2045)
*/
}

function PDF_insert_Podpis($inPDF, $idWorkGroup)
{
	//Председатель комиссии
	$sql = "SELECT * FROM `Arm_group` WHERE `id` = ".$idWorkGroup.";";
	$vResult = DbConnect::GetSqlRow($sql);

	$html = '
	<table nobr="true"><tr><td>
	<p><font face="calibrib" size="+2">Председатель комиссии по проведению специальной оценки  условий труда:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">
	<tr>
	<td valign="top" style="border-bottom:#000 solid 2px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">'.$vResult[sPredsPost].'</td>
    <td width="5%">&nbsp;</td>
    <td>'.StringWork::FullNameToInitials($vResult[sPredsName]).'</td>
    <td width="5%">&nbsp;</td>
  </tr>
</table>
	</td>
	</tr>
	<tr>
	<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td>
	</tr>
	</table>
	';

	//Состав комиссии
	$html .= '
	<p><font face="calibrib" size="+2">Члены комиссии по проведению специальной оценки условий труда:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">';

	$sql = "SELECT * FROM `Arm_comiss` WHERE `idParent` = ".$idWorkGroup.";";
	$vResult = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$html .= '
			<tr>
			<td valign="top" style="border-bottom:#000 solid 2px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">'.$vRow[sPost].'</td>
    <td width="5%">&nbsp;</td>
    <td>'.StringWork::FullNameToInitials($vRow[sName]).'</td>
    <td width="5%">&nbsp;</td>
  </tr>
</table>
			</td>
			</tr>
			<tr>
			<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td>
			</tr>
			';
		}
	}
	else
	{
			$html .= '
			<tr>
			<td valign="top" style="border-bottom:#000 solid 2px;"></td>
			</tr>
			<tr>
			<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td>
			</tr>
			';
	}

	$html .= '</table>';

	$sql = "SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = ".$idWorkGroup." AND `bExpert` = 1;";
	$vResult = DbConnect::GetSqlQuery($sql);

	if(mysql_num_rows($vResult) > 1) $sTmpI = 'ы';

	$html .= '
	<p><font face="calibrib" size="+2">Эксперт'.$sTmpI.' организации, проводившей специальную оценку условий труда:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">';

	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen(trim($vRow[sReestrNum])) == 0) $vRow[sReestrNum] = $vRow[sPost]; else $vRow[sReestrNum] = 'В реестре экспертов за № '.$vRow[sReestrNum];
			$html .= '
				<tr>
				<td valign="top" style="border-bottom:#000 solid 2px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">'.$vRow[sReestrNum].'</td>
    <td width="5%">&nbsp;</td>
    <td>'.StringWork::FullNameToInitials($vRow[sName]).'</td>
    <td width="5%">&nbsp;</td>
  </tr>
</table>
				</td>
				</tr>
				<tr>
				<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(№ в реестре экспертов, подпись, ФИО, дата)</font></td>
				</tr>
			';
		}
	}
	else
	{
			$html .= '
				<tr>
				<td valign="top" style="border-bottom:#000 solid 2px;">&nbsp;</td>
				</tr>
				<tr>
				<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(№ в реестре экспертов, подпись, ФИО, дата)</font></td>
				</tr>
			';
	}

	$html .= '
	</table></td></tr></table>
	';
	$inPDF->writeHTML($html, true, false, true, false, '');
}

function PDF_insert_Podpis_Protocol($inPDF, $idWorkGroup)
{
	//Измерения провел
	$html = '<table nobr="true"><tr><td>';

	$sql = "SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = ".$idWorkGroup." AND `bExpert` = 0;";
	$vResult = DbConnect::GetSqlQuery($sql);

	if(mysql_num_rows($vResult) > 1) $sTmpI = 'ы';

	$html .= '
	<p><font face="calibrib" size="+2">Исследования, измерения провел:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">';

	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			$html .= '
				<tr>
				<td valign="top" style="border-bottom:#000 solid 2px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">'.$vRow[sPost].'</td>
    <td width="5%">&nbsp;</td>
    <td>'.StringWork::FullNameToInitials($vRow[sName]).'</td>
    <td width="5%">&nbsp;</td>
  </tr>
</table>
				</td>
				</tr>
				<tr>
				<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td>
				</tr>
			';
		}
	}
	else
	{
			$html .= '
				<tr>
				<td valign="top" style="border-bottom:#000 solid 2px;">&nbsp;</td>
				</tr>
				<tr>
				<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td>
				</tr>
			';
	}
	$html .= '</table>';

	$sql = "SELECT * FROM `Arm_groupStuff` WHERE `idGroup` = ".$idWorkGroup." AND `bExpert` = 1;";
	$vResult = DbConnect::GetSqlQuery($sql);

	if(mysql_num_rows($vResult) > 1) $sTmpI = 'ы';

	$html .= '
	<p><font face="calibrib" size="+2">Оценку выполнил:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">';

	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if(strlen(trim($vRow[sReestrNum])) != 0) $vRow[sReestrNum] = 'В реестре экспертов за № '.$vRow[sReestrNum];

			$html .= '
				<tr>
				<td valign="top" style="border-bottom:#000 solid 2px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%">'.$vRow[sReestrNum].'</td>
    <td width="5%">&nbsp;</td>
    <td>'.StringWork::FullNameToInitials($vRow[sName]).'</td>
    <td width="5%">&nbsp;</td>
  </tr>
</table>
				</td>
				</tr>
				<tr>
				<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(№ в реестре экспертов, подпись, ФИО, дата)</font></td>
				</tr>
			';
		}
	}
	else
	{
			$html .= '
				<tr>
				<td valign="top" style="border-bottom:#000 solid 2px;">&nbsp;</td>
				</tr>
				<tr>
				<td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(№ в реестре экспертов, подпись, ФИО, дата)</font></td>
				</tr>
			';
	}
	$html .= '</table>';

	$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$idWorkGroup.";";
	$sSOUTORGid = DbConnect::GetSqlCell($sql);

	$html .= '
	<p><font face="calibrib" size="+2">Утверждаю:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">
	<tr><td valign="top" style="border-bottom:#000 solid 2px;">';
	$html .= '
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="50%">'.StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sSecondFacePost',$sSOUTORGid)).'</td>
		<td width="5%">&nbsp;</td>
		<td>'.StringWork::CheckNullStrFull(StringWork::FullNameToInitials(UserControl::GetUserFieldValueFromId('sSecondFaceName',$sSOUTORGid))).'</td>
		<td width="5%">&nbsp;</td>
	  </tr>
	</table>
	';
	$html .= '</td></tr><tr><td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td></tr></table>';

	$html .= '
	<p><font face="calibrib" size="+2">При измерениях присутствовал:</font></p>
	<table border="0" cellspacing="0" cellpadding="2" width="150mm">';
	$html .= '
	<tr><td valign="top" style="border-bottom:#000 solid 2px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="50%">&nbsp;</td>
		<td width="5%">&nbsp;</td>
		<td>&nbsp;</td>
		<td width="5%">&nbsp;</td>
	  </tr>
	</table></td></tr><tr><td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td></tr>
	';
	$html .= '
	<tr><td valign="top" style="border-bottom:#000 solid 2px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td width="50%">&nbsp;</td>
		<td width="5%">&nbsp;</td>
		<td>&nbsp;</td>
		<td width="5%">&nbsp;</td>
	  </tr>
	</table></td></tr><tr><td align="left" valign="top" style="color:#999;"><font face="calibril" size="-2">(должность, подпись, ФИО, дата)</font></td></tr>
	';
	$html .= '</table>';


	$html .= '</td></tr></table>';
	$inPDF->writeHTML($html, true, false, true, false, '');
}
?>

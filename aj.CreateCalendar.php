<?
include_once "LowLevel/dbConnect.php";
include_once "LowLevel/dataCrypt.php";
include_once "UserControl/userControl.php";
include_once "CrDocPdf/tcpdf/tcpdf.php";
include_once "MainWork/GroupWork.php";
include_once "MainWork/WorkFactors.php";
include_once "MainWork/WorkCalc.php";
include_once "Util/String.php";

function PDF_replace_null_micro($inValue)
{
	if (strlen($inValue) == 0) $inValue = '-';
	return $inValue;
}

class MYPDF extends TCPDF {	
	
	public $tmpDocType = 'Производственный календарь';
	public $tmpOrgName = '';
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

$pdf = new MYPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(15, 15, 15, false); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)	
	
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
	
$aArray = GroupWork::FillWorkSpace();

foreach($aArray as $aArr)
{
	$aArrayIds[] = $aArr[0];				
}
$idWorkSpaces = implode(',',$aArrayIds);

$aGroups = WorkCalc::Get_Event_List($_GET['dBegin'], $_GET['dEnd'], $idWorkSpaces);

	$rowheighta = 0;
	$pdf->SetFillColor(217,217,217);
	$pdf->MultiCell(10,$rowheighta,'п/п',1,'C',1,0);
	$pdf->MultiCell(50,$rowheighta,'Дата',1,'C',1,0);
	$pdf->MultiCell(85,$rowheighta,'Тип',1,'C',1,0);
	$pdf->MultiCell(85,$rowheighta,'Содержание',1,'C',1,0);
	$pdf->MultiCell(37,$rowheighta,'Примечание',1,'C',1,1);

if (mysql_num_rows($aGroups) > 0)
{
	$num = 1;
	while($row = mysql_fetch_array($aGroups))
	{
		
		//Начало цикла вставки
		$num_pages = $pdf->getNumPages();
		$pdf->startTransaction();
		
		//Вставка содержимого
		$pdf->SetFont($fontname, 'BI', 10, '', 'false');
		if($row['dDateStart'] != $row['dDateEnd']) $row['dDateStart'] = StringWork::StrToDateFormatLite($row['dDateStart']).' - '.StringWork::StrToDateFormatLite($row['dDateEnd']); else $row['dDateStart'] = StringWork::StrToDateFormatLite($row['dDateStart']);
		$rowcount = max($pdf->getNumLines($row['sName'], 85),$pdf->getNumLines($row['sInfo'], 85));
		$rowheight = $rowcount*4.5;
		$pdf->MultiCell(10,$rowheight,$num.'.',1,'C',0,0);
		$pdf->MultiCell(50,$rowheight,$row['dDateStart'],1,'C',0,0);
		$pdf->MultiCell(85,$rowheight,$row['sName'],1,'L',0,0);
		$pdf->MultiCell(85,$rowheight,$row['sInfo'],1,'L',0,0);
		$pdf->MultiCell(37,$rowheight,'',1,'C',0,1);
		if($num_pages < $pdf->getNumPages())
		{
			$pdf->rollbackTransaction(true);
			$pdf->AddPage();
			//Вставка заголовка	
			//PDF_InsertHeaderRmList($pdf);		
			
			//Вставка содержимого
			$pdf->MultiCell(10,$rowheighta,'п/п',1,'C',1,0);
			$pdf->MultiCell(50,$rowheighta,'Дата',1,'C',1,0);
			$pdf->MultiCell(85,$rowheighta,'Тип',1,'C',1,0);
			$pdf->MultiCell(85,$rowheighta,'Содержание',1,'C',1,0);
			$pdf->MultiCell(37,$rowheighta,'Примечание',1,'C',1,1);
			$pdf->MultiCell(10,$rowheight,$num.'.',1,'C',0,0);
			$pdf->MultiCell(50,$rowheight,$row['dDateStart'],1,'C',0,0);
			$pdf->MultiCell(85,$rowheight,$row['sName'],1,'L',0,0);
			$pdf->MultiCell(85,$rowheight,$row['sInfo'],1,'L',0,0);
			$pdf->MultiCell(37,$rowheight,'',1,'C',0,1);
		}
		else
		{
			//Otherwise we are fine with this row, discard undo history.
			$pdf->commitTransaction();
		}	
		$num++;
	}
}
else
{
	$html ='Доступные события отсутствуют.';
}

	
//Обрабатываем результаты
if(strlen($html) > 0)
$pdf->writeHTML($html, true, false, true, false, '');
$filename = 'DownloadDoc/Calendar.Pdf';
$pdf->Output($filename, 'D');
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
?>
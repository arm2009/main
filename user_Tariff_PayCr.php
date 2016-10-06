<?
	ini_set('memory_limit','64M');
	require_once('LowLevel/dbConnect.php');
	require_once('LowLevel/dataCrypt.php');
	include_once('UserControl/userControl.php');
	include_once('UserControl/userTariff.php');
	require_once('Util/String.php');
	require_once 'CrDocPdf/tcpdf/tcpdf.php';

	if(isset($_GET[pay]) && !empty($_GET[pay]))
	{
		$_GET[pay] = ($_GET[pay]/1255);
		$sql = "SELECT * FROM `Arm_PayOut` WHERE `iNum` = ".$_GET[pay].";";
		$vResult = DbConnect::GetSqlRow($sql);
	}

	// создаем объект TCPDF - документ с размерами формата A4
	// ориентация - книжная
	// единицы измерения - миллиметры
	// кодировка - UTF-8
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

	$pdf->SetAuthor('ООО "Консалтинговый центр "Труд"');
	$pdf->SetTitle('Счет на оплату.');
	$pdf->SetSubject('Счет на оплату.');

	$fontname = $pdf->addTTFfont('CrDocPdf/tcpdf/fonts/utils/calibri.ttf', 'UTF-8', 'UTF-8', 32, '', 3, 1);

	$pdf->SetMargins(15, 35, 15, false); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
	$pdf->SetHeaderMargin(10);
//	$pdf->SetFooterMargin(10);
	$pdf->setPrintFooter(false);

	$pdf->SetHeaderData('testlogo.jpg', '80', '', '', array(0,153,204), array(0,153,204));

	$pdf->setHeaderFont(Array($fontname, '', 10));
	$pdf->setFooterFont(Array($fontname, '', 10));

	$pdf->SetFont($fontname, 'BI', 10, '', 'false');

	$pdf->AddPage(); // создаем первую страницу, на которой будет содержимое

	$html = '
<table width="100%" border="0" cellspacing="0" cellpadding="5"><tr><td bgcolor="#990000" style="color:#FFF;">Обратите внимание, счет действителен в течении 5 банковских дней!</td></tr></table>
<p>ООО «Консалтинговый центр «Труд», Адрес: 660032, г. Красноярск, ул. Дубенского, 4-219<br />тел.: (391) 228-73-58</p>
<p>Образец заполнения платежного поручения:</p>
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border:1px solid #666666;">
  <tr>
    <td width="65%" align="left" valign="middle">ИНН 2465093756 КПП 246501001</td>
    <td width="10%" align="left" valign="middle">&nbsp;</td>
    <td width="25%" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td width="65%" align="left" valign="middle">Получатель ООО "КЦ "Труд"</td>
    <td width="10%" align="left" valign="middle">Сч. №</td>
    <td width="25%" align="left" valign="middle">40702810430460000240</td>
  </tr>
  <tr>
    <td width="65%" rowspan="2" align="left" valign="middle">Банк получателя Филиал №5440 ВТБ24 (ПАО)</td>
    <td width="10%" align="left" valign="middle">БИК</td>
    <td width="25%" align="left" valign="middle">045004751</td>
  </tr>
  <tr>
    <td width="10%">Сч. №</td>
    <td width="25%">30101810450040000751</td>
  </tr>
</table>
<h2 align="center">СЧЕТ № '.$vResult[iUserId].'-'.$vResult[iNum].'/АРМ2009 от '.date('d.m.Y', strtotime($vResult[dtStamp])).'</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="15%" align="left" valign="top">Поставщик:</td>
    <td width="85%" align="left" valign="top">ООО «Консалтинговый центр «Труд»<br />
ИНН 2465093756, КПП 246501001<br>660032, Красноярский край, г. Красноярск, ул. Андрея  Дубенского, 4, оф. 219<br>Филиал №5440 ВТБ24 (ПАО), БИК 045004751</td>
  </tr>
  <tr>
    <td width="15%" align="left" valign="top">Покупатель:</td>
    <td width="85%" align="left" valign="top">'.$vResult[sOrgName].'<br />
ИНН '.$vResult[sInn].', КПП '.$vResult[sKpp].'<br>'.$vResult[sAdress].'<br>'.$vResult[sBank].', БИК '.$vResult[sBik].'</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="5" width="100%">
  <tr>
    <td width="5%" align="center" nowrap="nowrap" style="border:1px solid #000;">№</td>
    <td width="45%" align="center" style="border:1px solid #000;">Наименование товара</td>
    <td width="10%" align="center" style="border:1px solid #000;">Ед. изм.</td>
    <td width="10%" align="center" style="border:1px solid #000;">Кол.</td>
    <td width="15%" align="center" style="border:1px solid #000;">Цена<br />(руб.)</td>
    <td width="15%" align="center" style="border:1px solid #000;">Сумма<br />(руб.)</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" style="border:1px solid #000;">1</td>
    <td width="45%" valign="top" style="border:1px solid #000;">Предоставление услуг<br />АРМ 2009 | Специальная оценка условий труда<br />"'.UserTariff::GetTariffNameRus($vResult[sTarif]).'" - '.$vResult[iMonth].' мес.</td>
    <td width="10%" nowrap="nowrap" style="border:1px solid #000;" align="center">-</td>
    <td width="10%" nowrap="nowrap" style="border:1px solid #000;" align="center">1</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;" align="center">'.$vResult[iSum].',00</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;" align="center">'.$vResult[iSum].',00</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="45%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="10%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td colspan="2" valign="bottom" nowrap="nowrap" style="border:1px solid #000;">Итого:</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;" align="center">'.$vResult[iSum].',00</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="45%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="10%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td colspan="2" valign="bottom" nowrap="nowrap" style="border:1px solid #000;">Без налога (НДС).</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;" align="center">-</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="45%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="10%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td colspan="2" valign="bottom" nowrap="nowrap" style="border:1px solid #000;">Всего к оплате:</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;" align="center">'.$vResult[iSum].',00</td>
  </tr>
</table>
<p>Всего наименований 1, на сумму '.$vResult[iSum].',00 рублей. ('.num2str($vResult[iSum]).')</p>';
if(isset($_GET[wp]))
$html .= '<table border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="middle">Директор<br />
    (Гайдук А.И.) <br /></td>
    <td align="center" valign="middle">________________________________________________</td>
  </tr>
</table>
<table border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="middle">Главный бухгалтер<br />
    (Антонова С.Н.)<br /></td>
    <td align="center" valign="middle">________________________________________________</td>
  </tr>
</table>
';
else
$html .= '<table border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="middle">Директор<br />
    (Гайдук А.И.) <br /></td>
    <td align="center" valign="middle"><img src="CrDocPdf/tcpdf/images/AG.png" width="149" height="57" /></td>
  </tr>
</table>
<table border="0" cellpadding="5" cellspacing="0">
  <tr>
    <td valign="middle">Главный бухгалтер<br />
    (Антонова С.Н.)<br /></td>
    <td align="center" valign="middle"><img src="CrDocPdf/tcpdf/images/SNend.png" width="111" height="75" /></td>
  </tr>
</table>';

	$pdf->writeHTML($html, true, false, true, false, '');

	$filename = 'art.pdf';
	$pdf->Output($filename, 'I'); // выводим документ в браузер, заставляя его включить плагин для отображения PDF (если имеется)
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

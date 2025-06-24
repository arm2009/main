<?
	include('../kcapi/api.php');
	require_once 'tcpdf/tcpdf.php';


	if(isset($_POST[sch_contsuim]))
	{
		//Информация в систему
		$sql = "SELECT MAX(`pay_num`) FROM `ae_pay_data` WHERE `user_id` = ".ClearSqlVarInteger($_POST[sch_contsuim]).";";
		$numtmp = SQLString($sql);

		if($numtmp == null)
		{
			$numtmp = 1;
		}
		else
		{
			$numtmp++;
		}

		$date = date('d.m.Y');
		$schsum = $_POST[sch_sum];

		$sql = "SELECT * FROM `ae_users` WHERE `id` = ".ClearSqlVarInteger($_SESSION[us_id]).";";
		$result = SQLquery($sql);
		if($result->num_rows > 0)
		{
			$row = mysqli_fetch_array($result);
		}

		$sql = "INSERT INTO `ae_pay_data` (`user_id`, `pay_num`, `pay_state`, `pay_sum`, `pay_name`, `pay_adres`, `pay_inn`, `pay_kpp`, `pay_sch`, `pay_bank`, `pay_bik`, `pay_crsch`) VALUES ('".$row[id]."', '".$numtmp."', '0', '".$_POST[sch_sum]."', '".$row[workout]."', '".$row[adress]."', '".$row[inn]."', '".$row[kpp]."', '".$row[rs]."', '".$row[bank]."', '".$row[bik]."', '".$row[ks]."');";
		SQLquery($sql);
	}

	if(isset($_GET[payid]))
	{
		$sql = "SELECT * FROM `ae_pay_data` WHERE `id` = ".ClearSqlVarInteger($_GET[payid]).";";
		$result = SQLquery($sql);
		if($result->num_rows > 0)
		{
			$rowpay = mysqli_fetch_array($result);
		}

		$sql = "SELECT * FROM `ae_users` WHERE `id` = ".ClearSqlVarInteger($rowpay[user_id]).";";
		$result = SQLquery($sql);
		if($result->num_rows > 0)
		{
			$row = mysqli_fetch_array($result);
		}

		$date = date('d.m.Y', strtotime($rowpay[crtime]));
		$schsum = $rowpay[pay_sum];
		$numtmp = $rowpay[pay_num];
	}

	// создаем объект TCPDF - документ с размерами формата A4
	// ориентация - книжная
	// единицы измерения - миллиметры
	// кодировка - UTF-8
	$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

	$pdf->SetAuthor('ООО "Консалтинговый центр "Труд"');
	$pdf->SetTitle('Счет на оплату.');
	$pdf->SetSubject('Счет на оплату.');

	$fontname = $pdf->addTTFfont('tcpdf/fonts/utils/CALIBRI.TTF', 'UTF-8', 'UTF-8', 32, '', 3, 1);

	$pdf->SetMargins(15, 25, 15, 15); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)
	$pdf->SetHeaderMargin(5);
//	$pdf->SetFooterMargin(10);
	$pdf->setPrintFooter(false);

	$pdf->SetHeaderData('testlogo.jpg', '50', '', '');

	$pdf->setHeaderFont(Array($fontname, '', 10));
	$pdf->setFooterFont(Array($fontname, '', 10));

	$pdf->SetFont($fontname, 'BI', 10, '', 'false');

	$pdf->AddPage(); // создаем первую страницу, на которой будет содержимое

	$html = '
<p>ООО «Консалтинговый центр «Труд», Адрес: 660032, г. Красноярск, ул. Дубенского, 4-219<br />тел.: (391) 228-73-58<br>
Образец заполнения платежного поручения:</p>
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border:1px solid #666666;">
  <tr>
    <td width="65%" align="left" valign="middle">ИНН 2465093756 КПП 246501001</td>
    <td width="10%" align="left" valign="middle">&nbsp;</td>
    <td width="25%" align="left" valign="middle">&nbsp;</td>
  </tr>
  <tr>
    <td width="65%" align="left" valign="middle">Получатель ООО "Консалтинговый центр "Труд"</td>
    <td width="10%" align="left" valign="middle">Сч. №</td>
    <td width="25%" align="left" valign="middle">40702810900000106642</td>
  </tr>
  <tr>
    <td width="65%" rowspan="2" align="left" valign="middle">Банк получателя Красноярская дирекция ЗАО КБ «КЕДР» г. Красноярск</td>
    <td width="10%" align="left" valign="middle">БИК</td>
    <td width="25%" align="left" valign="middle">040407415</td>
  </tr>
  <tr>
    <td width="10%">Сч. №</td>
    <td width="25%">30101810300000000415</td>
  </tr>
</table>
<p>&nbsp;</p>
<h1 align="center">СЧЕТ № '.$numtmp.'/'.$row[id].' от '.$date.'</h1>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width="15%" align="left" valign="top">Поставщик:</td>
    <td width="85%" align="left" valign="top">ООО «Консалтинговый центр «Труд», ИНН 2465093756, КПП 246501001<br>660032, Красноярский край, г. Красноярск, ул. Андрея  Дубенского, 4, оф. 219<br>Красноярская дирекция ЗАО КБ «КЕДР» г. Красноярск, БИК 040407415</td>
  </tr>
  <tr>
    <td width="15%" align="left" valign="top">Покупатель:</td>
    <td width="85%" align="left" valign="top">'.$row[workout].', ИНН '.$row[inn].', КПП '.$row[kpp].'<br>'.$row[adress].'<br>'.$row[bank].', БИК '.$row[bik].'</td>
  </tr>
</table>
<table border="0" cellspacing="0" cellpadding="5" width="100%">
  <tr>
    <td width="5%" align="center" nowrap="nowrap" style="border:1px solid #000;">№</td>
    <td width="45%" align="center" style="border:1px solid #000;">Наименование товара</td>
    <td width="10%" align="center" style="border:1px solid #000;">Ед. изм.</td>
    <td width="10%" align="center" style="border:1px solid #000;">Количество</td>
    <td width="15%" align="center" style="border:1px solid #000;">Цена</td>
    <td width="15%" align="center" style="border:1px solid #000;">Сумма</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" style="border:1px solid #000;">1</td>
    <td width="45%" valign="top" style="border:1px solid #000;">Дистанционные образовательные услуги с использованием системы дистанционного образования Обучениевсем.рф (для зачисления на личный счет, № '.$row[id].').</td>
    <td width="10%" nowrap="nowrap" style="border:1px solid #000;">-</td>
    <td width="10%" nowrap="nowrap" style="border:1px solid #000;">1</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;">'.$schsum.'</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;">'.$schsum.'</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="45%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="10%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td colspan="2" valign="bottom" nowrap="nowrap" style="border:1px solid #000;">Итого:</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;">'.$schsum.'</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="45%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="10%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td colspan="2" valign="bottom" nowrap="nowrap" style="border:1px solid #000;">Без налога (НДС).</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;">-</td>
  </tr>
  <tr>
    <td width="5%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="45%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td width="10%" nowrap="nowrap" valign="bottom">&nbsp;</td>
    <td colspan="2" valign="bottom" nowrap="nowrap" style="border:1px solid #000;">Всего к оплате:</td>
    <td width="15%" nowrap="nowrap" style="border:1px solid #000;">'.$schsum.'</td>
  </tr>
</table>
<p>&nbsp;</p>
<p>Всего наименований 1, на сумму '.$schsum.' рублей. ('.num2str($schsum).')</p>
<table border="0" cellpadding="5" cellspacing="0">
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
	$pdf->writeHTML($html, true, false, true, false, '');

	$filename = GetNowFileName() .'.pdf';
	$pdf->Output($filename, 'I'); // выводим документ в браузер, заставляя его включить плагин для отображения PDF (если имеется)
?>

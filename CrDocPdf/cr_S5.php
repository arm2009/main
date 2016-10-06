<?
	ini_set('memory_limit','64M');
	$sDocName = '0.6_SOUT_Statement.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');
	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$pdf->tmpOrgName = '<br>'.$agroup[sFullName];
	$pdf->tmpDocType = 'Сводная ведомость специальной оценки условий труда';

	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><h2><font face="calibrib">Сводная ведомость результатов проведения специальной оценки условий труда</font></h2></td>
</tr>
<tr>
<td align="left">&nbsp;</td>
</tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetFillColor(217,217,217);


	//Шапка ушанка
	$pdf->MultiCell	(50,5,'Таблица 1',0,'R',0,0,'232','25',1,0,0,1,5,'M');
	$pdf->MultiCell	(50,30,'Наименование',1,'C',1,0,'15','30',1,0,0,1,30,'M');
	$pdf->MultiCell	(50,15,'Количество рабочих мест и численность работников, занятых на этих рабочих местах',1,'C',1,0,'65','30',1,0,0,1,15,'M');
	$pdf->MultiCell	(20,15,'Всего',1,'C',1,0,'65','45',1,0,0,1,15,'M');
	$pdf->MultiCell	(30,15,'в том числе на которых проведена специальная оценка условий труда',1,'C',1,0,'85','45',1,0,0,1,15,'M');
	$pdf->MultiCell	(167,10,'Количество рабочих мест и численность занятых на них работников по классам (подклассам) условий труда из числа рабочих мест, указанных в графе 3 (единиц)',1,'C',1,0,'115','30',1,0,0,1,10,'M');
	$pdf->MultiCell	(23,20,'Класс 1',1,'C',1,0,'115','40',1,0,0,1,20,'M');
	$pdf->MultiCell	(23,20,'Класс 2',1,'C',1,0,'138','40',1,0,0,1,20,'M');
	$pdf->MultiCell	(92,10,'Класс 3',1,'C',1,0,'161','40',1,0,0,1,10,'M');
	$pdf->MultiCell	(23,10,'3.1',1,'C',1,0,'161','50',1,0,0,1,10,'M');
	$pdf->MultiCell	(23,10,'3.2',1,'C',1,0,'184','50',1,0,0,1,10,'M');
	$pdf->MultiCell	(23,10,'3.3',1,'C',1,0,'207','50',1,0,0,1,10,'M');
	$pdf->MultiCell	(23,10,'3.4',1,'C',1,0,'230','50',1,0,0,1,10,'M');
	$pdf->MultiCell	(29,20,'Класс 4',1,'C',1,0,'253','40',1,0,0,1,20,'M');

	$pdf->MultiCell	(50,5,'1',1,'C',1,0,'15','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(20,5,'2',1,'C',1,0,'65','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(30,5,'3',1,'C',1,0,'85','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(23,5,'4',1,'C',1,0,'115','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(23,5,'5',1,'C',1,0,'138','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(23,5,'6',1,'C',1,0,'161','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(23,5,'7',1,'C',1,0,'184','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(23,5,'8',1,'C',1,0,'207','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(23,5,'9',1,'C',1,0,'230','60',1,0,0,1,5,'M');
	$pdf->MultiCell	(29,5,'10',1,'C',1,0,'253','60',1,0,0,1,5,'M');

	//Данные общие
	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target.";";
	$vResultCount = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 1;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount1 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 1;";
	$vResultCount1 = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 2;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount2 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 2;";
	$vResultCount2 = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 3;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount3 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 3;";
	$vResultCount3 = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 4;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount4 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 4;";
	$vResultCount4 = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 5;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount5 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 5;";
	$vResultCount5 = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 6;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount6 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 6;";
	$vResultCount6 = DbConnect::GetSqlRow($sql);

	$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$target." AND `idParent` > -1  AND `iATotal` = 7;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$tmpRMCount7 = mysql_num_rows($vResult);
	$sql = "SELECT SUM(`iCount`) AS iCount, SUM(`iCountWoman`) AS iCountWoman, SUM(`iCountYouth`) AS iCountYouth, SUM(`iCountDisabled`) AS iCountDisabled FROM `Arm_workplace` WHERE `idParent` > -1 AND `idGroup` = ".$target." AND `iATotal` = 7;";
	$vResultCount7 = DbConnect::GetSqlRow($sql);

	$y = 65;
	$h = 10;
	if($agroup['iRmTotalCount'] < $tmpRMCount) $agroup['iRmTotalCount'] = $tmpRMCount;
	$pdf->MultiCell	(50,$h,'Рабочие места (ед.)',1,'L',0,0,'15',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(20,$h,$agroup['iRmTotalCount'],1,'C',0,0,'65',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(30,$h,$tmpRMCount,1,'C',0,0,'85',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,$tmpRMCount1,1,'C',0,0,'115',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,$tmpRMCount2,1,'C',0,0,'138',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,$tmpRMCount3,1,'C',0,0,'161',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,$tmpRMCount4,1,'C',0,0,'184',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,$tmpRMCount5,1,'C',0,0,'207',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,$tmpRMCount6,1,'C',0,0,'230',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(29,$h,$tmpRMCount7,1,'C',0,0,'253',$y,1,0,0,1,$h,'M');
	$y = 75;
	$h = 10;
	if($agroup['iWorkerTotal'] < $vResultCount[iCount]) $agroup['iWorkerTotal'] = $vResultCount[iCount];
	$pdf->MultiCell	(50,$h,'Работники, занятые на рабочих местах (чел.)',1,'L',0,0,'15',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(20,$h,StringWork::NullCatchZero($agroup['iWorkerTotal']),1,'C',0,0,'65',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(30,$h,StringWork::NullCatchZero($vResultCount[iCount]),1,'C',0,0,'85',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount1[iCount]),1,'C',0,0,'115',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount2[iCount]),1,'C',0,0,'138',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount3[iCount]),1,'C',0,0,'161',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount4[iCount]),1,'C',0,0,'184',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount5[iCount]),1,'C',0,0,'207',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount6[iCount]),1,'C',0,0,'230',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(29,$h,StringWork::NullCatchZero($vResultCount7[iCount]),1,'C',0,0,'253',$y,1,0,0,1,$h,'M');
	$y = 85;
	$h = 5;
	if($agroup['iWorkerTotalWoman'] < $vResultCount[iCountWoman]) $agroup['iWorkerTotalWoman'] = $vResultCount[iCountWoman];
	$pdf->MultiCell	(50,$h,'из них женщин',1,'L',0,0,'15',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(20,$h,StringWork::NullCatchZero($agroup['iWorkerTotalWoman']),1,'C',0,0,'65',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(30,$h,StringWork::NullCatchZero($vResultCount[iCountWoman]),1,'C',0,0,'85',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount1[iCountWoman]),1,'C',0,0,'115',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount2[iCountWoman]),1,'C',0,0,'138',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount3[iCountWoman]),1,'C',0,0,'161',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount4[iCountWoman]),1,'C',0,0,'184',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount5[iCountWoman]),1,'C',0,0,'207',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount6[iCountWoman]),1,'C',0,0,'230',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(29,$h,StringWork::NullCatchZero($vResultCount7[iCountWoman]),1,'C',0,0,'253',$y,1,0,0,1,$h,'M');
	$y = 90;
	$h = 5;
	if($agroup['iWorkerTotalYang'] < $vResultCount[iCountYouth]) $agroup['iWorkerTotalYang'] = $vResultCount[iCountYouth];
	$pdf->MultiCell	(50,$h,'из них лиц в возрасте до 18 лет',1,'L',0,0,'15',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(20,$h,StringWork::NullCatchZero($agroup['iWorkerTotalYang']),1,'C',0,0,'65',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(30,$h,StringWork::NullCatchZero($vResultCount[iCountYouth]),1,'C',0,0,'85',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount1[iCountYouth]),1,'C',0,0,'115',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount2[iCountYouth]),1,'C',0,0,'138',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount3[iCountYouth]),1,'C',0,0,'161',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount4[iCountYouth]),1,'C',0,0,'184',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount5[iCountYouth]),1,'C',0,0,'207',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount6[iCountYouth]),1,'C',0,0,'230',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(29,$h,StringWork::NullCatchZero($vResultCount7[iCountYouth]),1,'C',0,0,'253',$y,1,0,0,1,$h,'M');
	$y = 95;
	$h = 5;
	if($agroup['iWorkerTotalMedical'] < $vResultCount[iCountDisabled]) $agroup['iWorkerTotalMedical'] = $vResultCount[iCountDisabled];
	$pdf->MultiCell	(50,$h,'из них инвалидов',1,'L',0,0,'15',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(20,$h,StringWork::NullCatchZero($agroup['iWorkerTotalMedical']),1,'C',0,0,'65',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(30,$h,StringWork::NullCatchZero($vResultCount[iCountDisabled]),1,'C',0,0,'85',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount1[iCountDisabled]),1,'C',0,0,'115',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount2[iCountDisabled]),1,'C',0,0,'138',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount3[iCountDisabled]),1,'C',0,0,'161',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount4[iCountDisabled]),1,'C',0,0,'184',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount5[iCountDisabled]),1,'C',0,0,'207',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(23,$h,StringWork::NullCatchZero($vResultCount6[iCountDisabled]),1,'C',0,0,'230',$y,1,0,0,1,$h,'M');
	$pdf->MultiCell	(29,$h,StringWork::NullCatchZero($vResultCount7[iCountDisabled]),1,'C',0,0,'253',$y,1,0,0,1,$h,'M');


	//Таблица 2
	$pdf->AddPage();
	$pdf->MultiCell	(50,5,'Таблица 2',0,'R',0,0,'232','15',1,0,0,1,5,'M');

	//Шапка ушанка
	$pdf->StartTransform();
	$pdf->Rotate(90, 15, 80);
	$pdf->MultiCell	(60,10,'Индивидуальный номер рабочего места',1,'L',1,1,'15','80',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,37,'Профессия / должность / специальность работника',1,'L',1,1,'15','90',1,0,0,1,37,'M');
	$pdf->StopTransform();
	$pdf->SetFont($fontname, 'BI', 6, '', 'false');
	$pdf->StartTransform();
	$pdf->Rotate(90, 62, 80);
	$pdf->MultiCell	(55,10,'Химический фактор',1,'L',1,1,'62','80',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Биологический фактор',1,'L',1,1,'62','90',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Аэрозоли преимущественно фиброгенного действия',1,'L',1,1,'62','100',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Шум',1,'L',1,1,'62','110',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Инфразвук',1,'L',1,1,'62','120',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Ультразвук воздушный',1,'L',1,1,'62','130',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Вибрация общая',1,'L',1,1,'62','140',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Вибрация локальная',1,'L',1,1,'62','150',1,0,0,1,10,'M');

	$pdf->MultiCell	(55,10,'Неионизирующие излучения',1,'L',1,1,'62','160',1,0,0,1,10,'M');
//	$pdf->MultiCell	(45,10,'Ультрафиолетовое излучение фактора неионизирующие поля и излучения',1,'L',1,1,'122','180',1,0,0,1,10,'M');
//	$pdf->MultiCell	(45,10,'Лазерное излучение фактора неионизирующие поля и излучения',1,'L',1,1,'122','190',1,0,0,1,10,'M');
	$pdf->StopTransform();

	$pdf->StartTransform();
	$pdf->Rotate(90, 152, 80);
	$pdf->MultiCell	(55,10,'Ионизирующие излучения',1,'L',1,1,'152','80',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Параметры микроклимата',1,'L',1,1,'152','90',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Параметры световой среды',1,'L',1,1,'152','100',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Тяжесть трудового процесса',1,'L',1,1,'152','110',1,0,0,1,10,'M');
	$pdf->MultiCell	(55,10,'Напряженность трудового процесса',1,'L',1,1,'152','120',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Итоговый класс (подкласс) условий труда',1,'L',1,1,'152','130',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Итоговый класс (подкласс) условий труда с учетом эффективного применения СИЗ',1,'L',1,1,'152','140',1,0,0,1,10,'M');
	$pdf->StopTransform();

	$pdf->StartTransform();
	$pdf->Rotate(90, 222, 80);
	$pdf->MultiCell	(60,10,'Повышенный размер оплаты труда (да,нет)',1,'L',1,1,'222','80',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Ежегодный дополнительный оплачиваемый отпуск (да/нет)',1,'L',1,1,'222','90',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Сокращенная продолжительность рабочего времени (да/нет)',1,'L',1,1,'222','100',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Молоко или другие равноценные пищевые продукты (да/нет)',1,'L',1,1,'222','110',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Лечебно-профилактическое питание (да/нет)',1,'L',1,1,'222','120',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,10,'Льготное пенсионное обеспечение (да/нет)',1,'L',1,1,'222','130',1,0,0,1,10,'M');
	$pdf->StopTransform();
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	$pdf->MultiCell	(140,5,'Классы (подклассы) условий труда',1,'C',1,1,'62','20',1,0,0,1,5,'M');

	$pdf->SetXY(15,80);
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');
	PDF_insert_RM($pdf, $target, $fontname, $fontname_bold);
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');

	//Date
	$sql = "SELECT MAX(`dCreateDate`) FROM `Arm_workplace` WHERE `idGroup` = '".$target."' AND `idParent` <> -1;";
	$vResultDataCreate = DbConnect::GetSqlRow($sql);

	$pdf->MultiCell	(0,5,'Дата составления: '.StringWork::StrToDateFormatFull($vResultDataCreate[0]),0,'L',0,1,'','',1,0,0,1,5,'M');
	$pdf->Ln();

	//Коммисия!
	PDF_insert_Podpis($pdf, $target);

	$pdf->SetFont($fontname, 'BI', 10, '', 'false');
	$html ='';

function PDF_insert_RM($inPDF, $idWorkGroup, $infontname, $infontnamebold)
{
	PDF_InsertHeaderRmList($inPDF);
	$sql = "SELECT * FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			if (strlen($vRow[sNumAnalog]) > 0)
			{
				$vRow[iNumber] = $vRow[iNumber].'А';
			}

			//Начало цикла вставки
			$num_pages = $inPDF->getNumPages();
            $inPDF->startTransaction();

			//Вставка содержимого
			$rowcount = $inPDF->getNumLines($vRow[sName], 37);
			$rowheight = $rowcount*3.8;
			$inPDF->MultiCell(10,$rowheight,$vRow[iNumber],1,'C',0,0);
			$inPDF->MultiCell(37,$rowheight,$vRow[sName],1,'L',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAChem]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iABio]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAAPFD]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoise]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAInfraNoise]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAUltraNoise]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroO]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroL]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoIon]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAIon]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAMicroclimat]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iALight]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAHeavy]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATennese]),1,'C',0,0);

			$inPDF->SetFont($infontnamebold, 'BI', 8, '', 'false');
			$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATotal]),1,'C',0,0);
			$inPDF->SetFont($infontname, 'BI', 8, '', 'false');
			$inPDF->MultiCell(10,$rowheight,'—',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompSurcharge]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompVacation]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompShortWorkDay]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompMilk]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompFood]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompPension]),1,'C',0,1);

			if($num_pages < $inPDF->getNumPages())
            {
				$inPDF->rollbackTransaction(true);
				$inPDF->AddPage();
				//Вставка заголовка
				PDF_InsertHeaderRmList($inPDF);

				//Вставка содержимого
				$rowcount = $inPDF->getNumLines($vRow[sName], 37);
				$rowheight = $rowcount*3.8;
				$inPDF->MultiCell(10,$rowheight,$vRow[iNumber],1,'C',0,0);
				$inPDF->MultiCell(37,$rowheight,$vRow[sName],1,'L',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAChem]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iABio]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAAPFD]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoise]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAInfraNoise]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAUltraNoise]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroO]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAVibroL]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iANoIon]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAIon]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAMicroclimat]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iALight]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iAHeavy]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATennese]),1,'C',0,0);

				$inPDF->SetFont($infontnamebold, 'BI', 8, '', 'false');
				$inPDF->MultiCell(10,$rowheight,StringWork::iToClassNameLite($vRow[iATotal]),1,'C',0,0);
				$inPDF->SetFont($infontname, 'BI', 8, '', 'false');
				$inPDF->MultiCell(10,$rowheight,'—',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompSurcharge]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompVacation]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompShortWorkDay]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompMilk]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompFood]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,StringWork::iToCompString($vRow[iCompPension]),1,'C',0,1);
			}
            else
            {
                //Otherwise we are fine with this row, discard undo history.
                $inPDF->commitTransaction();
            }
		}
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$inPDF->MultiCell(10,0,'1',1,'C',1,0);
	$inPDF->MultiCell(37,0,'2',1,'C',1,0);
	$inPDF->MultiCell(10,0,'3',1,'C',1,0);
	$inPDF->MultiCell(10,0,'4',1,'C',1,0);
	$inPDF->MultiCell(10,0,'5',1,'C',1,0);
	$inPDF->MultiCell(10,0,'6',1,'C',1,0);
	$inPDF->MultiCell(10,0,'7',1,'C',1,0);
	$inPDF->MultiCell(10,0,'8',1,'C',1,0);
	$inPDF->MultiCell(10,0,'9',1,'C',1,0);
	$inPDF->MultiCell(10,0,'10',1,'C',1,0);
	$inPDF->MultiCell(10,0,'11',1,'C',1,0);
	$inPDF->MultiCell(10,0,'12',1,'C',1,0);
	$inPDF->MultiCell(10,0,'13',1,'C',1,0);
	$inPDF->MultiCell(10,0,'14',1,'C',1,0);
	$inPDF->MultiCell(10,0,'15',1,'C',1,0);
	$inPDF->MultiCell(10,0,'16',1,'C',1,0);
	$inPDF->MultiCell(10,0,'17',1,'C',1,0);
	$inPDF->MultiCell(10,0,'18',1,'C',1,0);
	$inPDF->MultiCell(10,0,'19',1,'C',1,0);
	$inPDF->MultiCell(10,0,'20',1,'C',1,0);
	$inPDF->MultiCell(10,0,'21',1,'C',1,0);
	$inPDF->MultiCell(10,0,'22',1,'C',1,0);
	$inPDF->MultiCell(10,0,'23',1,'C',1,0);
	$inPDF->MultiCell(10,0,'24',1,'C',1,1);
}

function PDF_isFactorGroup($idPoint,$idGroupID,$rTimeHour)
{
	$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint." AND `idFactorGroup` = ".$idGroupID.";";
	$vResultP = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResultP) > 0)
	{
		return $rTimeHour .' ч.';
	}
	else
	{
		return '-';
	}
}
function PDF_isFactorId($idPoint,$idFactor,$rTimeHour)
{
	$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint." AND `idFactor` = ".$idFactor.";";
	$vResultP = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResultP) > 0)
	{
		return $rTimeHour .' ч.';
	}
	else
	{
		return '-';
	}
}
function PDF_isFactorIds($idPoint,$idFactor,$rTimeHour)
{
	$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint." AND `idFactor` IN (".$idFactor.");";
//	echo($sql);
	$vResultP = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResultP) > 0)
	{
		return $rTimeHour .' ч.';
	}
	else
	{
		return '-';
	}
}
?>

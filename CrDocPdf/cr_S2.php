<?
	ini_set('memory_limit','64M');
	$sDocName = '0.3_SOUT_RMList.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$pdf->tmpOrgName = '<br>'.$agroup[sFullName];
	$pdf->tmpDocType = 'Перечень рабочих мест на которых проводилась специальная оценка условий труда';

	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><h2><font face="calibrib">Перечень рабочих мест, на которых проводилась специальная оценка условий труда</font></h2></td>
</tr>
<tr>
<td align="left">&nbsp;</td>
</tr>
</table>
';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->SetAutoPageBreak(False);
$pdf->SetFillColor(217,217,217);


	//Шапка ушанка
	$pdf->StartTransform();
	$pdf->Rotate(90, 15, 90);
	$pdf->MultiCell	(60,10,'Индивидуальный номер рабочего места',1,'L',1,1,'15','90',1,0,0,1,10,'M');
	$pdf->MultiCell	(60,67,'Наименование рабочего места и источников вредных и (или) опасных факторов производственной среды и трудового процесса',1,'L',1,1,'15','100',1,0,0,1,60,'M');
	$pdf->MultiCell	(60,15,'Численность работников, занятых на данном рабочем месте (чел.)',1,'L',1,1,'15','167',1,0,0,1,15,'M');
	$pdf->MultiCell	(60,15,'Наличие аналогичного рабочего места (рабочих мест)',1,'L',1,1,'15','182',1,0,0,1,15,'M');
	$pdf->StopTransform();

	$pdf->SetFont($fontname, 'BI', 6, '', 'false');

	$pdf->StartTransform();
	$pdf->Rotate(90, 122, 90);
	$pdf->MultiCell	(50,10,'Химический фактор',1,'L',1,1,'122','90',1,0,0,1,10,'M');
	$pdf->MultiCell	(50,10,'Биологический фактор',1,'L',1,1,'122','100',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Аэрозоли преимущественно фиброгенного действия',1,'L',1,1,'122','110',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Шум',1,'L',1,1,'122','120',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Инфразвук',1,'L',1,1,'122','130',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Ультразвук воздушный',1,'L',1,1,'122','140',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Вибрация общая',1,'L',1,1,'122','150',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Вибрация локальная',1,'L',1,1,'122','160',1,0,0,1,10,'M');

	$pdf->MultiCell	(45,10,'Электромагнитные поля фактора неионизирующие поля и излучения',1,'L',1,1,'122','170',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Ультрафиолетовое излучение фактора неионизирующие поля и излучения',1,'L',1,1,'122','180',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Лазерное излучение фактора неионизирующие поля и излучения',1,'L',1,1,'122','190',1,0,0,1,10,'M');
	$pdf->StopTransform();

	$pdf->StartTransform();
	$pdf->Rotate(90, 232, 90);
	$pdf->MultiCell	(45,10,'Ионизирующие излучения',1,'L',1,1,'232','90',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Микроклимат',1,'L',1,1,'232','100',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Световая среда',1,'L',1,1,'232','110',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Тяжесть трудового процесса',1,'L',1,1,'232','120',1,0,0,1,10,'M');
	$pdf->MultiCell	(45,10,'Напряженность трудового процесса',1,'L',1,1,'232','130',1,0,0,1,10,'M');
	$pdf->StopTransform();

	$pdf->SetFont($fontname, 'BI', 8, '', 'false');

	$pdf->MultiCell	(160,10,'Наименование вредных и (или) опасных факторов производственной среды и трудового процесса и продолжительность их воздействия на работника в течение рабочего дня (смены) (час.)',1,'C',1,1,'122','30',1,0,0,1,10,'M');
	$pdf->MultiCell	(140,5,'Физические факторы',1,'C',1,1,'142','40',1,0,0,1,5,'M');
	$pdf->SetAutoPageBreak(True, 25);

	$pdf->SetXY(15,90);
//	PDF_insert_nums($pdf);

	//Поехали!
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');
	PDF_insert_RM($pdf, $target, $fontname, $fontname_bold);
	$pdf->SetFont($fontname, 'BI', 10, '', 'false');
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
			$inPDF->SetFont($infontnamebold, 'BI', 8, '', 'false');
			$rowcount = max($inPDF->getNumLines(htmlspecialchars_decode($vRow[sName]), 67),$inPDF->getNumLines(PDF_replace_null_micro($vRow[sNumAnalog]), 15));
			$rowheight = $rowcount*3.8;
			$inPDF->MultiCell(10,$rowheight,$vRow[iNumber],1,'C',0,0);
			$inPDF->MultiCell(67,$rowheight,htmlspecialchars_decode($vRow[sName]),1,'L',0,0);
			$inPDF->SetFont($infontname, 'BI', 8, '', 'false');
			$inPDF->MultiCell(15,$rowheight,$vRow[iCount],1,'C',0,0);
			$inPDF->MultiCell(15,$rowheight,PDF_replace_null_micro($vRow[sNumAnalog]),1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
			$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,1);

			if($num_pages < $inPDF->getNumPages())
            {
				$inPDF->rollbackTransaction(true);
				$inPDF->AddPage();
				//Вставка заголовка
				PDF_InsertHeaderRmList($inPDF);

				//Вставка содержимого
				$inPDF->SetFont($infontnamebold, 'BI', 8, '', 'false');
				$rowcount = max($inPDF->getNumLines($vRow[sName], 67),$inPDF->getNumLines(PDF_replace_null_micro($vRow[sNumAnalog]), 15));
				$rowheight = $rowcount*3.8;
				$inPDF->MultiCell(10,$rowheight,$vRow[iNumber],1,'C',0,0);
				$inPDF->MultiCell(67,$rowheight,$vRow[sName],1,'L',0,0);
				$inPDF->SetFont($infontname, 'BI', 8, '', 'false');
				$inPDF->MultiCell(15,$rowheight,$vRow[iCount],1,'C',0,0);
				$inPDF->MultiCell(15,$rowheight,PDF_replace_null_micro($vRow[sNumAnalog]),1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,0);
				$inPDF->MultiCell(10,$rowheight,'-',1,'C',0,1);
			}
            else
            {
                //Otherwise we are fine with this row, discard undo history.
                $inPDF->commitTransaction();
            }
			//Конец цикла вставки


			$sqlP = "SELECT `Arm_rmPoints`.`id`, `Arm_rmPoints`.`sName`, `Arm_rmPointsRm`.`sTime` FROM `Arm_rmPoints`, `Arm_rmPointsRm` WHERE `Arm_rmPoints`.`id` = `Arm_rmPointsRm`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = ".$vRow[id].";";
			$vResultP = DbConnect::GetSqlQuery($sqlP);
			if (mysql_num_rows($vResultP) > 0)
			{
				while($vRowP = mysql_fetch_array($vResultP))
				{

					/*$sqlF = "SELECT id FROM Arm_rmFactors WHERE Arm_rmFactors.idPoint = $vRowP[id]";
					$vResultF = DbConnect::GetSqlQuery($sqlF);
					if (mysql_num_rows($vResultF) > 0)
					{	*/

					//Начало цикла вставки
					$num_pages = $inPDF->getNumPages();
					$inPDF->startTransaction();

					//Вставка содержимого
					$rowcount = $inPDF->getNumLines($vRowP[sName], 67);
					$rowheight = $rowcount*3.8;
					$inPDF->MultiCell(10,$rowheight,'',1,'C',0,0);
					$inPDF->MultiCell(67,$rowheight,$vRowP[sName],1,'L',0,0);
					$inPDF->MultiCell(15,$rowheight,'',1,'C',0,0);
					$inPDF->MultiCell(15,$rowheight,'',1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],31,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],33,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],8,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],13,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],14,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],15,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],16,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],54,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorIds($vRowP[id],'22, 23, 24, 25',$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],26,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],27,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],28,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],1,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],17,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],37,$vRowP[sTime]),1,'C',0,0);
					$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],46,$vRowP[sTime]),1,'C',0,1);
					if($num_pages < $inPDF->getNumPages())
					{
						$inPDF->rollbackTransaction(true);
						$inPDF->AddPage();
						//Вставка заголовка
						PDF_InsertHeaderRmList($inPDF);

						//Вставка содержимого
						$rowcount = $inPDF->getNumLines($vRowP[sName], 67);
						$rowheight = $rowcount*3.8;
						$inPDF->MultiCell(10,$rowheight,'',1,'C',0,0);
						$inPDF->MultiCell(67,$rowheight,$vRowP[sName],1,'L',0,0);
						$inPDF->MultiCell(15,$rowheight,'',1,'C',0,0);
						$inPDF->MultiCell(15,$rowheight,'',1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],31,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],33,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],8,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],13,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],14,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],15,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],16,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],54,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorIds($vRowP[id],'22, 23, 24, 25',$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],26,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorId($vRowP[id],27,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],28,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],1,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],17,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],37,$vRowP[sTime]),1,'C',0,0);
						$inPDF->MultiCell(10,$rowheight,PDF_isFactorGroup($vRowP[id],46,$vRowP[sTime]),1,'C',0,1);
					}
					else
					{
						//Otherwise we are fine with this row, discard undo history.
						$inPDF->commitTransaction();
					}
					//Конец цикла вставки
				//}


//$html .= '<tr><td width="10mm" align="center"></td><td width="67mm" align="left">'.$vRowP[sName].'</td><td width="15mm" align="center"></td><td width="15mm" align="center"></td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],31,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],33,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],8,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],13,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],14,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],15,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],16,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],54,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorIds($vRowP[id],'22, 23, 24, 25',$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],26,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorId($vRowP[id],27,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],28,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],1,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],17,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],37,$vRowP[sTime]).'</td><td width="10mm" align="center">'.PDF_isFactorGroup($vRowP[id],46,$vRowP[sTime]).'</td></tr>';
				}
			}
		}
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$inPDF->MultiCell(10,0,'1',1,'C',1,0);
	$inPDF->MultiCell(67,0,'2',1,'C',1,0);
	$inPDF->MultiCell(15,0,'3',1,'C',1,0);
	$inPDF->MultiCell(15,0,'4',1,'C',1,0);
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
	$inPDF->MultiCell(10,0,'20',1,'C',1,1);
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

<?
	ini_set('memory_limit','64M');
	$sDocName = '0.6_SOUT_Actions.pdf';
	$pdf->SetFont($fontname, 'BI', 8, '', 'false');
	//Значение группы для формируемого документа
	$agroup = GroupWork::ReadGroupFull($target);
	$pdf->tmpOrgName = '<br>'.$agroup[sFullName];
	$pdf->tmpDocType = 'Перечень рекомендуемых мероприятий по улучшению условий труда';
	
	$html ='
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center"><h2><font face="calibrib">Перечень рекомендуемых мероприятий по улучшению условий труда</font></h2></td>
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
	$h = 15;
	$pdf->MultiCell	(37,$h,'Наименование структурного подразделения, рабочего места',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$pdf->MultiCell	(55,$h,'Наименование мероприятия',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$pdf->MultiCell	(55,$h,'Цель мероприятия',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$pdf->MultiCell	(40,$h,'Срок выполнения',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$pdf->MultiCell	(40,$h,'Структурные подразделения, привлекаемые для выполнения мероприятия',1,'C',1,0,'','',1,0,0,1,$h,'M');
	$pdf->MultiCell	(40,$h,'Отметка о выполнении',1,'C',1,1,'','',1,0,0,1,$h,'M');
	
	$pdf->SetAutoPageBreak(True, 25);
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
	$sql = "SELECT `id`, `sName`, `idParent` FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	$bRow = false;
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			//Название подразделения
			$tmpUnitName = DbConnect::GetSqlCell("SELECT `sName` FROM `Arm_workplace` WHERE `id` = ".$vRow[idParent].";").', '. $vRow[sName];
			//Список мероприятий
			$sqlA = "SELECT * FROM `Arm_activity` WHERE `iRmId` = ".$vRow[id]." AND `iType` = 0;";
			$vResultA = DbConnect::GetSqlQuery($sqlA);
			$firstRow = true;
			if (mysql_num_rows($vResultA) > 0)
			{
			while($vRowA = mysql_fetch_array($vResultA))
			{
				$bRow = true;
				if (strlen($vRow[sNumAnalog]) > 0)
				{
					$vRow[iNumber] = $vRow[iNumber].'А';
				}
				
				//Начало цикла вставки
				$num_pages = $inPDF->getNumPages();
				$inPDF->startTransaction();
				
				//Вставка содержимого

				$rowcount = max($inPDF->getNumLines($tmpUnitName, 37),$inPDF->getNumLines($vRowA[sActivityName], 55),$inPDF->getNumLines($vRowA[sActivityTarget], 55),$inPDF->getNumLines($vRowA[sTerm], 40),$inPDF->getNumLines($vRowA[sInvolved], 40),$inPDF->getNumLines($vRowA[sMark], 40));
				$rowheight = $rowcount*3.8;
				$inPDF->MultiCell(37,$rowheight,$tmpUnitName,1,'C',0,0,'','',1,0,0,1,$rowheight,'M');
				$inPDF->MultiCell(55,$rowheight,StringWork::CheckNullStrLite($vRowA[sActivityName]),1,'C',0,0,'','',1,0,0,1,$rowheight,'M');
				$inPDF->MultiCell(55,$rowheight,StringWork::CheckNullStrLite($vRowA[sActivityTarget]),1,'C',0,0,'','',1,0,0,1,$rowheight,'M');
				$inPDF->MultiCell(40,$rowheight,StringWork::CheckNullStrLite($vRowA[sTerm]),1,'C',0,0,'','',1,0,0,1,$rowheight,'M');
				$inPDF->MultiCell(40,$rowheight,StringWork::CheckNullStrLite($vRowA[sInvolved]),1,'C',0,0,'','',1,0,0,1,$rowheight,'M');
				$inPDF->MultiCell(40,$rowheight,'',1,'C',0,1,'','',1,0,0,1,$rowheight,'M');
				
				if($num_pages < $inPDF->getNumPages())
				{
					$inPDF->rollbackTransaction(true);
					$inPDF->AddPage();
					//Вставка заголовка	
					PDF_InsertHeaderRmList($inPDF);		
					
					//Вставка содержимого
					$rowcount = max($inPDF->getNumLines($tmpUnitName, 37),$inPDF->getNumLines($vRowA[sActivityName], 55),$inPDF->getNumLines($vRowA[sActivityTarget], 55),$inPDF->getNumLines($vRowA[sTerm], 40),$inPDF->getNumLines($vRowA[sInvolved], 40),$inPDF->getNumLines($vRowA[sMark], 40));
					$rowheight = $rowcount*3.8;
					$inPDF->MultiCell(37,$rowheight,$tmpUnitName,1,'C',0,0);
					$inPDF->MultiCell(55,$rowheight,StringWork::CheckNullStrLite($vRowA[sActivityName]),1,'C',0,0);
					$inPDF->MultiCell(55,$rowheight,StringWork::CheckNullStrLite($vRowA[sActivityTarget]),1,'C',0,0);
					$inPDF->MultiCell(40,$rowheight,StringWork::CheckNullStrLite($vRowA[sTerm]),1,'C',0,0);
					$inPDF->MultiCell(40,$rowheight,StringWork::CheckNullStrLite($vRowA[sInvolved]),1,'C',0,0);
					$inPDF->MultiCell(40,$rowheight,'',1,'C',0,1);
				}
				else
				{
					//Otherwise we are fine with this row, discard undo history.
					$inPDF->commitTransaction();
				}
				if($firstRow) {$firstRow = false; $tmpUnitName = '';}
			}
			}
		}
	}
	
	if(!$bRow)
	{
		$inPDF->MultiCell(267,10,'По итогам проведения специальной оценки условий труда, рекомендации по улучшению условий труда отсутсвуют.',1,'C',0,1,'','',1,0,0,1,10,'M');
	}
}

function PDF_InsertHeaderRmList($inPDF)
{
	$inPDF->MultiCell(37,0,'1',1,'C',1,0);
	$inPDF->MultiCell(55,0,'2',1,'C',1,0);
	$inPDF->MultiCell(55,0,'3',1,'C',1,0);
	$inPDF->MultiCell(40,0,'4',1,'C',1,0);
	$inPDF->MultiCell(40,0,'5',1,'C',1,0);
	$inPDF->MultiCell(40,0,'6',1,'C',1,1);
}
?>

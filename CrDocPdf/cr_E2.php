<?
	ini_set('memory_limit','64M');
	$sDocName = '0.1_SOUT_WorkJournal.pdf';
	$agroup = GroupWork::ReadGroupFull($target);
	$pdf->tmpOrgName = '<br>'.$agroup[sFullName];
	$pdf->tmpDocType = 'Рабочий журнал по проведению специальной оценки условий труда в организации';

	$sql = "SELECT * FROM Arm_group WHERE Arm_group.id = ".$target."";
	$vResultGroup = DbConnect::GetSqlRow($sql);

	$sql = "SELECT * FROM Arm_users WHERE Arm_users.id = ".$vResultGroup[idParent].";";
	$vResultUsers = DbConnect::GetSqlRow($sql);
	$sSOUTORGid = $vResultUsers[id];	

	$pdf->SetAutoPageBreak(False);
	$pdf->SetFillColor(217,217,217);
	$pdf->SetAutoPageBreak(True, 25);

	$sqlZone = "SELECT Arm_rmPoints.id, Arm_rmPoints.sName FROM Arm_workplace, Arm_rmPointsRm, Arm_rmPoints WHERE idGroup = ".$target." AND Arm_rmPointsRm.idRm = Arm_workplace.id AND Arm_rmPoints.id = Arm_rmPointsRm.idPoint GROUP BY Arm_rmPoints.id ORDER BY `Arm_rmPoints`.`iType`, Arm_rmPoints.sName;";
	$vResultZ = DbConnect::GetSqlQuery($sqlZone);

	$aNamesPoints = array();
	$aNumPoints = array();
	$aStrPoints = array();
	$pdf->SetFont($infontnamebold, '', 14, '', 'false');
	$pdf->MultiCell	(270,50, htmlspecialchars_decode(StringWork::CheckNullStrFull(UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid))), 0, 'C', 0, 1);
	$pdf->SetFont($infontnamebold, '', 20, '', 'false');
	$pdf->MultiCell	(270,0, 'Рабочий журнал № __________', 0, 'C', 0, 1);
	$pdf->SetFont($fontname, '', 16, '', 'false');
	$pdf->SetFont($infontnamebold, '', 20, '', 'false');
	$pdf->MultiCell	(270,15, 'проведения измерений в рамках СОУТ', 0, 'C', 0, 1);
	$pdf->MultiCell	(270,40, htmlspecialchars_decode($vResultGroup[sFullName]), 0, 'C', 0, 1);

	$pdf->SetFont($fontname, '', 14, '', 'false');
	$pdf->MultiCell	(270,10, 'Начат  «__»_______________ 201__г.', 0, 'R', 0, 1);
	$pdf->MultiCell	(270,10, 'Окончен  «__»_______________ 201__г.', 0, 'R', 0, 1);
	$pdf->MultiCell	(270,10, 'Ответственный за ведение журнала', 0, 'R', 0, 1);
	$pdf->MultiCell	(270,8, '________________________________', 0, 'R', 0, 1);
	$pdf->SetFont($fontname, '', 12, '', 'false');
	$pdf->MultiCell	(270,10, '(ФИО, должность)', 0, 'R', 0, 1);

	$pdf->AddPage();

	//$pdf->MultiCell	(60,7,'',0,'L',0,1,'','',1,0,0,1,5,'M');
	$pdf->SetFont($infontname, '', 10, '', 'false');



	$counter = 1;
	if (mysql_num_rows($vResultZ) > 0)
	{	
		
		$lines = 0;
		
		while($vRowZ = mysql_fetch_array($vResultZ))
		{
			$sqlRm = "SELECT Arm_workplace.sName, Arm_workplace.iNumber FROM Arm_rmPointsRm, Arm_workplace WHERE Arm_rmPointsRm.idPoint = ".$vRowZ[id]." AND Arm_rmPointsRm.idRm = Arm_workplace.id GROUP BY Arm_workplace.sName";
			$vResultRM = DbConnect::GetSqlQuery($sqlRm);
			$sRmNames = 0;
			$aRmNames = array();
			if (mysql_num_rows($vResultRM) > 0)
			{	
				while($vRowRM = mysql_fetch_array($vResultRM))
				{
					array_push($aRmNames, $vRowRM[iNumber].' '.$vRowRM[sName]);
				}
				$sRmNames = implode('; ',$aRmNames);
			}
			
			if (count($aRmNames)>0)
			{
		
			$pdf->StartTransform();
			$pdf->SetFont($infontname, '', 6, '', 'false');
			$lines = $lines + $pdf->MultiCell(100,10,$sRmNames,1,'L',1,0,'','',1,0,0,1,10,'M');
			$pdf->SetFont($infontname_bold, '', 10, '', 'false');
			$pdf->MultiCell(170,10,$vRowZ[sName],1,'R',1,1,'','',1,0,0,1,10,'M');
			$pdf->SetFont($infontname, '', 10, '', 'false');
			array_push($aNamesPoints, $vRowZ[sName]);
			array_push($aStrPoints, $pdf->getNumPages());
			$counter++;
			$sqlFactors = "SELECT sName FROM Arm_rmFactors WHERE idPoint = ".$vRowZ[id];
			$vResultF = DbConnect::GetSqlQuery($sqlFactors);

			$bWasDraw = false;
//			if (mysql_num_rows($vResultF) > 0)
//			{	
				while($vRowF = mysql_fetch_array($vResultF))
				{       
					$pdf->SetFont($infontname, '', 8, '', 'false');
					$lines = $lines + $pdf->MultiCell(100,7,$vRowF[sName],1,'L',0,0,'','',1,0,0,1,7,'M');
					$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
					$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');		
					$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
					if(!$bWasDraw)
					{
						$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
						$pdf->SetFont($fontname, '', 8, '', 'false');
						$pdf->SetTextColor(150,150,150);
						$pdf->MultiCell(110,7,'Высота подвеса ламп ________ тип ________ процент не работающих ________',0,'R',0,1,'','',1,0,0,1,7,'B');
						$pdf->SetTextColor(0,0,0);
						$pdf->SetFont($fontname, '', 10, '', 'false');
						$bWasDraw = true;
					}
					else
					{
        				$pdf->MultiCell(15,7,'',1,'R',0,1,'','',1,0,0,1,0,'M');
					}
					//$pdf->MultiCell(100,7,$lines,1,'R',0,1,'','',1,0,0,1,5,'M');
				}


				for ($ii=0; $ii<6 - mysql_num_rows($vResultF); $ii++)
				{			
					$lines = $lines + $pdf->MultiCell(100,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
					$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
					$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
					$pdf->MultiCell(15,7,'',1,'R',0,0,'','',1,0,0,1,7,'M');
					$pdf->MultiCell(15,7,'',1,'R',0,1,'','',1,0,0,1,7,'M');
				}
//			}
			
			if ($lines > 18)
			{        	
				$lines = 0;
				$pdf->rollbackTransaction(true);
				$pdf->AddPage();
			}
			else
			{
				$pdf->commitTransaction();
			}

		        }
		}
	}





	//Øàïêà óøàíêà

	//$pdf->SetFont($infontname, 'BI', 10, '', 'false');
	//$pdf->Rotate(90, 15, 90);

	//Содержание
//	$pdf->MultiCell	(60,7,'',0,'L',0,1,'','',1,0,0,1,5,'M');
	$iCounter = 0;
	if ($lines != 0 && $lines < 20) {$pdf->AddPage();}
	$pdf->MultiCell	(60,7,'Содержание:',0,'L',0,1,'','',1,0,0,1,5,'M');
	for ($i = 0; $i < count ($aNamesPoints); $i++)
	{
		$counterS = $i+1;
		$iCounter++;
		$pdf->MultiCell	(110,7,'№'.$counterS.' '.$aNamesPoints[$i],0,'L',0,0,'','',1,0,0,1,5,'M');
		if ($iCounter == 1)
		{
			$pdf->MultiCell	(20,7,'стр. '.$aStrPoints[$i],0,'L',0,0,'','',1,0,0,1,5,'M');
		}
		else
		{
			$iCounter = 0;
			$pdf->MultiCell	(20,7,'стр. '.$aStrPoints[$i],0,'L',0,1,'','',1,0,0,1,5,'M');
		}
	}

        $pdf->commitTransaction();


?>

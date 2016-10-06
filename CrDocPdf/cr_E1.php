<?
	ini_set('memory_limit','64M');
	$sDocName = '0.1_SOUT_ExpertOpinion.pdf';
	
	$sql = "SELECT * FROM Arm_group WHERE Arm_group.id = ".$target."";
	$vResultGroup = DbConnect::GetSqlRow($sql);
	
	$sql = "SELECT * FROM Arm_users WHERE Arm_users.id = ".$vResultGroup[idParent].";";
	$vResultUsers = DbConnect::GetSqlRow($sql);

	$sql = "SELECT * FROM Arm_groupStuff WHERE idGroup = ".$vResultGroup[id]." AND bExpert = 1;";
	$vResultStuff = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);

	$sql = "SELECT * FROM Arm_workplace WHERE idGroup = ".$vResultGroup[id]." AND idParent <> -1;";
	$vResultRM = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);

	//Количество рабочих мест
	$sNumRm = mysql_num_rows($vResultRM);

	//Имена экспертов
	$sStuffNames = '';

	//Массив имен экспертов
	$aNames = array();
	if (mysql_num_rows($vResultStuff) > 0)
	{
                                        	
		while($vRowStuff = mysql_fetch_array($vResultStuff))
		{
			array_push($aNames, $vRowStuff[sName]);
		}
		$sStuffNames = implode(',',$aNames);
	}

	//Количество РМ с соответсвующими оценками
	$iRm1Count = 0;
	$iRm2Count = 0;
	$iRm31Count = 0;
	$iRm32Count = 0;
	$iRm33Count = 0;
	$iRm4Count = 0;

	if (mysql_num_rows($vResultRM) > 0)
	{
		$aNames = array();                                        	
		while($vRowRM = mysql_fetch_array($vResultRM))
		{
			switch ($vRowRM[iATotal])
			{
				case 1:
					$iRm1Count++;
				break;
				case 2:
					$iRm2Count++;
				break;
				case 3:
					$iRm31Count++;
				break;
				case 4:
					$iRm32Count++;
				break;
				case 5:
					$iRm33Count++;
				break;
				case 6:
					$iRm4Count++;
				break;
			}			
		}

	}

	                                               	
	$sSOUTORGid = $vResultUsers[id];	
	
	//Имя организации проводящей СУОТ
	$sSoutOrgName = UserControl::GetUserFieldValueFromId('sOrgName',$sSOUTORGid);

	//Имя организации в которой проводился СУОТ
	$sOrgName = $vResultGroup[sName];

	//Название документа
//	$sDocName = $vResultGroup[sDocName];

	//Дата начала
	$sStartDate = $vResultGroup[dStartDate];

	//Дата окончания
	$sEndDate = $vResultGroup[dEndDate];

	//Количество рабочих мест на предприятии
	$sOrgNumRm = $vResultGroup[iRmTotalCount];
	
	$pdf->tmpOrgName = '<br>'.$vResultGroup[sFullName];
	$pdf->tmpDocType = 'Заключение эксперта на материалы специальной оценки условий труда';
	$html =''.$sSoutOrgName.'|'.$sOrgName.'|'.$sStuffNames.'|'.$sNumRm.' из '.$sOrgNumRm.'|'.$iRm1Count.','.$iRm2Count.','.$iRm31Count.','.$iRm32Count.','.$iRm33Count.','.$iRm4Count;
?>

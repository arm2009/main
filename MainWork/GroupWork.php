<?php
	include_once('LowLevel/userValidator.php');
	include_once('UserControl/userControl.php');

	class GroupWork
	{
		public static function IsCanEditGroup($idGroup)
		{
			$bResult = false;
			$aGroups = GroupWork::FillGroupList();
			if(count($aGroups) > 0)
			foreach($aGroups as $aGroup)
			{
				if (intval($aGroup[0]) == intval($idGroup))
				{
					$bResult = true;
				}
			}
			$aGroups = GroupWork::FillGroupList('archive');
			if(count($aGroups) > 0)
			foreach($aGroups as $aGroup)
			{
				if (intval($aGroup[0]) == intval($idGroup))
				{
					$bResult = true;
				}
			}
			return $bResult;
		}

		//Сохранение изменений группы данных
		public static function SaveGroup($iId, $sName, $iParentKey, $sFullName, $sPlace, $sEmail, $sNameDirector, $sInn, $sOgrn, $sOkved, $sOkpo, $sOkogu, $sOkato, $sPredsName, $sPredsPost, $sPostDirector, $sPhone, $sPNumTenesy, $sPNumHeavy, $sPNumAir, $sPNumLight, $sPNumNoise, $sPNumNoiseNoise, $sPNumClimate, $sExpEndDoc, $sExpEndDate, $iRmTotalCount, $iWorkerTotal, $iWorkerTotalWoman, $iWorkerTotalYang, $iWorkerTotalMedical, $dStartDate, $dEndDate, $sDocName, $sNTens, $sNHeavy, $sNAir, $sNLight, $sNNoise, $sNClimate)
		{
			$sExpEndDate = new DateTime($sExpEndDate);
			$dStartDate = new DateTime($dStartDate);
			$dEndDate = new DateTime($dEndDate);
			$sSql = 'UPDATE Arm_group SET
			idParent = '.DbConnect::ToBaseStr($iParentKey).',
			bTemp = 0,
			sName = "'.DbConnect::ToBaseStr($sName).'",
			sFullName = "'.DbConnect::ToBaseStr($sFullName).'",
			sPlace = "'.DbConnect::ToBaseStr($sPlace).'",
			sEmail = "'.DbConnect::ToBaseStr($sEmail).'",
			sNameDirector = "'.DbConnect::ToBaseStr($sNameDirector).'",
			sInn = "'.DbConnect::ToBaseStr($sInn).'",
			sOgrn = "'.DbConnect::ToBaseStr($sOgrn).'",
			sOkved = "'.DbConnect::ToBaseStr($sOkved).'",
			sOkpo = "'.DbConnect::ToBaseStr($sOkpo).'",
			sOkogu = "'.DbConnect::ToBaseStr($sOkogu).'",
			sOkato = "'.DbConnect::ToBaseStr($sOkato).'",
			sPredsName = "'.DbConnect::ToBaseStr($sPredsName).'",
			sPredsPost = "'.DbConnect::ToBaseStr($sPredsPost).'",
			sPostDirector = "'.DbConnect::ToBaseStr($sPostDirector).'",
			sPhone = "'.DbConnect::ToBaseStr($sPhone).'",
			sPNumTenesy = "'.DbConnect::ToBaseStr($sPNumTenesy).'",
			sPNumHeavy = "'.DbConnect::ToBaseStr($sPNumHeavy).'",
			sPNumAir = "'.DbConnect::ToBaseStr($sPNumAir).'",
			sPNumLight = "'.DbConnect::ToBaseStr($sPNumLight).'",
			sPNumNoise = "'.DbConnect::ToBaseStr($sPNumNoise).'",
			sPNumNoiseNoise = "'.DbConnect::ToBaseStr($sPNumNoiseNoise).'",
			sPNumClimate = "'.DbConnect::ToBaseStr($sPNumClimate).'",
			sExpEndDoc = "'.DbConnect::ToBaseStr($sExpEndDoc).'",
			sExpEndDate = \''.DbConnect::ToBaseStr($sExpEndDate->format('Y.m.d')).'\',
			iRmTotalCount = '.DbConnect::ToBaseStr($iRmTotalCount).',
			iWorkerTotal = '.DbConnect::ToBaseStr($iWorkerTotal).',
			iWorkerTotalWoman = '.DbConnect::ToBaseStr($iWorkerTotalWoman).',
			iWorkerTotalYang = '.DbConnect::ToBaseStr($iWorkerTotalYang).',
			iWorkerTotalMedical = '.DbConnect::ToBaseStr($iWorkerTotalMedical).',
			dStartDate = \''.DbConnect::ToBaseStr($dStartDate->format('Y.m.d')).'\',
			dEndDate = \''.DbConnect::ToBaseStr($dEndDate->format('Y.m.d')).'\',
			sDocName = "'.DbConnect::ToBaseStr($sDocName).'",
			sNTens = "'.DbConnect::ToBaseStr($sNTens).'",
			sNHeavy = "'.DbConnect::ToBaseStr($sNHeavy).'",
			sNAir = "'.DbConnect::ToBaseStr($sNAir).'",
			sNLight = "'.DbConnect::ToBaseStr($sNLight).'",
			sNNoise = "'.DbConnect::ToBaseStr($sNNoise).'",
			sNClimate = "'.DbConnect::ToBaseStr($sNClimate).'"
			WHERE id = '.$iId.';';
//			echo($sSql);
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
			GroupWork::SetLastChangeStamp($iId);
		}

		public static function GetMyGroupCount($sStatus = '')
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT `id` FROM `Arm_group` WHERE `Arm_group`.`idParent` = '.UserControl::GetUserLoginId().' AND `bTemp` = 0 AND Arm_group.sStatus = "'.$sStatus.'";');
			return mysql_num_rows($vResult);
		}

		public static function FillGroupList($sStatus = '')
		{
			$aArray = GroupWork::FillWorkSpace();

			foreach($aArray as $aArr)
			{
				$aArrayIds[] = $aArr[0];
			}

			$idWorkSpaces = implode(',',$aArrayIds);

			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT Arm_users.id as idUser, DECODE(Arm_users.sOrgName,"04022009") as sNameSpace, Arm_group.id as id, Arm_group.sName as sName FROM Arm_users, Arm_group WHERE Arm_users.id = Arm_group.idParent AND Arm_users.id IN ('.$idWorkSpaces.') AND Arm_group.sStatus = "'.$sStatus.'" AND `bTemp` = 0 ORDER BY `sName`;');

			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$aResult[] = array($vRow['id'], $vRow['sName'], $vRow['sNameSpace'], $vRow['idUser']);
				}
			}

			return $aResult;
		}

		//Чтение группы в массив
		public static function ReadGroupFull($idGroup)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT idParent, sName, sFullName, sPlace, sEmail, sNameDirector, sInn, sOgrn, sOkved, sOkpo, sOkogu, sOkato, sPredsName, sPredsPost, sPostDirector, `sPhone`, `sPNumTenesy`, `sPNumHeavy`, `sPNumAir`, `sPNumLight`, `sPNumNoise`, `sPNumNoiseNoise`, `sPNumClimate`, `sExpEndDoc`, `sExpEndDate`, `iRmTotalCount`, `iWorkerTotal`, `iWorkerTotalWoman`, `iWorkerTotalYang`, `iWorkerTotalMedical`, dStartDate, dEndDate, sDocName, sNTens, sNHeavy, sNAir, sNLight, sNNoise, sNClimate FROM Arm_group WHERE id = '.$idGroup.';');

			$aReturn['idParent'] = mysql_result($vResult, 0, 0);
			$aReturn['sName'] = mysql_result($vResult, 0, 1);
			$aReturn['sFullName'] = mysql_result($vResult, 0, 2);
			$aReturn['sPlace'] = mysql_result($vResult, 0, 3);
			$aReturn['sEmail'] = mysql_result($vResult, 0, 4);
			$aReturn['sNameDirector'] = mysql_result($vResult, 0, 5);
			$aReturn['sInn'] = mysql_result($vResult, 0, 6);
			$aReturn['sOgrn'] = mysql_result($vResult, 0, 7);
			$aReturn['sOkved'] = mysql_result($vResult, 0, 8);
			$aReturn['sOkpo'] = mysql_result($vResult, 0, 9);
			$aReturn['sOkogu'] = mysql_result($vResult, 0, 10);
			$aReturn['sOkato'] = mysql_result($vResult, 0, 11);
			$aReturn['sPredsName'] = mysql_result($vResult, 0, 12);
			$aReturn['sPredsPost'] = mysql_result($vResult, 0, 13);
			$aReturn['sPostDirector'] = mysql_result($vResult, 0, 14);
			$aReturn['sPhone'] = mysql_result($vResult, 0, 15);
			$aReturn['sPNumTenesy'] = mysql_result($vResult, 0, 16);
			$aReturn['sPNumHeavy'] = mysql_result($vResult, 0, 17);
			$aReturn['sPNumAir'] = mysql_result($vResult, 0, 18);
			$aReturn['sPNumLight'] = mysql_result($vResult, 0, 19);
			$aReturn['sPNumNoise'] = mysql_result($vResult, 0, 20);
			$aReturn['sPNumNoiseNoise'] = mysql_result($vResult, 0, 21);
			$aReturn['sPNumClimate'] = mysql_result($vResult, 0, 22);
			$aReturn['sExpEndDoc'] = mysql_result($vResult, 0, 23);
			$aReturn['sExpEndDate'] = StringWork::StrToDateFormatLite(mysql_result($vResult, 0, 24));
			$aReturn['iRmTotalCount'] = mysql_result($vResult, 0, 25);
			$aReturn['iWorkerTotal'] = mysql_result($vResult, 0, 26);
			$aReturn['iWorkerTotalWoman'] = mysql_result($vResult, 0, 27);
			$aReturn['iWorkerTotalYang'] = mysql_result($vResult, 0, 28);
			$aReturn['iWorkerTotalMedical'] = mysql_result($vResult, 0, 29);
			$aReturn['dStartDate'] = StringWork::StrToDateFormatLite(mysql_result($vResult, 0, 30));
			$aReturn['dEndDate'] = StringWork::StrToDateFormatLite(mysql_result($vResult, 0, 31));
			$aReturn['sDocName'] = mysql_result($vResult, 0, 32);
			$aReturn['sNTens'] = mysql_result($vResult, 0, 33);
			$aReturn['sNHeavy'] = mysql_result($vResult, 0, 34);
			$aReturn['sNAir'] = mysql_result($vResult, 0, 35);
			$aReturn['sNLight'] = mysql_result($vResult, 0, 36);
			$aReturn['sNNoise'] = mysql_result($vResult, 0, 37);
			$aReturn['sNClimate'] = mysql_result($vResult, 0, 38);

			return $aReturn;
		}

		//Заполнение возможных рабочих пространств
		public static function FillWorkSpace()
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT DECODE(sOrgName,"'.UserControl::GetSalt().'") FROM Arm_users WHERE id ='.UserControl::GetUserLoginId());
			$aResult[] = array(UserControl::GetUserLoginId(),mysql_result($vResult, 0, 0));

			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT Arm_users.id as id, DECODE(Arm_users.sOrgName,"'.UserControl::GetSalt().'") as name FROM Arm_users, Arm_soworkers WHERE Arm_soworkers.idChild = '.UserControl::GetUserLoginId().' AND Arm_users.id = Arm_soworkers.idParent;');
			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$aResult[] = array($vRow['id'], $vRow['name']);
				}
			}
			return $aResult;
		}

		public static function AddGroup()
		{
			$iInsertedKey = -1;
			$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_group (idParent, sName, bTemp, sExpEndDate, dStartDate, dEndDate) VALUES ('.UserControl::GetUserLoginId().', "Новая группа данных", "1", NOW(), NOW(), NOW());');
			$iInsertedKey = mysql_insert_id();
			GroupWork::SetLastChangeStamp($iInsertedKey);
			return $iInsertedKey;
		}

		//Чтение группы данных в массив
		public static function ReadGroup()
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName FROM Arm_group WHERE idParent ='.$iParentKey);
			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$aResult[] = array($vRow['id'], $vRow['sName']);
				}
			}
			return $aResult;
		}

		public static function AddComiss($sName, $sPost, $iParentKey)
		{
			$sSql = 'INSERT INTO `Arm_comiss` (`idParent`, `sName`, `sPost`) VALUES ('.(string)DbConnect::ToBaseStr($iParentKey).',"'.(string)DbConnect::ToBaseStr($sName).'","'.(string)DbConnect::ToBaseStr($sPost).'");';

			$insertedId = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
			GroupWork::SetLastChangeStamp($iParentKey);
			return $insertedId;

		}

		public static function ReadComiss($iParentKey)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sPost FROM Arm_comiss WHERE idParent ='.$iParentKey);
			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$aResult[] = array($vRow['id'], $vRow['sName'], $vRow['sPost']);
				}
			}
			return $aResult;
		}

		public static function ReadOneComiss($id)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sPost FROM Arm_comiss WHERE id ='.$id);
			if (mysql_num_rows($vResult) > 0)
			{
				$aResult = array(mysql_result($vResult,0, 0), mysql_result($vResult,0, 1), mysql_result($vResult,0, 2));
			}


			return $aResult;
		}

		public static function EditComiss($id, $sName, $sPost)
		{
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_comiss SET sName="'.$sName.'", sPost="'.$sPost.'" WHERE id ='.$id);
		}

		public static function DelComiss($id)
		{

			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_comiss WHERE id ='.$id);
		}

		public static function SetStatus($id, $sStatus)
		{
			echo ($id);
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_group SET sStatus="'.$sStatus.'" WHERE id ='.$id);
		}

		//Штамп последнего изменения группы данных
		public static function SetLastChangeStamp($idGroup, $idWorkPlace = -1)
		{
			$idUser = UserControl::GetUserLoginId();
			if ($idWorkPlace != -1)
			{
				$sqlWP = 'SELECT idGroup FROM Arm_workplace WHERE id = '.$idWorkPlace.';';
				$vResultWP = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sqlWP);
				if (mysql_num_rows($vResultWP) > 0)
				{
					while($vRow = mysql_fetch_array($vResultWP))
					{
						$idGroup = $vRow['idGroup'];
					}
				}
			}
			$sql = 'UPDATE Arm_group SET dLastChangeDate = NOW(), idLastChangeUser = '.$idUser.' WHERE id = '.$idGroup.';';
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		}

		public static function ReadLastChangeStamp($idGroup)
		{
			$aRetValues = array();
			$sql = 'SELECT idLastChangeUser, dLastChangeDate FROM Arm_group WHERE id = '.$idGroup.';';
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			if (mysql_num_rows($vResult) > 0)
			{
			while($vRow = mysql_fetch_array($vResult))
				{
					if ($vRow['idLastChangeUser'] != '0')
					{
						array_push($aRetValues, UserControl::GetUserFieldValueFromId('sName1', $vRow['idLastChangeUser']));
						array_push($aRetValues, UserControl::GetUserFieldValueFromId('sName2', $vRow['idLastChangeUser']));
						array_push($aRetValues, StringWork::StrToDateFormatLite($vRow['dLastChangeDate']));
					}
					else
					{
						array_push($aRetValues, '');
						array_push($aRetValues, '');
						array_push($aRetValues, 'Никогда');
					}
				}
			}
			return $aRetValues;
		}

	}

?>

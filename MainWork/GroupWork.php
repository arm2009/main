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
		public static function SaveGroup($iId, $sName, $iParentKey, $sFullName, $sPlace, $sEmail, $sNameDirector, $sInn, $sOgrn, $sOkved, $sOkpo, $sOkogu, $sOkato, $sPredsName, $sPredsPost, $sPostDirector, $sPhone, $sPNumTenesy, $sPNumHeavy, $sPNumAir, $sPNumLight, $sPNumNoise, $sPNumNoiseNoise, $sPNumClimate, $sExpEndDoc, $sExpEndDate, $iRmTotalCount, $iWorkerTotal, $iWorkerTotalWoman, $iWorkerTotalYang, $iWorkerTotalMedical, $dStartDate, $dEndDate, $sDocName, $sNTens, $sNHeavy, $sNAir, $sNLight, $sNNoise, $sNClimate, $sKpp)
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
			sKpp = "'.DbConnect::ToBaseStr($sKpp).'",
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
			return mysqli_num_rows($vResult);
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

			if (mysqli_num_rows($vResult) > 0)
			{
				while($vRow = MYSQLI_FETCH_ASSOC($vResult))
				{
					$aResult[] = array($vRow['id'], $vRow['sName'], $vRow['sNameSpace'], $vRow['idUser']);
				}
			}

			return $aResult;
		}

		//Чтение группы в массив
		public static function ReadGroupFull($idGroup)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT idParent, sName, sFullName, sPlace, sEmail, sNameDirector, sInn, sOgrn, sOkved, sOkpo, sOkogu, sOkato, sPredsName, sPredsPost, sPostDirector, `sPhone`, `sPNumTenesy`, `sPNumHeavy`, `sPNumAir`, `sPNumLight`, `sPNumNoise`, `sPNumNoiseNoise`, `sPNumClimate`, `sExpEndDoc`, `sExpEndDate`, `iRmTotalCount`, `iWorkerTotal`, `iWorkerTotalWoman`, `iWorkerTotalYang`, `iWorkerTotalMedical`, dStartDate, dEndDate, sDocName, sNTens, sNHeavy, sNAir, sNLight, sNNoise, sNClimate, sKpp FROM Arm_group WHERE id = '.$idGroup.';');
			$result = MYSQLI_FETCH_ASSOC($vResult);        	
			$aReturn['idParent'] = $result[array_keys($result)[0]];
			$aReturn['sName'] = $result[array_keys($result)[1]];
			$aReturn['sFullName'] = $result[array_keys($result)[2]];
			$aReturn['sPlace'] = $result[array_keys($result)[3]];
			$aReturn['sEmail'] = $result[array_keys($result)[4]];
			$aReturn['sNameDirector'] = $result[array_keys($result)[5]];
			$aReturn['sInn'] = $result[array_keys($result)[6]];
			$aReturn['sOgrn'] = $result[array_keys($result)[7]];
			$aReturn['sOkved'] = $result[array_keys($result)[8]];
			$aReturn['sOkpo'] = $result[array_keys($result)[9]];
			$aReturn['sOkogu'] = $result[array_keys($result)[10]];
			$aReturn['sOkato'] = $result[array_keys($result)[11]];
			$aReturn['sPredsName'] = $result[array_keys($result)[12]];
			$aReturn['sPredsPost'] = $result[array_keys($result)[13]];
			$aReturn['sPostDirector'] = $result[array_keys($result)[14]];
			$aReturn['sPhone'] = $result[array_keys($result)[15]];
			$aReturn['sPNumTenesy'] = $result[array_keys($result)[16]];
			$aReturn['sPNumHeavy'] = $result[array_keys($result)[17]];
			$aReturn['sPNumAir'] = $result[array_keys($result)[18]];
			$aReturn['sPNumLight'] = $result[array_keys($result)[19]];
			$aReturn['sPNumNoise'] = $result[array_keys($result)[20]];
			$aReturn['sPNumNoiseNoise'] = $result[array_keys($result)[21]];
			$aReturn['sPNumClimate'] = $result[array_keys($result)[22]];
			$aReturn['sExpEndDoc'] = $result[array_keys($result)[23]];
			$aReturn['sExpEndDate'] = StringWork::StrToDateFormatLite($result[array_keys($result)[24]]);
			$aReturn['iRmTotalCount'] = $result[array_keys($result)[25]];
			$aReturn['iWorkerTotal'] = $result[array_keys($result)[26]];
			$aReturn['iWorkerTotalWoman'] = $result[array_keys($result)[27]];
			$aReturn['iWorkerTotalYang'] = $result[array_keys($result)[28]];
			$aReturn['iWorkerTotalMedical'] = $result[array_keys($result)[29]];
			$aReturn['dStartDate'] = StringWork::StrToDateFormatLite($result[array_keys($result)[30]]);
			$aReturn['dEndDate'] = StringWork::StrToDateFormatLite($result[array_keys($result)[31]]);
			$aReturn['sDocName'] = $result[array_keys($result)[32]];
			$aReturn['sNTens'] = $result[array_keys($result)[33]];
			$aReturn['sNHeavy'] = $result[array_keys($result)[34]];
			$aReturn['sNAir'] = $result[array_keys($result)[35]];
			$aReturn['sNLight'] = $result[array_keys($result)[36]];
			$aReturn['sNNoise'] = $result[array_keys($result)[37]];
			$aReturn['sNClimate'] = $result[array_keys($result)[38]];
			$aReturn['sKpp'] = $result[array_keys($result)[39]];

			return $aReturn;
		}

		//Заполнение возможных рабочих пространств
		public static function FillWorkSpace()
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT DECODE(sOrgName,"'.UserControl::GetSalt().'") FROM Arm_users WHERE id ='.UserControl::GetUserLoginId());
			$vResult = MYSQLI_FETCH_ASSOC($vResult);
            $aResult[] = array(UserControl::GetUserLoginId(),$vResult[key($vResult)]);
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT Arm_users.id as id, DECODE(Arm_users.sOrgName,"'.UserControl::GetSalt().'") as name FROM Arm_users, Arm_soworkers WHERE Arm_soworkers.idChild = '.UserControl::GetUserLoginId().' AND Arm_users.id = Arm_soworkers.idParent;');
			if (mysqli_num_rows($vResult) > 0)
			{
				while($vRow = MYSQLI_FETCH_ASSOC($vResult))
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
			$iInsertedKey = $vReturn;
			GroupWork::SetLastChangeStamp($iInsertedKey);
			return $iInsertedKey;
		}

		//Чтение группы данных в массив
		public static function ReadGroup()
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName FROM Arm_group WHERE idParent ='.$iParentKey);
			if (mysqli_num_rows($vResult) > 0)
			{
				while($vRow = mysqli_fetch_array($vResult))
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
			if (mysqli_num_rows($vResult) > 0)
			{
				while($vRow = mysqli_fetch_array($vResult))
				{
					$aResult[] = array($vRow['id'], $vRow['sName'], $vRow['sPost']);
				}
			}
			return $aResult;
		}

		public static function ReadOneComiss($id)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sPost FROM Arm_comiss WHERE id ='.$id);
			$res = MYSQLI_FETCH_ASSOC($vResult);
			if (mysqli_num_rows($vResult) > 0)
			{
				$aResult = array($res[array_keys($res)[0]], $res[array_keys($res)[1]], $res[array_keys($res)[2]]);
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
				if (mysqli_num_rows($vResultWP) > 0)
				{
					while($vRow = mysqli_fetch_array($vResultWP))
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
			if (mysqli_num_rows($vResult) > 0)
			{
			while($vRow = mysqli_fetch_array($vResult))
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

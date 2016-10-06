<?php
	include_once('LowLevel/userValidator.php');
	include_once('UserControl/userControl.php');
	include_once('MainWork/GroupWork.php');

	class WorkPlace
	{
		public static function GetWorkPlaseList($idGroup, $bFolders = 'true')
		{
			$aList = null;
			if (GroupWork::IsCanEditGroup($idGroup))
			{
				
				if ($bFolders == 'true')
				{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, idParent, iNumber, sName, sOk, sPrefix FROM Arm_workplace WHERE idGroup = '.$idGroup.' AND idParent = -1 ORDER BY iOrder;');
				}
				else
				{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, idParent, iNumber, sName, sOk, sPrefix FROM Arm_workplace WHERE idGroup = '.$idGroup.' AND idParent <> -1 ORDER BY iNumber DESC;');
				}
						while ($vRow = mysql_fetch_assoc($vResult))
						{
							$aList[] = array($vRow['id'], $vRow['idParent'],$vRow['iNumber'], $vRow['sName'], $vRow['sOk'], $vRow['sPrefix']);
						}
			}
			return $aList;
		}
		
		public static function ReadWorkPlace($id)
		{	

				$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, idParent, iNumber, sName, sOk, sPrefix, dDate, sNumAnalog, sETKS, iCount, iCountWoman, iCountYouth, iCountDisabled, sSnils, dCreateDate, fWorkDay FROM Arm_workplace WHERE id='.$id);
				setlocale(LC_ALL, 'rus');
				if ($id != 0)
				{
					$sDate = date_create(mysql_result($vResult,0,14));
					$sDate = StringWork::DateFormatLite($sDate);
					$aWorkPlace = array(mysql_result($vResult,0,0), mysql_result($vResult,0,1), mysql_result($vResult,0,2), mysql_result($vResult,0,3), mysql_result($vResult,0,4), mysql_result($vResult,0,5), mysql_result($vResult,0,6), mysql_result($vResult,0,7), mysql_result($vResult,0,8), mysql_result($vResult,0,9), mysql_result($vResult,0,10), mysql_result($vResult,0,11), mysql_result($vResult,0,12), mysql_result($vResult,0,13), $sDate, mysql_result($vResult,0,15));
				}

			return $aWorkPlace;
		}
		
		public static function AddWorkPlace($idGroup, $idParent, $sName, $sOk, $sPrefix, $sNum, $sEtks)
		{
			if ($idParent == null || $idParent == '') {$idParent = -1;}
			if (GroupWork::IsCanEditGroup($idGroup))
			{
				$dNowDate = date('Y-m-d H:i:s');
				$dNowDate = date_create($dNowDate);						
				$sNowDate = $dNowDate->format('Y-m-d');
				$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_workplace (idParent, sName, sOk, sPrefix, iNumber, idGroup, dCreateDate, sETKS, iCount, dSizDate, sSIZbase) VALUES ('.$idParent.',"'.$sName.'","'.$sOk.'","'.$sPrefix.'","'.$sNum.'","'.$idGroup.'","'.$sNowDate.'","'.$sEtks.'", 1, "'.$sNowDate.'", "Отсутствует.");');
				GroupWork::SetLastChangeStamp($idGroup);
//				$vRetMaxId = UserValidator::GetSqlCellSafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT max(id) FROM Arm_workplace WHERE sName LIKE "'.$sName.'";');
				return $vReturn;
			}
		}
		
		public static function DelWorkPlace($id, $idGroup)
		{
			if (GroupWork::IsCanEditGroup($idGroup))
			{
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_workplace WHERE id = '.$id.' OR idParent = '.$id.';');
				GroupWork::SetLastChangeStamp($idGroup);
			}
		}
		
		public static function ChangeWorkPlace($id, $sField, $sValue)
		{
			if (GropWork::IsCanEditGroup($idGroup))
			{
				$sValue = DbConnect::ToBaseStr($sValue);
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_workplace SET '.$sField.' = "'.$sValue.'" WHERE id = '.$id.';');
				GroupWork::SetLastChangeStamp(-1, $id);
			}
		}
		
		public static function ChangeWorkPlaceAll($id, $sName, $sOk, $sPrefix, $sNum,$sNumAnalog, $sETKS, $sCount, $sCountWoman, $sCountYouth, $sCountDisabled, $sSnils, $sDateCreate, $fWorkDay)
		{
			$dDateCreate = date_create($sDateCreate);
			$sDateCreate = $dDateCreate->format('Y-m-d');

			$sSql = 'UPDATE Arm_workplace SET 
			sName = "'.DbConnect::ToBaseStr($sName).'",
			sOk = "'.DbConnect::ToBaseStr($sOk).'",
			sPrefix = "'.DbConnect::ToBaseStr($sPrefix).'",
			iNumber = "'.DbConnect::ToBaseStr($sNum).'",
			sNumAnalog = "'.DbConnect::ToBaseStr($sNumAnalog).'",
			sETKS = "'.DbConnect::ToBaseStr($sETKS).'",
			iCount = "'.DbConnect::ToBaseStr($sCount).'",
			iCountWoman = "'.DbConnect::ToBaseStr($sCountWoman).'",
			iCountYouth = "'.DbConnect::ToBaseStr($sCountYouth).'",
			iCountDisabled = "'.DbConnect::ToBaseStr($sCountDisabled).'",
			dCreateDate = "'.$sDateCreate.'",
			sSnils = "'.DbConnect::ToBaseStr($sSnils).'",
			fWorkDay = "'.DbConnect::ToBaseStr($fWorkDay).'"
			WHERE id = '.$id.';';

			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
			GroupWork::SetLastChangeStamp(-1, $id);
		}
		
		public static function GetMaxNumber($idGroup)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT MAX(iNumber) FROM Arm_workplace WHERE idGroup = '.$idGroup);
			return mysql_result($vResult, 0, 0);
		}
		
		public static function SaveWarranty($idRm, $iCompSurcharge, $sCompBaseSurcharge, $sCompFactSurcharge, $iCompVacation, $sCompBaseVacation, $sCompFactVacation, $iCompShortWorkDay, $sCompBaseShortWorkDay, $sCompFactShortWorkDay, $iCompMilk, $sCompBaseMilk, $sCompFactMilk, $iCompFood, $sCompBaseFood, $sCompFactFood, $iCompPension, $sCompBasePension, $sCompFactPension, $iCompPhysical, $sCompBasePhysical, $sCompFactPhysical)
		{
			$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '".$iCompSurcharge."', `sCompBaseSurcharge` = '".$sCompBaseSurcharge."', `sCompFactSurcharge` = '".$sCompFactSurcharge."', `iCompVacation` = '".$iCompVacation."', `sCompBaseVacation` = '".$sCompBaseVacation."', `sCompFactVacation` = '".$sCompFactVacation."', `iCompShortWorkDay` = '".$iCompShortWorkDay."', `sCompBaseShortWorkDay` = '".$sCompBaseShortWorkDay."', `sCompFactShortWorkDay` = '".$sCompFactShortWorkDay."', `iCompMilk` = '".$iCompMilk."', `sCompBaseMilk` = '".$sCompBaseMilk."', `sCompFactMilk` = '".$sCompFactMilk."', `iCompFood` = '".$iCompFood."', `sCompBaseFood` = '".$sCompBaseFood."', `sCompFactFood` = '".$sCompFactFood."', `iCompPension` = '".$iCompPension."', `sCompBasePension` = '".$sCompBasePension."', `sCompFactPension` = '".$sCompFactPension."', `iCompPhysical` = '".$iCompPhysical."', `sCompBasePhysical` = '".$sCompBasePhysical."', `sCompFactPhysical` = '".$sCompFactPhysical."' WHERE `Arm_workplace`.`id` = ".$idRm.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			GroupWork::SetLastChangeStamp(-1, $idRm);
		}
		
		//Чтение Мероприятий
		public static function GetActivityList($idRm)
		{
			$aList = null;
			$sql = "SELECT * FROM `Arm_activity` WHERE `iRmId` = ".$idRm.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				$aList[] = array($vRow['id'], $vRow['sActivityName'],$vRow['sActivityTarget'],$vRow['sTerm'],$vRow['sInvolved'],$vRow['sMark'],$vRow['iType']);
			}
			return $aList;
		}
		
		public static function ReadActivity($id)
		{	
			$sql = "SELECT * FROM `Arm_activity` WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				$aList[] = array($vRow['id'], $vRow['sActivityName'],$vRow['sActivityTarget'],$vRow['sTerm'],$vRow['sInvolved'],$vRow['sMark'],$vRow['iType']);
			}
			return $aList;
		}

		
		//Добавление мероприятий Мероприятий
		public static function AddActivity($idRm, $sActivityName, $sActivityTarget, $sTerm, $sInvolved, $sMark, $iType = 0)
		{
			$sActivityName = mysql_escape_string($sActivityName);
			$sql2 = 'SELECT * FROM Arm_activity WHERE sActivityName LIKE "'.$sActivityName.'" AND iRmId ='.$idRm.';';
			$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
			
			if (mysql_num_rows($vResult2) == 0)
			{
				$sql = "INSERT INTO `Arm_activity` (`id`, `iRmId`, `sActivityName`, `sActivityTarget`, `sTerm`, `sInvolved`, `sMark`, `iType`) VALUES (NULL, '".$idRm."', '".$sActivityName."', '".$sActivityTarget."', '".$sTerm."', '".$sInvolved."', '".$sMark."', '".$iType."');";
				$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				GroupWork::SetLastChangeStamp(-1, $idRm);
				return mysql_insert_id();
			}
		}
		
		//Удаление Мероприятий
		public static function DelActivity($id)
		{
			$sql = "DELETE FROM `Arm_activity` WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		}
		
		//Изменение Мероприятий
		public static function EditActivity($id, $sActivityName, $sActivityTarget, $sTerm, $sInvolved, $sMark='', $iType = 0)
		{
			$sql = "UPDATE `Arm_activity` SET `sActivityName` = '".$sActivityName."', `sActivityTarget` = '".$sActivityTarget."', `sTerm` = '".$sTerm."', `sInvolved` = '".$sInvolved."', `sMark` = '".$sMark."', `iType` = '".$iType."' WHERE `Arm_activity`.`id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			return WorkPlace::ReadActivity($id);
		}
		
		//Изменение СИЗ
		public static function SaveSiz($id, $sSIZbase, $dSizDate, $iSIZCard, $iSIZEffect, $iSIZOFact, $iSIZOProtect, $iSIZOEffect)
		{
			$dSizDate = date_create($dSizDate);						
			$dSizDate = $dSizDate->format('Y-m-d');
			$sql = "UPDATE `Arm_workplace` SET `sSIZbase` = '".$sSIZbase."', `dSizDate` = '".$dSizDate."', `iSIZCard` = '".$iSIZCard."', `iSIZEffect` = '".$iSIZEffect."', `iSIZOFact` = '".$iSIZOFact."', `iSIZOProtect` = '".$iSIZOProtect."', `iSIZOEffect` = '".$iSIZOEffect."' WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			GroupWork::SetLastChangeStamp(-1, $id);
		}
		
		//Чтение Мероприятий
		public static function GetSizList($idRm)
		{
			$aList = null;
			$sql = "SELECT * FROM `Arm_Siz` WHERE `rmId` = ".$idRm." ORDER BY SizName;";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				$aList[] = array($vRow['id'], $vRow['SizName'],$vRow['Fact'],$vRow['Sert'],$vRow['protectFactor']);
			}
			return $aList;
		}
		public static function ReadSiz($id)
		{	
			$sql = "SELECT * FROM `Arm_Siz` WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				$aList[] = array($vRow['id'], $vRow['SizName'],$vRow['Fact'],$vRow['Sert'],$vRow['protectFactor']);
			}
			return $aList;
		}

		
		//Добавление мероприятий Мероприятий
		public static function AddSiz($idRm, $SizName, $Fact, $Sert, $protectFactor)
		{
			if(strpos($SizName, "\n") !== FALSE) {
				//Перечень сизов
				$aSiz = explode("\n",$SizName);
				foreach($aSiz as $vSiz)
				if(!empty($vSiz))
				{
					$sql = "INSERT INTO `Arm_Siz` (`id`, `rmId`, `SizName`, `Fact`, `Sert`, `protectFactor`) VALUES (NULL, '".$idRm."', '".$vSiz."', '".$Fact."', '".$Sert."', '".$protectFactor."');";
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				}
				$LastId = '!List';
			}
			else {
				//Одиночный СИЗ
				$sql = "INSERT INTO `Arm_Siz` (`id`, `rmId`, `SizName`, `Fact`, `Sert`, `protectFactor`) VALUES (NULL, '".$idRm."', '".$SizName."', '".$Fact."', '".$Sert."', '".$protectFactor."');";
				$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				$LastId = mysql_insert_id();
			}
			GroupWork::SetLastChangeStamp(-1, $idRm);
			return $LastId;
		}
		
		//Удаление Мероприятий
		public static function DelSiz($id)
		{
			$sql = "DELETE FROM `Arm_Siz` WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		}
		
		//Изменение Мероприятий
		public static function EditSiz($id, $SizName, $Fact, $Sert, $protectFactor)
		{
			$sql = "UPDATE `Arm_Siz` SET `SizName` = '".$SizName."', `Fact` = '".$Fact."', `Sert` = '".$Sert."', `protectFactor` = '".$protectFactor."' WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			return WorkPlace::ReadSiz($id);
		}

		public static function ImportSiz($idDonor, $idRecepient)
		{
			//Импорт основной информации
			$sql = "SELECT `sSIZbase`, `dSizDate`, `iSIZCard`,`iSIZEffect` ,`iSIZOFact` ,`iSIZOProtect` ,`iSIZOEffect` FROM `Arm_workplace` WHERE `id` = ".$idDonor.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				$sql = "UPDATE `Arm_workplace` SET `sSIZbase` = '".$vRow[sSIZbase]."', `dSizDate` = '".$vRow[dSizDate]."', `iSIZCard` = '".$vRow[iSIZCard]."', `iSIZEffect` = '".$vRow[iSIZEffect]."', `iSIZOFact` = '".$vRow[iSIZOFact]."', `iSIZOProtect` = '".$vRow[iSIZOProtect]."', `iSIZOEffect` = '".$vRow[iSIZOEffect]."' WHERE `Arm_workplace`.`id` = ".$idRecepient.";";
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			}
			//Очистка еречня СИЗ
			$sql = "DELETE FROM `Arm_Siz` WHERE `Arm_Siz`.`rmId` = ".$idRecepient.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			//Импорт перечня сиз
			$sql = "SELECT * FROM `Arm_Siz` WHERE `rmId` = ".$idDonor.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				$sql = "INSERT INTO `Arm_Siz` (`id`, `rmId`, `SizName`, `Fact`, `Sert`, `protectFactor`) VALUES (NULL, '".$idRecepient."', '".$vRow[SizName]."', '".$vRow[Fact]."', '".$vRow[Sert]."', '".$vRow[protectFactor]."');";
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
 				GroupWork::SetLastChangeStamp(-1, $idRecepient);
			}
		}
		
		public static function ImportWaranty($idDonor, $idRecepient)
		{
			//Импорт основной информации
			$sql = "SELECT * FROM `Arm_workplace` WHERE `id` = ".$idDonor.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '".$vRow[iCompSurcharge]."', `sCompBaseSurcharge` = '".$vRow[sCompBaseSurcharge]."', `sCompFactSurcharge` = '".$vRow[sCompFactSurcharge]."', `iCompVacation` = '".$vRow[iCompVacation]."', `sCompBaseVacation` = '".$vRow[sCompBaseVacation]."', `sCompFactVacation` = '".$vRow[sCompFactVacation]."', `iCompShortWorkDay` = '".$vRow[iCompShortWorkDay]."', `sCompBaseShortWorkDay` = '".$vRow[sCompBaseShortWorkDay]."', `sCompFactShortWorkDay` = '".$vRow[sCompFactShortWorkDay]."', `iCompMilk` = '".$vRow[iCompMilk]."', `sCompBaseMilk` = '".$vRow[sCompBaseMilk]."', `sCompFactMilk` = '".$vRow[sCompFactMilk]."', `iCompFood` = '".$vRow[iCompFood]."', `sCompBaseFood` = '".$vRow[sCompBaseFood]."', `sCompFactFood` = '".$vRow[sCompFactFood]."', `iCompPension` = '".$vRow[iCompPension]."', `sCompBasePension` = '".$vRow[sCompBasePension]."', `sCompFactPension` = '".$vRow[sCompFactPension]."', `iCompPhysical` = '".$vRow[iCompPhysical]."', `sCompBasePhysical` = '".$vRow[sCompBasePhysical]."', `sCompFactPhysical` = '".$vRow[sCompFactPhysical]."' WHERE `Arm_workplace`.`id` = ".$idRecepient.";";
				echo($sql);
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
 				GroupWork::SetLastChangeStamp(-1, $idRecepient);
			}
		}
		
		public static function ImportActions($idDonor, $idRecepient)
		{
			//Очистка еречня СИЗ
			$sql = "DELETE FROM `Arm_activity` WHERE `Arm_activity`.`iRmId` = ".$idRecepient.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			//Импорт перечня сиз
			$sql = "SELECT * FROM `Arm_activity` WHERE `iRmId` = ".$idDonor.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				$sql = "INSERT INTO `Arm_activity` (`id`, `iRmId`, `sActivityName`, `sActivityTarget`, `sTerm`, `sInvolved`, `sMark`, `iType`) VALUES (NULL, '".$idRecepient."', '".$vRow[sActivityName]."', '".$vRow[sActivityTarget]."', '".$vRow[sTerm]."', '".$vRow[sInvolved]."', '".$vRow[sMark]."', '".$vRow[iType]."');";
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
 				GroupWork::SetLastChangeStamp(-1, $idRecepient);
			}
		}
		
		//Установка дефолтных дат
		public static function SetAllCreateDate($inIdGroup, $dDateCreate, $dDateControl, $dNewDateSiz)
		{
			//DbConnect::Log("Группа ".$inIdGroup." дата создания: ".$dDateCreate." дата измерения: ".$dDateControl,"debug");
			if(strlen(trim($dDateCreate)) > 0 && $dDateCreate != 'Не изменять')
			{
			$dDateCreate = date_create($dDateCreate);						
			$dDateCreate = $dDateCreate->format('Y-m-d');
			$sql = "UPDATE `Arm_workplace` SET `dCreateDate`='".$dDateCreate."' WHERE `idParent` > -1 AND `idGroup` = ".$inIdGroup.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			}
			//DbConnect::Log("Группа ".$inIdGroup." дата создания: ".$dDateCreate." дата измерения: ".$dDateControl,"debug");
			if(strlen(trim($dDateControl)) > 0 && $dDateControl != 'Не изменять')
			{
			$dDateControl = date_create($dDateControl);						
			$dDateControl = $dDateControl->format('Y-m-d');
			$sql = "UPDATE `Arm_rmFactors`, `Arm_rmPointsRm`, `Arm_workplace` SET `Arm_rmFactors`.`dtControl`='".$dDateControl."' WHERE `Arm_workplace`.`id` = `Arm_rmPointsRm`.`idRm` AND `Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_workplace`.`idParent` > -1 AND `Arm_workplace`.`idGroup` = ".$inIdGroup.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			}
			if(strlen(trim($dNewDateSiz)) > 0 && $dNewDateSiz != 'Не изменять')
			{
			$dNewDateSiz = date_create($dNewDateSiz);						
			$dNewDateSiz = $dNewDateSiz->format('Y-m-d');
			$sql = "UPDATE `Arm_workplace` SET `dSizDate`='".$dNewDateSiz."' WHERE `idParent` > -1 AND `idGroup` = ".$inIdGroup.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			}
		}
		
		//Быстрые пенсии
		public static function FastWarantyPens($idRm, $bToBase = true)
		{
			$sql = 'SELECT * FROM Arm_workplace WHERE id = '.$idRm.';';
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			
			$sReturn = "";
			
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				if ($vRow['iATotal'] > 2)
				{
					$sql2= "SELECT * FROM Nd_ok01694 WHERE sCode = ".$vRow['sOk'].";";
					if (is_numeric($vRow['sOk']))
					{
					$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
					
					while ($vRow2 = mysql_fetch_assoc($vResult2))
					{

							$sReturn = WorkPlace::GetFullNamePens($vRow2['sBasePension']);

						
//						sCompBasePension
//						iCompPension
					}
					}
				}
			}
			
			if ($bToBase)
						{
							$iCP = 0;
							if ($sReturn != '') {$iCP = 1;}
								
								$sql3 = "UPDATE Arm_workplace SET sCompBasePension = '".$sReturn."', iCompPension = ".$iCP." WHERE id = ".$idRm.";";
								UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql3);
				 				GroupWork::SetLastChangeStamp(-1, $idRm);
							
						}
			
			return $sReturn;
		}
		
		//Полное наименование пенсии по id
		public static function GetFullNamePens($id)
		{
			$sResult = '';
		if ($id != '')
		{
				$sql = "SELECT * FROM Nd_pens WHERE id = ".$id.";";
				$result = DbConnect::GetSqlQuery($sql);
				
		
		
		if (mysql_num_rows($result) > 0)
				{
					while($vRow = mysql_fetch_array($result))
					{		
						//$vRow['sNum'][0]				
						$sResult = 'п. '.$vRow['sNum'].', "'.trim($vRow['sName']).'"';
						
						if ($vRow['idParent'] != -1)
						{
							$sql2 = "SELECT * FROM Nd_pens WHERE id = ".$vRow['idParent'].";";
							$result2 = DbConnect::GetSqlQuery($sql2);
							if (mysql_num_rows($result2) > 0)
							{
								while($vRow2 = mysql_fetch_array($result2))
								{
									$sResult = trim($vRow2['sName']).', '.$sResult;
									
								}
							}
						}
						$sHeader = '';
						if ($vRow['sNum'][0] == '1')
						{
							$sHeader = 'Постановление от 26 января 1991 года № 10 "Об утверждении Списков производств, работ, профессий, должностей и показателей, дающих право на льготное пенсионное обеспечение.", Список № 1, ';
						}
						else
						{
							$sHeader = 'Постановление от 26 января 1991 года № 10 "Об утверждении Списков производств, работ, профессий, должностей и показателей, дающих право на льготное пенсионное обеспечение.", Список № 2, ';
						}
						
						$sResult = $sHeader.$sResult;
					}
					$sResult = $sResult.'.';
				}
				else
				{
					$sResult = '';
				}
		}
				return $sResult;
	}
	
		//Быстрые медосмотры
		public static function FastWarantyMed($idRm)
		{	
			$sql = '
			SELECT Arm_rmPointsRm.id, Arm_rmPoints.sName, Arm_rmFactors.sName, Arm_rmFactors.idFactorGroup, Arm_rmFactors.idFactor, Arm_rmFactorsPdu.iAsset FROM Arm_rmPointsRm
			JOIN Arm_rmPoints ON Arm_rmPoints.id = Arm_rmPointsRm.idPoint 
			JOIN Arm_rmFactors ON Arm_rmFactors.idPoint = Arm_rmPointsRm.idPoint 
			JOIN Arm_rmFactorsPdu ON Arm_rmFactorsPdu.idRm = Arm_rmPointsRm.idRm AND Arm_rmFactorsPdu.idFactor = Arm_rmFactors.id 
			WHERE iAsset > 2 AND Arm_rmPointsRm.idRm ='.$idRm;
			
			$result = DbConnect::GetSqlQuery($sql);
			
			$aIdMeds = array();
			
			$sTemp = 'err';
			
				if (mysql_num_rows($result) > 0)
				{
					while($vRow = mysql_fetch_array($result))
					{
							$sql3 = '';
							
							$sTemp = $vRow['idFactorGroup'];
							
							if ($vRow['idFactorGroup'] == '8' || $vRow['idFactorGroup'] == '31')
							{
								$sql3 = 'SELECT idMed FROM Nd_gn1313 WHERE	id = '.$vRow['idFactor'];
							}
							else
							{
								$sql3 = 'SELECT idMed FROM Nd_factors WHERE	id = '.$vRow['idFactor'];
							}
							
							$result3 = DbConnect::GetSqlQuery($sql3);
							while($vRow3 = mysql_fetch_array($result3))
							{	
								array_push($aIdMeds, $vRow3['idMed']);	
							}
					}
				}
				
				
				$sSql = "SELECT iAion FROM Arm_workplace WHERE id = ".$idRm;
				$result2 = DbConnect::GetSqlQuery($sSql);
				if (mysql_num_rows($result2) > 0)
				{
					while($vRow = mysql_fetch_array($result2))
					{
						if ($vRow['iAion'] > 2) { array_push($aIdMeds, '186');}
					}
				}
				
					if (count($aIdMeds) > 0)
					{
						$sResult = WorkPlace::GetFullNameMed(implode(',',$aIdMeds));
					}
								$iCP = 0;
								if ($sResult != '') {$iCP = 1;}
					
								$sql3 = "UPDATE Arm_workplace SET sCompBasePhysical = '".$sResult."', iCompPhysical = ".$iCP." WHERE id = ".$idRm.";";
								UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql3);
				 				GroupWork::SetLastChangeStamp(-1, $idRm);					
			return $sResult;
		}
	
	
		//Полное наименование медосмотров по id (
		public static function GetFullNameMed($idMed1 = '', $idMed2 = '')
		{
			if ($idMed1 != '')
			{
                $bFirst = true;
				$sql = "SELECT * FROM Nd_med1 WHERE id IN (".$idMed1.");";
				$result = DbConnect::GetSqlQuery($sql);
				while($vRow = mysql_fetch_array($result))
					{
                        if($bFirst)
                        {
                            $sResult = $sResult.' Приложение №1';
                            $bFirst = false;
                        }
						$sResult = $sResult.' п.п. '.$vRow['sPunkt'].';';
					}
				
			}
			
			if ($idMed2 != '')
			{
                $bFirst = true;
				$sql2 = "SELECT * FROM Nd_med2 WHERE id IN (".$idMed2.");";
				$result2 = DbConnect::GetSqlQuery($sql2);
				while($vRow2 = mysql_fetch_array($result2))
					{
                        if($bFirst)
                        {
                            $sResult = $sResult.' Приложение №2';
                            $bFirst = false;
                        }
                        $sResult = $sResult.' п.п. '.$vRow2['sPunkt'].';';
					}
			}
			
            if(!empty($sResult)) $sResult = 'Приказ Минздравсоцразвития РФ №302н от 12.04.2011г.'.$sResult;

			if ($idMed1 == '' && $idMed2 == '')
			{
				$sResult = '';
			}
			
			return $sResult;
		}
		
		//Быстрые мероприятия
		public static function FastActions($idRm)
		{
			$sql = "SELECT `Arm_rmFactors`.*, `Arm_rmFactorsPdu`.`iAsset`, (`Arm_rmFactorsPdu`.`id`) AS `PduId`, `Arm_rmPointsRm`.`sTime`, `Arm_rmFactorsPdu`.`sAddonAsset`, (`Arm_rmPoints`.`sName`) AS `PointName`, `Arm_rmPoints`.`iType` FROM `Arm_rmFactorsPdu` LEFT JOIN `Arm_rmFactors` ON `Arm_rmFactorsPdu`.`idFactor` = `Arm_rmFactors`.`id` LEFT JOIN `Arm_rmPointsRm` ON (`Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_rmFactorsPdu`.`idRm`) LEFT JOIN `Arm_rmPoints` ON (`Arm_rmPoints`.`id` = `Arm_rmFactors`.`idPoint`) WHERE `Arm_rmFactorsPdu`.`idRm` = ".$idRm." AND `Arm_rmFactorsPdu`.`iAsset` > 2;";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			
			//Получение даты по умолчанию
			$sql = "SELECT `Arm_workplace`.`dCreateDate`, `Arm_workplace`.`iSIZEffect`, `Arm_workplace`.`iSIZOFact`, `Arm_workplace`.`iSIZOProtect`, `Arm_workplace`.`sOk`, `Nd_ok01694`.`sNoChild`, `Nd_ok01694`.`sNoWoman` FROM `Arm_workplace` LEFT JOIN `Nd_ok01694` ON (`Nd_ok01694`.`sCode` LIKE `Arm_workplace`.`sOk`) WHERE `Arm_workplace`.`id` = ".$idRm.";";
			$createCardDate = DbConnect::GetSqlRow($sql);
			$oneYearOn = date('Y-m-d',strtotime($createCardDate[dCreateDate] . " + 365 day"));
			
			//Мероприятия по СИЗ
			if($createCardDate[iSIZEffect] == 0 || $createCardDate[iSIZOFact] == 0 || $createCardDate[iSIZOProtect] == 0)
			{
				WorkPlace::AddActivity($idRm, 'Обеспечение работников сертифицированными средствами индивидуальной защиты в соответствии с типовыми нормами.', 'Средства индивидуальной защиты — повышение эффективности защиты работника.', '', 'Все структурные подразделения.', '', 0);
			}
			
			//Применение труда лиц не достигших 18 лет - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 163, п. 2111 "Водитель автомобиля")
			//echo ($createCardDate[sNoChild]);
			if(strlen($createCardDate[sNoChild])>0){WorkPlace::AddActivity($idRm, 'Применение труда лиц не достигших 18 лет - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 163, '.$createCardDate[sNoChild].').', '', '', '', '', 1);}
			if(strlen($createCardDate[sNoWoman])>0){WorkPlace::AddActivity($idRm, 'Применение труда женщин - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 162, '.$createCardDate[sNoWoman].').', '', '', '', '', 1);}
			
			//Перебор выборки по факторам.
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				$sMero = '';
				switch($vRow[idFactorGroup])
				{
					//Климат
					case 1: $sMero = 'Устройство новых и реконструкция имеющихся отопительных и вентиляционных систем в производственных и бытовых помещениях, тепловых и воздушных завесс целью обеспечения нормального теплового режима и микроклимата, в соответствии с требованиями нормативных документов.'; break;
					//АПФД
					case 8: $sMero = 'Устройство новых и реконструкция имеющихся аспирационных и пылегазоулавливающих установок с целью обеспечения чистоты воздушной среды в рабочей и обслуживаемых зонах помещений в соответствии с требованиями  нормативных документов.'; break;
					//Виброакустические факторы
					case 10: $sMero = 'Снижение до регламентированных уровней неблагоприятно действующих механических колебаний (шум, вибрация, ультразвук и др.) на рабочих местах в соответствии с требованиями нормативных документов.'; break;
					//Световая среда
					case 17: $sMero = 'Приведение естественного и искусственного освещения на рабочих местах, в цехах, бытовых помещениях, местах массового перехода людей, на территории к нормам в соответствии с требованиями нормативных документов.'; break;
					//Неионизирующие излучения
					case 21: $sMero = 'Снижение до регламентированных уровней неионизирующих излучений (электромагнитного, лазерного, ультрафиолетового и др.) на рабочих местах в соответствии с требованиями нормативных документов.'; break;
					//Ионизирующие излучения
					case 28: $sMero = 'Снижение до регламентированных уровней ионизирующих излучений на рабочих местах в соответствии с требованиями нормативных документов.'; break;
					//Химический фактор
					case 31: $sMero = 'Устройство новых и реконструкция имеющихся аспирационных и пылегазоулавливающих установок с целью обеспечения чистоты воздушной среды в рабочей и обслуживаемых зонах помещений в соответствии с требованиями  нормативных документов.'; break;
					//Биологический фактор
					case 33: $sMero = ''; break;
					//Тяжесть трудового процесса
					case 37: $sMero = ''; break;
					//Напряженность трудового процесса
					case 46: $sMero = ''; break;
					//Общее
					default: $sMero = ''; break;
				}
				
				//Внесение мероприятий			
				if(strlen($sMero)>0)
				WorkPlace::AddActivity($idRm, $sMero, $vRow[PointName] .' — снижение вредного воздействия производственного фактора "'.$vRow['sName'].'".', '', 'Все структурные подразделения.', '', 0);
			}

			//Мероприятие на ионизирующее
			$sSql = "SELECT iAion FROM Arm_workplace WHERE id = ".$idRm;
			$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
			while ($vRow = mysql_fetch_assoc($vResult2))
			{
			   if ($vRow[iAion] > 2) {WorkPlace::AddActivity($idRm, 'Снижение до регламентированных уровней ионизирующих излучений на рабочих местах в соответствии с требованиями нормативных документов.', 'Cнижение вредного воздействия производственного фактора "Ионизирующие излучение".', '', 'Все структурные подразделения.', '', 0);}
			}

            //Мероприятие на неверное именование рабочего места
            $sSql = "SELECT `sName`, `sOk` FROM `Arm_workplace` WHERE `id` = $idRm";
            $vResult1 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
			while ($vRow1 = mysql_fetch_assoc($vResult1))
			{
                $aRMData = $vRow1;

                if(strlen($vRow1[sOk]) > 5)
                $vRow1[sOk] = substr($vRow1[sOk], 0, 5);
                //Мероприятие на неверное именование рабочего места
                $sSql = "SELECT Nd_ok01694.`sCode`, Nd_ok01694.`sName`, Nd_Etks.sName as sNameEtks FROM `Nd_ok01694` LEFT JOIN Nd_Etks ON Nd_Etks.iCode = Nd_ok01694.sEtks WHERE `sCode` = $vRow1[sOk]";

                $vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
                while ($vRow2 = mysql_fetch_assoc($vResult2))
                {
                    $aOkData = $vRow2;
                }

                if($aRMData[sOk] && $aOkData[sCode])
                {
                    if(trim($aRMData[sName]) != trim($aOkData[sName]))
                    {
                        $sMero = "Привести наименование должности професси к ОК 016-94, ЕТКС/КС: $aOkData[sCode] - $aOkData[sName] ($aOkData[sNameEtks]).";
                        WorkPlace::AddActivity($idRm, $sMero, 'Приведение штатного расписания в соответсвие с ОК 016-94, ЕТКС/КС.', '', 'Отдел кадров.', '', 0);
                    }
                }
            }
		}
		
		//Быстрые компенсации
		public static function FastWarranty($idRm)
		{
			WorkPlace::FastWarantyPens($idRm);
			WorkPlace::FastWarantyMed($idRm);
			
			$sql = "SELECT `iATotal` FROM `Arm_workplace` WHERE `id` = ".$idRm.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				switch($vRow[iATotal])
				{
					case '1':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '0', `sCompBaseSurcharge` = 'Отсутствует.', `iCompVacation` = '0', `sCompBaseVacation` = 'Отсутствует.', `iCompShortWorkDay` = 0, `sCompBaseShortWorkDay` = 'Отсутствует.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
					case '2':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '0', `sCompBaseSurcharge` = 'Отсутствует.', `iCompVacation` = '0', `sCompBaseVacation` = 'Отсутствует.', `iCompShortWorkDay` = 0, `sCompBaseShortWorkDay` = 'Отсутствует.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
					case '3':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '1', `sCompBaseSurcharge` = 'ТК РФ, часть III, глава 21, ст. 147.', `iCompVacation` = '0', `sCompBaseVacation` = 'Отсутствует.', `iCompShortWorkDay` = 0, `sCompBaseShortWorkDay` = 'Отсутствует.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
					case '4':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '1', `sCompBaseSurcharge` = 'ТК РФ, часть III, глава 21, ст. 147.', `iCompVacation` = '1', `sCompBaseVacation` = 'ТК РФ, часть III, глава 19, ст. 117.', `iCompShortWorkDay` = 0, `sCompBaseShortWorkDay` = 'Отсутствует.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
					case '5':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '1', `sCompBaseSurcharge` = 'ТК РФ, часть III, глава 21, ст. 147.', `iCompVacation` = '1', `sCompBaseVacation` = 'ТК РФ, часть III, глава 19, ст. 117.', `iCompShortWorkDay` = 1, `sCompBaseShortWorkDay` = 'ТК РФ, часть III, глава 15, ст. 92.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
					case '6':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '1', `sCompBaseSurcharge` = 'ТК РФ, часть III, глава 21, ст. 147.', `iCompVacation` = '1', `sCompBaseVacation` = 'ТК РФ, часть III, глава 19, ст. 117.', `iCompShortWorkDay` = 1, `sCompBaseShortWorkDay` = 'ТК РФ, часть III, глава 15, ст. 92.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
					case '7':
$sql = "UPDATE `Arm_workplace` SET `iCompSurcharge` = '1', `sCompBaseSurcharge` = 'ТК РФ, часть III, глава 21, ст. 147.', `iCompVacation` = '1', `sCompBaseVacation` = 'ТК РФ, часть III, глава 19, ст. 117.', `iCompShortWorkDay` = 1, `sCompBaseShortWorkDay` = 'ТК РФ, часть III, глава 15, ст. 92.' WHERE `Arm_workplace`.`id` = ".$idRm.";";
					break;
				}
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			}
		}
		
		//Установка мероприятий для всех рабочих мест
		public static function SetAllCreateAction($inIdGroup)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_workplace WHERE idGroup = '.$inIdGroup.' AND idParent <> -1 ORDER BY iNumber DESC;');
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				WorkPlace::FastActions($vRow[id]);
			}
		}
		//Усановка гарантий и компенсаций
		public static function SetAllCreateWarranty($inIdGroup)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_workplace WHERE idGroup = '.$inIdGroup.' AND idParent <> -1 ORDER BY iNumber DESC;');
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				WorkPlace::FastWarranty($vRow[id]);

			}
		}
		//Автонумерация
		public static function SetAllNums($inIdGroup, $sFirstNum)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT `id` FROM Arm_workplace WHERE idGroup = '.$inIdGroup.' AND idParent <> -1 ORDER BY idParent,iNumber;');
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				DbConnect::GetSqlQuery("UPDATE `Arm_workplace` SET `iNumber` = ".$sFirstNum." WHERE `id` = ".$vRow[id].";");
				$sFirstNum++;
			}
			GroupWork::SetLastChangeStamp($inIdGroup);
		}
	}
?>

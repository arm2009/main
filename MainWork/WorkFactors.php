<?php
	include_once('LowLevel/userValidator.php');
	include_once('UserControl/userControl.php');
	include_once('MainWork/GroupWork.php');
	include_once('Util/String.php');
	include_once('MainWork/Tools/NoiseCalc.php');

	class WorkFactors
	{
		//Проверка на превышение времени
		public static function GetTime($idRm)
		{
			$sResult = '';
			$iTotalTime = 0;
			$iZonesTime = 0;
      $iDevicesTime = 0;
      $iMaterialTime = 0;
			$sql = "SELECT fWorkDay FROM Arm_workplace WHERE id =".$idRm.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while($vRow = mysql_fetch_assoc($vResult))
			{
			    $iTotalTime = $vRow[fWorkDay];
			}

            //З
			$sql2 = "SELECT SUM(sTime) as sTime FROM Arm_rmPointsRm JOIN Arm_rmPoints ON Arm_rmPoints.id = Arm_rmPointsRm.idPoint WHERE idRm =".$idRm." AND Arm_rmPoints.iType = 0;";
			$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
			while($vRow2 = mysql_fetch_assoc($vResult2))
			{
				if ($vRow2[sTime] != '')
				{
					$iZonesTime = $iZonesTime + $vRow2[sTime];
				}
			}

            //О
			$sql2 = "SELECT SUM(sTime) as sTime FROM Arm_rmPointsRm JOIN Arm_rmPoints ON Arm_rmPoints.id = Arm_rmPointsRm.idPoint WHERE idRm =".$idRm." AND Arm_rmPoints.iType = 1;";
			$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
			while($vRow2 = mysql_fetch_assoc($vResult2))
			{
				if ($vRow2[sTime] != '')
				{
					$iDevicesTime = $iDevicesTime + $vRow2[sTime];
				}
			}

            //М
			$sql2 = "SELECT SUM(sTime) as sTime FROM Arm_rmPointsRm JOIN Arm_rmPoints ON Arm_rmPoints.id = Arm_rmPointsRm.idPoint WHERE idRm =".$idRm." AND Arm_rmPoints.iType = 2;";
			$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
			while($vRow2 = mysql_fetch_assoc($vResult2))
			{
				if ($vRow2[sTime] != '')
				{
					$iMaterialTime = $iMaterialTime + $vRow2[sTime];
				}
			}



			if ($iTotalTime < $iZonesTime || $iTotalTime > $iZonesTime)
			{
                if(!empty($sResult)) $sResult .= '<br>';
				$sResult .= 'Время прибывания в зонах: '.$iZonesTime.'ч.';
			}

            if ($iTotalTime < $iDevicesTime)
			{
                if(!empty($sResult)) $sResult .= '<br>';
				$sResult .= 'Работа с инструментом / оборудованием: '.$iDevicesTime.'ч.';
			}

            if ($iTotalTime < $iMaterialTime)
			{
                if(!empty($sResult)) $sResult .= '<br>';
				$sResult .= 'Работа с сырьем и материалами: '.$iMaterialTime.'ч.';
			}

            if(!empty($sResult)) $sResult = 'Обратите внимание, время воздействия источников вредных факторов не соответствует продолжительности смены ('.$iTotalTime.'ч.).<br>'.$sResult;

			return $sResult;
		}

		//Чтение точек измерения
		public static function GetPointsList($idRm)
		{
			$aList = null;
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT Arm_rmPoints.id as id, Arm_rmPoints.sName as sName, Arm_rmPointsRm.sTime as sTime, Arm_rmPoints.iType FROM Arm_rmPoints, Arm_rmPointsRm WHERE Arm_rmPoints.id = Arm_rmPointsRm.idPoint AND Arm_rmPointsRm.idRm = '.$idRm.' ORDER BY Arm_rmPoints.iType, Arm_rmPoints.sName;');
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				$aList[] = array($vRow['id'], $vRow['idRm'],$vRow['sName'], $vRow['sTime'], $vRow['iType']);
			}
			return $aList;
		}

		//Чтение параметров одной точки
		public static function GetPoint($idPoint, $idRm)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT Arm_rmPoints.id as id, Arm_rmPoints.sName as sName, Arm_rmPointsRm.sTime as sTime, Arm_rmPoints.iType as iType FROM Arm_rmPoints, Arm_rmPointsRm WHERE Arm_rmPoints.id = Arm_rmPointsRm.idPoint AND Arm_rmPointsRm.idRm = '.$idRm.' AND Arm_rmPoints.id = '.$idPoint.' ORDER BY `Arm_rmPoints`.`sName`;');
			$result = array();
			$result['sName'] = mysql_result($vResult, 0,1);
			$result['sTime'] = mysql_result($vResult, 0,2);
			$result['iType'] = mysql_result($vResult, 0,3);
			return $result;
		}

		//Запись параметров одной точки
		public static function EditPoint($idRm, $idPoint, $sName, $sTime, $iType)
		{

			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_rmPoints, Arm_rmPointsRm SET Arm_rmPoints.sName ="'.$sName.'", Arm_rmPointsRm.sTime = "'.str_replace(",", ".",$sTime).'", Arm_rmPoints.iType ="'.$iType.'" WHERE Arm_rmPoints.id = Arm_rmPointsRm.idPoint AND Arm_rmPointsRm.idRm = '.$idRm.' AND Arm_rmPoints.id = '.$idPoint.';');

			//Установка оценок рабочего места
			WorkFactors::SetAssetRm($idRm);
		}

		//Запись дополнительных параметров по фактору
		public static function EditPointAddLight($idFactor, $sLightPolygone, $sLightHeight, $sLightDark, $sLightType)
		{
			$sql = "SELECT `idPoint` FROM `Arm_rmFactors` WHERE `id` = ".$idFactor.";";
			$idPoint = DbConnect::GetSqlCell($sql);
			$sql = "UPDATE `kctrud_arm2009`.`Arm_rmPoints` SET `sLightPolygone` = '".$sLightPolygone."', `sLightHeight` = '".$sLightHeight."', `sLightDark` = '".$sLightDark."', `sLightType` = '".$sLightType."' WHERE `Arm_rmPoints`.`id` = ".$idPoint.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		}

		//Запись параметров одного фактора
		public static function EditFactor($inIdFactor, $inIdRm, $fFact1, $fPdu1, $dControl, $fFact2, $fPdu2, $fFact3, $fPdu3, $fFact4, $fPdu4, $fFact5, $fPdu5)
		{
			//Изменение факта
			$tmpSetFact = "";
			if($fFact1 > -1) {$tmpSetFact .="`var1` = '".str_replace(",", ".", $fFact1)."'";}
			if($fFact2 > -1) {$tmpSetFact .="`var2` = '".str_replace(",", ".", $fFact2)."'";}
			if($fFact3 > -1) {$tmpSetFact .="`var3` = '".str_replace(",", ".", $fFact3)."'";}
			if($fFact4 > -1) {$tmpSetFact .="`var4` = '".str_replace(",", ".", $fFact4)."'";}
			if($fFact5 > -1) {$tmpSetFact .="`var5` = '".str_replace(",", ".", $fFact5)."'";}
			$tmpSetFact = str_replace("'`", "', `", $tmpSetFact);
			if(strlen(trim($tmpSetFact)) > 0) {$tmpSetFact .=', ';}
			$Date = new DateTime($dControl);
			$sql = "UPDATE `Arm_rmFactors` SET ".$tmpSetFact."`dtControl` = '".$Date->format('Y.m.d')."' WHERE `Arm_rmFactors`.`id` = ".$inIdFactor.";";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);

			//Изменение нормы
			$tmpSetFact = "";
			if($fPdu1 > -1) {$tmpSetFact .="`fPdu1` = '".str_replace(",", ".", $fPdu1)."'";}
			if($fPdu2 > -1) {$tmpSetFact .="`fPdu2` = '".str_replace(",", ".", $fPdu2)."'";}
			if($fPdu3 > -1) {$tmpSetFact .="`fPdu3` = '".str_replace(",", ".", $fPdu3)."'";}
			if($fPdu4 > -1) {$tmpSetFact .="`fPdu4` = '".str_replace(",", ".", $fPdu4)."'";}
			if($fPdu5 > -1) {$tmpSetFact .="`fPdu5` = '".str_replace(",", ".", $fPdu5)."'";}
			$tmpSetFact = str_replace("'`", "', `", $tmpSetFact);
			if(strlen(trim($tmpSetFact)) > 0)
			{
				$sql = "UPDATE `kctrud_arm2009`.`Arm_rmFactorsPdu` SET ".$tmpSetFact." WHERE `Arm_rmFactorsPdu`.`idFactor` = ".$inIdFactor." AND `Arm_rmFactorsPdu`.`idRm` = ".$inIdRm.";";
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			}

			//Установка оценок всех затронутых факторов
			$sql = "SELECT `idRm` FROM `Arm_rmFactorsPdu` WHERE `idFactor` = ".$inIdFactor.";";
			$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			//Перерасчет оценок
			while ($vRow = mysql_fetch_assoc($vReturn))
			{
				WorkFactors::SetAssetRm($vRow[idRm]);
			}

			return WorkFactors::ReadFactor($inIdFactor, $inIdRm);
		}

		//Добавление точки
		public static function AddPoint($idRm, $sName, $idGroup, $sTime, $iType)
		{
			//Проверка на существование этой точки
			$vReturn1 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT Arm_rmPoints.id, Arm_rmPoints.sName FROM Arm_rmPoints, Arm_rmPointsRm, Arm_workplace WHERE Arm_workplace.id = Arm_rmPointsRm.idRm AND Arm_rmPoints.id = Arm_rmPointsRm.idPoint AND Arm_rmPoints.sName = "'.$sName.'" AND Arm_workplace.idGroup = '.$idGroup.';');
			if (mysql_num_rows($vReturn1) > 0)
			{
				//Если точка существует
				$idPoint = mysql_result($vReturn1, 0, 0);
			}
			else
			{
				//Если точки не существует, создаем
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_rmPoints (sName, iType, sLightPolygone, sLightHeight, sLightDark, sLightType) VALUES ("'.$sName.'", '.$iType.', "Г-0,8", "2,5", "0", "ЛЛ");');
				$idPoint = mysql_insert_id();
			}

			//Проверка на существование этой точки для текущего рабочего места
			$vReturn1 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_rmPointsRm WHERE idRm = '.$idRm.' AND idPoint = "'.$idPoint.'";');
			if (mysql_num_rows($vReturn1) > 0)
			{
				//Если в этом рабочем месте точка существует, возвращаем ошибку
				return -1;
			}
			else
			{
				//Если точка не существует для данного рабочего места, добавляем
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_rmPointsRm (idRm, idPoint, sTime) VALUES ('.$idRm.',"'.$idPoint.'","'.$sTime.'");');

				//Если у этой точки уже есть факторы нужно проставить дефолтные значения норматива для этого рабочего места
				$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint.";";
				$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				while ($vRow = mysql_fetch_assoc($vReturn))
				{
					WorkFactors::SetDefaultAsset($idRm, $vRow[id]);
				}
				return $idPoint;
			}

			//Перерасчет оценок
			WorkFactors::SetAssetRm($idRm);
		}

		//Удаление точки
		public static function DelPoint($idPoint, $idRm)
		{
				//Удаление точки текущего рабочего места
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_rmPointsRm WHERE idPoint = '.$idPoint.' AND idRm = '.$idRm.';');
				//Удаление нормативов для текущего места
				$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint.";";
				$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				while ($vRow = mysql_fetch_assoc($vResult))
				{
					$sql = "DELETE FROM `Arm_rmFactorsPdu` WHERE `idRm` = ".$idRm." AND `idFactor` = ".$vRow[id].";";
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				}

				//Проверка на полное удаление точки измерений и полное удаление точки измерений
				$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_rmPointsRm WHERE idPoint = '.$idPoint.';');
				if (mysql_num_rows($vResult) == 0)
				{
					$sql = "SELECT `id` FROM `Arm_rmFactors` WHERE `idPoint` = ".$idPoint.";";
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
					while ($vRow = mysql_fetch_assoc($vResult))
					{
						WorkFactors::DelFactor($vRow[id]);
					}
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_rmPoints WHERE id = '.$idPoint.';');
				}

				//Перерасчет оценок
				WorkFactors::SetAssetRm($idRm);
		}

		public static function ChangeFactors($id, $sField, $sValue)
		{
				$sValue = DbConnect::ToBaseStr($sValue);
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_rmFactors SET '.$sField.' = "'.$sValue.'" WHERE id = '.$id.';');
		}
		public static function ChangePdu($id, $sField, $sValue)
		{
				$sValue = DbConnect::ToBaseStr($sValue);
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_rmFactorsPdu SET '.$sField.' = "'.$sValue.'" WHERE id = '.$id.';');
		}

		//Чтение факторов
		public static function GetFactorsList($idPoint, $idRm, $bFullData = false)
		{
			$aList = null;

			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT `Arm_rmFactors`.*, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, `Arm_rmFactorsPdu`.`iAsset`, `Arm_rmFactorsPdu`.`sAddonAsset` FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor` AND `Arm_rmFactorsPdu`.`idRm` = '.$idRm.') WHERE `idPoint` = '.$idPoint.' ORDER BY `Arm_rmFactors`.`idFactorGroup`, `Arm_rmFactors`.`idFactor` ;');

			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				//$vRow['id']																						- 0
				//$vRow['idPoint']																			- 1
				//$vRow['sName']																				- 2
				//$vRow['var1']																					- 3
				//StringWork::StrToDateFormatLite($vRow['dtControl']),	- 4
				//$vRow['fPdu1']																				- 5
				//StringWork::iToClassNameLite($vRow['iAsset'])					- 6
				//$vRow['var2']																					- 7
				//$vRow['var3']																					- 8
				//$vRow['var4']																					- 9
				//$vRow['var5']																					- 10
				//$vRow['fPdu2']																				- 11
				//$vRow['fPdu3']																				- 12
				//$vRow['fPdu4']																				- 13
				//$vRow['fPdu5']																				- 14
				//$vRow[idFactor]																				- 15
				//$vRow[idFactorGroup]																	- 16
				//$vRow[sAddonAsset]																		- 17
				//$vRow['iAsset']																				- 18
				//$vRow['dtControl']																		- 19
				//if($bFullData)
				$aList[] = array($vRow['id'], $vRow['idPoint'],$vRow['sName'],$vRow['var1'],StringWork::StrToDateFormatLite($vRow['dtControl']),$vRow['fPdu1'],StringWork::iToClassNameLite($vRow['iAsset']), $vRow['var2'],$vRow['var3'],$vRow['var4'],$vRow['var5'],$vRow['fPdu2'],$vRow['fPdu3'],$vRow['fPdu4'],$vRow['fPdu5'],$vRow[idFactor],$vRow[idFactorGroup],$vRow[sAddonAsset],$vRow['iAsset'],$vRow['dtControl']);
			}
			return $aList;
		}

		public static function ReadFactor($idFactor, $idRm)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(),
			'SELECT `Arm_rmFactors`.*, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`,
			`Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`,
			`Arm_rmFactorsPdu`.`iAsset`, `Arm_rmFactorsPdu`.`sAddonAsset`, `Arm_rmPoints`.`sLightPolygone`,
			`Arm_rmPoints`.`sLightHeight`, `Arm_rmPoints`.`sLightDark`, `Arm_rmPoints`.`sLightType`
			FROM `Arm_rmFactors` LEFT JOIN `Arm_rmFactorsPdu` ON (`Arm_rmFactors`.`id` = `Arm_rmFactorsPdu`.`idFactor` AND `Arm_rmFactorsPdu`.`idRm` = '.$idRm.') LEFT JOIN `Arm_rmPoints` ON (`Arm_rmFactors`.`idPoint` = `Arm_rmPoints`.`id`) WHERE `Arm_rmFactors`.`id` = '.$idFactor.';');
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				$aList = array($vRow['id'], $vRow['idPoint'],$vRow['sName'],$vRow['var1'],
				StringWork::StrToDateFormatLite($vRow['dtControl']),$vRow['fPdu1'],
				StringWork::iToClassNameLite($vRow['iAsset']), $vRow['var2'],$vRow['var3'],$vRow['var4'],$vRow['var5'],$vRow['fPdu2'],$vRow['fPdu3'],$vRow['fPdu4'],$vRow['fPdu5'],$vRow[idFactor],$vRow[idFactorGroup],$vRow[sAddonAsset],$vRow[sLightPolygone],$vRow[sLightHeight],$vRow[sLightDark],$vRow[sLightType]);//18,19,20,21
			}
			return $aList;
		}

		//Добавление фактора
		public static function AddFactor($idPoint, $sIdFactor, $sType = 'class', $idRm)
		{
			if ($sType == 'class')
			{
				$vResult1 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, idParent FROM Nd_factors WHERE id ='.$sIdFactor.';');
				$idGroup = mysql_result($vResult1, 0, 1);
				$sPdu1 = '0.0';
			}
			else
			{
				$vResult1 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, sFeat,sPdk, sFeatCode FROM Nd_gn1313 WHERE id ='.$sIdFactor.';');
				$sPdu1 = mysql_result($vResult1, 0, 2);
				$sFeat = mysql_result($vResult1, 0, 1);
				//echo ($sFeat.':'.strpos($sFeat,'Ф'));
				if (strpos($sFeat,"Ф") > -1 || strpos($sFeat,"ф") > -1)
				{
					$idGroup = 8;
				}
				else
				{
					$idGroup = 31;
				}
			}

			$sName = mysql_result($vResult1, 0, 0);
			if($sIdFactor != '13')
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_rmFactors (idPoint, sName, idFactor, idFactorGroup, dtControl) VALUES ('.$idPoint.',"'.$sName.'", '.$sIdFactor.','.$idGroup.', NOW());');
			else
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_rmFactors (idPoint, sName, idFactor, idFactorGroup, dtControl, var5) VALUES ('.$idPoint.',"'.$sName.'", '.$sIdFactor.','.$idGroup.', NOW(), 2);');
			$iFactorsId = mysql_insert_id();

			//Проставление дефолтных нормативов для всех рабочих мест где появилась эта точка
			$sql = "SELECT `idRm` FROM `Arm_rmPointsRm` WHERE `idPoint` = ".$idPoint.";";
			$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vReturn))
			{
				WorkFactors::SetDefaultAsset($vRow[idRm], $iFactorsId);
				WorkFactors::SetAssetRm($vRow[idRm]);
			}

			//Возвращаем информацию по вновь созданной точке
			return WorkFactors::ReadFactor($iFactorsId, $idRm);
		}

		//Удаление фактора
		public static function DelFactor($id)
		{
			//Получение списка рабочих мест где присутствовал этот фактор
			$sql = "SELECT `idRm` FROM `Arm_rmFactorsPdu` WHERE `idFactor` = ".$id.";";
			$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);

			//Удаление фактора
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_rmFactors WHERE id ='.$id.';');
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_rmFactorsPdu WHERE `idFactor` = '.$id.';');

			//Перерасчет оценок
			while ($vRow = mysql_fetch_assoc($vReturn))
			{
				WorkFactors::SetAssetRm($vRow[idRm]);
			}
		}

		//Установка базового норматива и оценки
		public static function SetDefaultAsset($idRm, $idFactors)
		{
			//Значение по умолчанию
			$sPdu1 = 0;$sPdu2 = 0;$sPdu3 = 0;$sPdu4 = 0;$sPdu5 = 0;$sAddonAsset = '';

			//Получение типа фактора
			$sql = "SELECT `idFactor`, `idFactorGroup` FROM `Arm_rmFactors` WHERE `id` = ".$idFactors.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			$idFactor = mysql_result($vResult, 0, 0);
			$idFactorGroup = mysql_result($vResult, 0, 1);

			//Подбор норматива
			switch ($idFactorGroup)
			{
				//Химия / АПФД
				case 8:
				case 31:
					$sql = "SELECT `fMM`, `fSS`, `sFeat`, `sFeatCode` FROM `Nd_gn1313` WHERE `id` = ".$idFactor.";";
					$gnRow = DbConnect::GetSqlRow($sql);
					$sPdu1 = $gnRow[fMM];
					$sPdu1 = str_replace(',','.',$sPdu1);
					$sPdu2 = $gnRow[fSS];
					$sPdu2 = str_replace(',','.',$sPdu2);
					$sAddonAsset = $gnRow[sFeatCode];
				break;
				default:
					//Физика
					switch($idFactor)
					{
						case 7:
							$sPdu1 = 140;
						break;
						case 55:
							$sPdu1 = 500;
						break;
						case 13:
							$sPdu1 = 80;
						break;
						case 14:
							$sPdu1 = 110;
						break;
						case 16:
							$sPdu1 = 112;
							$sPdu2 = 112;
							$sPdu3 = 115;
						break;
						case 54:
							$sPdu1 = 126;
							$sPdu2 = 126;
							$sPdu3 = 126;
						break;
						case 22:
							$sPdu1 = 5;
							$sPdu2 = 80;
						break;
						case 24:
							$sPdu1 = 60;
						break;
						case 25:
							$sPdu1 = 8000;
						break;
						case 26:
							$sPdu1 = 50;
							$sPdu2 = 0.05;
							$sPdu3 = 0.001;
						break;
						case 29:
							$sPdu1 = 5;
						break;
						case 61:
							$sPdu1 = 37.5;
						break;
						case 62:
							$sPdu1 = 125;
						break;
						case 45:
							$sPdu1 = 8;
							$sPdu2 = 2.5;
						break;
						case 43:
							$sPdu1 = 100;
						break;
						case 41:
							$sPdu1 = 40000;
							$sPdu1 = 20000;
						break;
					}
				break;
			}

			$sql = "INSERT INTO `Arm_rmFactorsPdu` (`idRm`, `idFactor`, `fPdu1`, `fPdu2`, `fPdu3`, `fPdu4`, `fPdu5`, `iAsset`, `sAddonAsset`) VALUES (".$idRm.", ".$idFactors.", '".$sPdu1."', '".$sPdu2."', '".$sPdu3."', '".$sPdu4."', '".$sPdu5."', '0', '".$sAddonAsset."');";
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		}

		//Оценки!!!
		//=====================================================================================================================================
		//Получить оценки для фактора
		public static function GetFactorAsset($idRm, $idFactors, $isReturnString = false)
		{
			$sql = "SELECT `iAsset` FROM `Arm_rmFactorsPdu` WHERE `idRm` = ".$idRm." AND `idFactor` = ".$idFactors.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			$iAsset = mysql_result($vResult, 0, 0);
			if($isReturnString)
			return StringWork::iToClassNameLite($iAsset);
			else
			return $iAsset;
		}
		//Расчитать оценки для группы данных
		public static function SetAllAsset($inIdGroup)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_workplace WHERE idGroup = '.$inIdGroup.' AND idParent <> -1 ORDER BY iNumber DESC;');
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				WorkFactors::SetAssetRm($vRow[id]);
			}
		}
		//Расчитать оценки для рабочего места
		public static function SetAssetRm($idRm)
		{
			//Подготовка дефолтных переменных
			$arrAssets = array('iAChem' => 0, 'iABio' => 0, 'iAAPFD' => 0, 'iANoise' => 0, 'iAInfraNoise' => 0, 'iAUltraNoise' => 0, 'iAVibroO' => 0, 'iAVibroL' => 0, 'iANoIon' => 0, 'iAIon' => 0, 'iAMicroclimat' => 0, 'iALight' => 0, 'iAHeavy' => 0, 'iATennese' => 0, 'iATotal' => 2);

			//Массивы шума, инфразвука, ультразвука, вибрации
			$aNoiseLevel = array();
			$aNoiseLevel2 = array();
			$aNoiseLevel3 = array();
			$aNoiseTime = array();
			$aInfraNoiseLevel = array();
			$aInfraNoiseTime = array();
			$aUltraNoiseLevel = array();
			$aVibroOLevelX = array();
			$aVibroOLevelY = array();
			$aVibroOLevelZ = array();
			$aVibroOTime = array();
			$aVibroLLevelX = array();
			$aVibroLLevelY = array();
			$aVibroLLevelZ = array();
			$aVibroLTime = array();
			$aMicroclimatPointId = array();
			$aMicroclimatMaxAsset = array();
			$aMicroclimatTime = array();
			$aNoIonAsset = array();
			$aLightPointId = array();
			$aLightMaxAsset = array();
			$aLightTime = array();
			$iHeavyMaxAssetW = 0;
			$iHeavyMaxAssetM = 0;

			$iIonVarDose = 0;
			$iIonVarEye = 0;
			$iIonVarSkin = 0;

			$aHeavyTotal = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
			$aHeavyTotalM = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
			$aHeavyTotalW = array(11=>0,12=>0,13=>0,21=>0,22=>0,23=>0,24=>0,31=>0,32=>0,41=>0,42=>0,43=>0,51=>0,61=>0,71=>0,72=>0);
			$bHeavy = false;

			$aTenneseTotal = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
			$aTenneseTotalAll = array(1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);
			$bTennese = false;
			$bTennese5 = false;

			//Массивы однонправленных действий химии
			$aChem = array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array());
			$aChemSs = array(array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array(), array());

			//Получение продолжительности рабочего дня, параметров рабочего места
			$sql = "SELECT `fWorkDay`, `iCount`, `iCountWoman` FROM `Arm_workplace` WHERE `id` = ".$idRm.";";
			$tmpRow = DbConnect::GetSqlRow($sql);
			$fWorkDay = $tmpRow[fWorkDay];
			$WorkCount = $tmpRow[iCount];
			$WorkCountW = $tmpRow[iCountWoman];

			//Выбор всех факторов для рабочего места
			$sql = "SELECT `Arm_rmFactors`.*, `Arm_rmFactorsPdu`.`fPdu1`, `Arm_rmFactorsPdu`.`fPdu2`, `Arm_rmFactorsPdu`.`fPdu3`, `Arm_rmFactorsPdu`.`fPdu4`, `Arm_rmFactorsPdu`.`fPdu5`, (`Arm_rmFactorsPdu`.`id`) AS `PduId`, `Arm_rmPointsRm`.`sTime`, `Arm_rmFactorsPdu`.`sAddonAsset` FROM `Arm_rmFactorsPdu` LEFT JOIN `Arm_rmFactors` ON `Arm_rmFactorsPdu`.`idFactor` = `Arm_rmFactors`.`id` LEFT JOIN `Arm_rmPointsRm` ON (`Arm_rmPointsRm`.`idPoint` = `Arm_rmFactors`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_rmFactorsPdu`.`idRm`) WHERE `Arm_rmFactorsPdu`.`idRm` = ".$idRm.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			$dSuspNoise = 0;
			//Перебор выборки
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Перебор по факторам
				if($vRow[idFactorGroup] != 8 && $vRow[idFactorGroup] != 31)
				{
					switch($vRow[idFactor])
					{
						//Температура воздуха, 0С
						case 2:
							$tmpAsset = WorkFactors::GetFactorAsset_Temperature($vRow[var1], $vRow[fPdu1]);

							//Промежуточные значения микроклимата
							$tmpArrSearc = array_search($vRow[idPoint], $aMicroclimatPointId);
							if($tmpArrSearc > -1)
							{
								$aMicroclimatMaxAsset[$tmpArrSearc] = max($aMicroclimatMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aMicroclimatPointId, $vRow[idPoint]);
								array_push($aMicroclimatTime, $vRow[sTime]);
								array_push($aMicroclimatMaxAsset, $tmpAsset);
							}
						break;
						//Относительная влажность воздуха, %
						case 5:
							$tmpAsset = WorkFactors::GetFactorAsset_AirDry($vRow[var1]);
							//Промежуточные значения микроклимата
							$tmpArrSearc = array_search($vRow[idPoint], $aMicroclimatPointId);
							if($tmpArrSearc > -1)
							{
								$aMicroclimatMaxAsset[$tmpArrSearc] = max($aMicroclimatMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aMicroclimatPointId, $vRow[idPoint]);
								array_push($aMicroclimatTime, $vRow[sTime]);
								array_push($aMicroclimatMaxAsset, $tmpAsset);
							}
						break;
						//Cкорость движения воздуха, м/с
						case 6:
							$tmpAsset = WorkFactors::GetFactorAsset_AirSpeed($vRow[var1], $vRow[fPdu1]);
							//Промежуточные значения микроклимата
							$tmpArrSearc = array_search($vRow[idPoint], $aMicroclimatPointId);
							if($tmpArrSearc > -1)
							{
								$aMicroclimatMaxAsset[$tmpArrSearc] = max($aMicroclimatMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aMicroclimatPointId, $vRow[idPoint]);
								array_push($aMicroclimatTime, $vRow[sTime]);
								array_push($aMicroclimatMaxAsset, $tmpAsset);
							}
						break;
						//Тепловое излучение, Вт/м3
						case 7:
							$tmpAsset = WorkFactors::GetFactorAsset_HeartRay($vRow[var1]);
							//Промежуточные значения микроклимата
							$tmpArrSearc = array_search($vRow[idPoint], $aMicroclimatPointId);
							if($tmpArrSearc > -1)
							{
								$aMicroclimatMaxAsset[$tmpArrSearc] = max($aMicroclimatMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aMicroclimatPointId, $vRow[idPoint]);
								array_push($aMicroclimatTime, $vRow[sTime]);
								array_push($aMicroclimatMaxAsset, $tmpAsset);
							}
						break;
						//Экспозиционная доза теплового облучения, Вт*ч
						case 55:
							$tmpAsset = WorkFactors::GetFactorAsset_HeartExpDose($vRow[var1]);
							//Промежуточные значения микроклимата
							$tmpArrSearc = array_search($vRow[idPoint], $aMicroclimatPointId);
							if($tmpArrSearc > -1)
							{
								$aMicroclimatMaxAsset[$tmpArrSearc] = max($aMicroclimatMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aMicroclimatPointId, $vRow[idPoint]);
								array_push($aMicroclimatTime, $vRow[sTime]);
								array_push($aMicroclimatMaxAsset, $tmpAsset);
							}
						break;
						//ТНС - индекс, 0С
						case 56:
							$tmpAsset = WorkFactors::GetFactorAsset_TNSIndex($vRow[var1], $vRow[fPdu1]);
							//Промежуточные значения микроклимата
							$tmpArrSearc = array_search($vRow[idPoint], $aMicroclimatPointId);
							if($tmpArrSearc > -1)
							{
								$aMicroclimatMaxAsset[$tmpArrSearc] = max($aMicroclimatMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aMicroclimatPointId, $vRow[idPoint]);
								array_push($aMicroclimatTime, $vRow[sTime]);
								array_push($aMicroclimatMaxAsset, $tmpAsset);
							}
						break;
						//Шум, дБа
						case 13:
							array_push($aNoiseLevel, $vRow[var1]);
							array_push($aNoiseLevel2, $vRow[var2]);
							array_push($aNoiseLevel3, $vRow[var3]);
							array_push($aNoiseTime, $vRow[sTime]);
							$tmpAsset = WorkFactors::GetFactorAsset_Noise(NoiseCalc::tmpEqual($vRow[var1],$vRow[var2],$vRow[var3]));
						break;
						//Инфразвук, дБЛин
						case 14:
							array_push($aInfraNoiseLevel, $vRow[var1]);
							array_push($aInfraNoiseTime, $vRow[sTime]);
							$tmpAsset = WorkFactors::GetFactorAsset_InfraNoise($vRow[var1]);
						break;
						//Ультразвук воздушный, дБ
						case 15:
							$tmpAsset = WorkFactors::GetFactorAsset_UltraNoise($vRow[var1], $vRow[fPdu1]);
							array_push($aUltraNoiseLevel, $tmpAsset);
						break;
						//Общая вибрация, дБ
						case 16:
							array_push($aVibroOLevelX, $vRow[var1]);
							array_push($aVibroOLevelY, $vRow[var2]);
							array_push($aVibroOLevelZ, $vRow[var3]);
							array_push($aVibroOTime, $vRow[sTime]);
							$tmpAssetX = WorkFactors::GetFactorAsset_TotalVibro($vRow[var1], 'X');
							$tmpAssetY = WorkFactors::GetFactorAsset_TotalVibro($vRow[var2], 'Y');
							$tmpAssetZ = WorkFactors::GetFactorAsset_TotalVibro($vRow[var3], 'Z');
							$tmpAsset = max($tmpAssetX, $tmpAssetY, $tmpAssetZ);
						break;
						//Локальная вибрация, дБ
						case 54:
							array_push($aVibroLLevelX, $vRow[var1]);
							array_push($aVibroLLevelY, $vRow[var2]);
							array_push($aVibroLLevelZ, $vRow[var3]);
							array_push($aVibroLTime, $vRow[sTime]);
							$tmpAssetX = WorkFactors::GetFactorAsset_LocalVibro($vRow[var1]);
							$tmpAssetY = WorkFactors::GetFactorAsset_LocalVibro($vRow[var2]);
							$tmpAssetZ = WorkFactors::GetFactorAsset_LocalVibro($vRow[var3]);
							$tmpAsset = max($tmpAssetX, $tmpAssetY, $tmpAssetZ);
						break;
						//Освещенность рабочей поверхности, лк
						case 18:
							$tmpAsset1 = WorkFactors::GetFactorAsset_Light($vRow[var1], $vRow[fPdu1]);
							$tmpAsset2 = WorkFactors::GetFactorAsset_Light($vRow[var2], $vRow[fPdu2]);
							$tmpAsset = max($tmpAsset1, $tmpAsset2);
							//Промежуточные значения света
							$tmpArrSearc = array_search($vRow[idPoint], $aLightPointId);
							if($tmpArrSearc > -1)
							{
								$aLightMaxAsset[$tmpArrSearc] = max($aLightMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aLightPointId, $vRow[idPoint]);
								array_push($aLightTime, $vRow[sTime]);
								array_push($aLightMaxAsset, $tmpAsset);
							}
						break;
						//Прямая блесткость, Отраженная блесткость
						case 19:
						case 20:
							$tmpAsset = WorkFactors::GetFactorAsset_LightAddon($vRow[var1]);
							//Промежуточные значения света
							$tmpArrSearc = array_search($vRow[idPoint], $aLightPointId);
							if($tmpArrSearc > -1)
							{
								$aLightMaxAsset[$tmpArrSearc] = max($aLightMaxAsset[$tmpArrSearc], $tmpAsset);
							}
							else
							{
								array_push($aLightPointId, $vRow[idPoint]);
								array_push($aLightTime, $vRow[sTime]);
								array_push($aLightMaxAsset, $tmpAsset);
							}
						break;
						//Переменное электромагнитное поле (промышленная частота 50 Гц), Э кВ/м, М А/м
						case 22:
							DbConnect::Log($vRow[PduId].' /  / '.WorkFactors::GetFactorPdu_EMI50($vRow[sTime]), "debug", UserControl::GetUserLoginId());
							WorkFactors::ChangePdu($vRow[PduId], 'fPdu2', WorkFactors::GetFactorPdu_EMI50($vRow[sTime]));
							$tmpAsset = WorkFactors::GetFactorAsset_EMI50($vRow[var1], $vRow[var2], $vRow[fPdu1], WorkFactors::GetFactorPdu_EMI50($vRow[sTime]));
							array_push($aNoIonAsset, $tmpAsset);
						break;
						//Переменное электромагнитное поле радиочастотного диапазона?, (В/м)2·ч, (А/м)2·ч
						case 23:
						case 57:
							$tmpAsset = WorkFactors::GetFactorAsset_EMIRCLow($vRow[var1], $vRow[fPdu1]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						case 58:
							$tmpAsset = WorkFactors::GetFactorAsset_EMIRCMed($vRow[var1], $vRow[fPdu1]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						case 59:
						case 60:
							$tmpAsset = WorkFactors::GetFactorAsset_EMIRCHi($vRow[var1], $vRow[fPdu1]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						//Электростатическое поле, кВ/м
						case 24:
							$tmpAsset = WorkFactors::GetFactorAsset_EMIESP($vRow[var1]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						//Постоянное магнитное поле, А/м
						case 25:
							$tmpAsset = WorkFactors::GetFactorAsset_EMIPMP($vRow[var1]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						//Ультрафиолетовое излучение, Вт/м2
						case 26:
							$tmpAsset = WorkFactors::GetFactorAsset_EMIUF($vRow[var1],$vRow[var2],$vRow[var3]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						//Лазерное излучение
						case 27:
							$tmpAsset = WorkFactors::GetFactorAsset_EMILaser($vRow[var1], $vRow[fPdu1]);
							array_push($aNoIonAsset, $tmpAsset);
						break;
						//Рентгеновское, гамма- и нейтронное излучение - эффективная доза, мзв/год
						case 29:
							$iIonVarDose = $iIonVarDose + $vRow[var1];
						break;
						//Рентгеновское, гамма- и нейтронное излучение - эквивалентная доза в хрусталике глаза, мзв/год
						case 61:
							$iIonVarEye = $iIonVarEye + $vRow[var1];
						break;
						//Рентгеновское, гамма- и нейтронное излучение - эффективная доза в коже, кистях и стопах, мзв/год
						case 62:
							$iIonVarSkin = $iIonVarSkin + $vRow[var1];
						break;
						//Радиоактивное загрязнение производственных помещений, элементов производственного оборудования, средств индивидуальной
						case 30:
							$tmpAsset = 0;
							$arrAssets[iAIon] = max($arrAssets[iAIon], $tmpAsset);
						break;
						//Микроорганизмы-продуценты, живые клетки и споры, содержащиеся в бактериальных  препаратах
						case 34:
							//FIXME:Ошибочный расчет перепроверить
							//$tmpAsset = WorkFactors::GetFactorAsset_BIO($vRow[var1], $vRow[fPdu1]);
							//$arrAssets[iABio] = max($arrAssets[iABio], $tmpAsset);
						break;
						//Патогенные микроорганизмы - возбудители особо опасных инфекционных заболеваний
						case 35:
							$tmpAsset = 7;
							$arrAssets[iABio] = max($arrAssets[iABio], $tmpAsset);
						break;
						//Патогенные микроорганизмы - возбудители высококонтрагиозных эпидемических заболеваний
						case 36:
							$tmpAsset = 5;
							$arrAssets[iABio] = max($arrAssets[iABio], $tmpAsset);
						break;
						//Патогенные микроорганизмы - возбудители инфекционных болезней, выделяемые в самостоятельные нозологические группы
						case 63:
							$tmpAsset = 4;
							$arrAssets[iABio] = max($arrAssets[iABio], $tmpAsset);
						break;
						//Патогенные микроорганизмы - условно-патогенные микробы (возбудители оппортунистических инфекций)
						case 64:
							$tmpAsset = 3;
							$arrAssets[iABio] = max($arrAssets[iABio], $tmpAsset);
						break;
						//Физическая динамическая нагрузка
						case 39:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyFD($vRow[var1], $vRow[var2], $vRow[var3]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[11] += $vRow[var1];
							$aHeavyTotal[12] += $vRow[var2];
							$aHeavyTotal[13] += $vRow[var3];
						break;
						//Масса поднимаемого и перемещаемого груза вручную
						case 40:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPiP($vRow[var1], $vRow[var2], $vRow[var3], $vRow[var4]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[21] = max($vRow[var1], $aHeavyTotal[21]);
							$aHeavyTotal[22] = max($vRow[var2], $aHeavyTotal[22]);
							$aHeavyTotal[23] += $vRow[var3];
							$aHeavyTotal[24] += $vRow[var4];
						break;
						//Стереотипные рабочие движения
						case 41:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySD($vRow[var1], $vRow[var2]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[31] += $vRow[var1];
							$aHeavyTotal[32] += $vRow[var2];
						break;
						//Статическая нагрузка
						case 42:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySN($vRow[var1], $vRow[var2], $vRow[var3]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[41] += $vRow[var1];
							$aHeavyTotal[42] += $vRow[var2];
							$aHeavyTotal[43] += $vRow[var3];
						break;
						//Рабочая поза
						case 43:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyRP($vRow[var1]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[51] = max($vRow[var1], $aHeavyTotal[51]);
						break;
						//Наклоны корпуса тела работника
						case 44:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyNK($vRow[var1]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[61] += $vRow[var1];
						break;
						//Перемещение в пространстве
						case 45:
							$bHeavy = true;
							$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPP($vRow[var1], $vRow[var2]);
							WorkFactors::ChangePdu($vRow[PduId], 'sAddonAsset', $tmpAddonAsset);
							$tmparr = explode(',', $tmpAddonAsset);
							$tmpAsset = max($tmparr);
							//Общая тяжесть
							$aHeavyTotal[71] += $vRow[var1];
							$aHeavyTotal[72] += $vRow[var2];
						break;
						//Плотность сигналов (световых, звуковых) и сообщений в единицу времени
						case 48:
							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_PS($vRow[var1]);
							//Общая напряженность
							$bTennese = true;
							$aTenneseTotal[1] = max($vRow[var1], $aTenneseTotal[1]);
						break;
						//Число производственных объектов одновременного набл юдения
						case 49:
							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_OC($vRow[var1]);
							//Общая напряженность
							$bTennese = true;
							$aTenneseTotal[2] = max($vRow[var1], $aTenneseTotal[2]);
						break;
						//Работа с оптическими приборами
						case 52:
							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_OP($vRow[var1]);
							//Общая напряженность
							$bTennese = true;
							$aTenneseTotal[3] += $vRow[var1];
						break;
						//Нагрузка на голосовой аппарат
						case 53:
							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_GA($vRow[var1]);
							//Общая напряженность
							$bTennese = true;
							$aTenneseTotal[4] += $vRow[var1];
						break;
						//Число элементов (приемов), необходимых для реализации простого задания или многократно повторяющихся операций
						case 65:
							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_PO($vRow[var1]);
							//Общая напряженность
							$bTennese = true;
							$aTenneseTotal[5] = min($vRow[var1], $aTenneseTotal[5]);
						break;
						//Монотонность производственной обстановки (время пассивного наблюдения за ходом технологического процесса в % от времени смены)
						case 66:
							$tmpAsset = WorkFactors::GetFactorAsset_Tennese_MO($vRow[var1]);
							//Общая напряженность
							$bTennese = true;
							$aTenneseTotal[6] += $vRow[var1];
						break;
						//По умолчанию
						default:
							$tmpAsset = 0;
						break;
					}
					WorkFactors::ChangePdu($vRow[PduId], 'iAsset', $tmpAsset);
				}
				else
				{
					//АПФД
					if($vRow[idFactorGroup] == 8)
					{
						if($vRow[fPdu1] == -1) $tmpP1 = $vRow[fPdu2]; else $tmpP1 = $vRow[fPdu1];
						if($vRow[fPdu2] != -1) $tmpP1 = min($tmpP1, $vRow[fPdu2]);

						if($tmpP1 <= 2)
						{
							$tmpAsset = WorkFactors::GetFactorAsset_APFD_Hi($vRow[var1],$vRow[var2],$vRow[fPdu1],$vRow[fPdu2]);
						}
						else
						{
							$tmpAsset = WorkFactors::GetFactorAsset_APFD_Low($vRow[var1],$vRow[var2],$vRow[fPdu1],$vRow[fPdu2]);
						}

						WorkFactors::ChangePdu($vRow[PduId], 'iAsset', $tmpAsset);
						$arrAssets[iAAPFD] = max($arrAssets[iAAPFD], $tmpAsset);

						//Заполнение массивов для суммации массивы нужно предварительно создать.
						$retChem = Summ_Add_Chem($vRow[sAddonAsset], $vRow[var1], $vRow[var2], $vRow[fPdu1], $vRow[fPdu2], $aChem, $aChemSs);
						$aChem = $retChem[0]; $aChemSs = $retChem[1];
					}
					//Химия
					if($vRow[idFactorGroup] == 31)
					{
						$tmpAssetAll = 0; $tmpAssetOO = 0; $tmpAssetK = 0; $tmpAssetA = 0; $tmpAssetP = 0; $tmpAssetNA = 0; $tmpAssetFMP = 0;

						$tmpAssetAll = WorkFactors::GetFactorAsset_Chem_All($vRow[var1],$vRow[var2],$vRow[fPdu1],$vRow[fPdu2]);
						if(strpos($vRow[sAddonAsset], 'О') > -1 || strpos($vRow[sAddonAsset], 'r') > -1)
						$tmpAssetOO = max(WorkFactors::GetFactorAsset_Chem_OO($vRow[var1],$vRow[var2],$vRow[fPdu1],$vRow[fPdu2]),$tmpAssetOO);
						if(strpos($vRow[sAddonAsset], 'a') > -1)
						$tmpAssetOO = max(WorkFactors::GetFactorAsset_Chem_OR($vRow[var1],$vRow[var2],$vRow[fPdu1],$vRow[fPdu2]),$tmpAssetOO);
						if(strpos($vRow[sAddonAsset], 'К') > -1 || strpos($vRow[sAddonAsset], 'e') > -1)
						$tmpAssetK = WorkFactors::GetFactorAsset_Chem_K($vRow[var2],$vRow[fPdu2]);
						if(strpos($vRow[sAddonAsset], 'А') > -1 || strpos($vRow[sAddonAsset], 'b') > -1)
						$tmpAssetA = WorkFactors::GetFactorAsset_Chem_A($vRow[var1],$vRow[var2],$vRow[fPdu1],$vRow[fPdu2]);
						if(strpos($vRow[sAddonAsset], 'П') > -1 || strpos($vRow[sAddonAsset], 's') > -1)
						$tmpAssetP = 6;
						if(strpos($vRow[sAddonAsset], 'НА') > -1 || strpos($vRow[sAddonAsset], 't') > -1)
						$tmpAssetNA = 4;
						if(strpos($vRow[sAddonAsset], 'ФМП') > -1 || strpos($vRow[sAddonAsset], 'g') > -1)
						$tmpAssetFMP = WorkFactors::GetFactorAsset_Chem_FMP($vRow[var1],$vRow[fPdu1]);
						$tmpAsset = max($tmpAssetAll, $tmpAssetOO, $tmpAssetK, $tmpAssetA, $tmpAssetP, $tmpAssetNA, $tmpAssetFMP);
						WorkFactors::ChangePdu($vRow[PduId], 'iAsset', $tmpAsset);
						$arrAssets[iAChem] = max($arrAssets[iAChem], $tmpAsset);

						//Заполнение массивов для суммации массивы нужно предварительно создать.
						$retChem = Summ_Add_Chem($vRow[sAddonAsset], $vRow[var1], $vRow[var2], $vRow[fPdu1], $vRow[fPdu2], $aChem, $aChemSs);
						$aChem = $retChem[0]; $aChemSs = $retChem[1];
					}
				}
			}

			//Функция конечной оценки суммации, возвращает массив из четырех массивов ($aSummMr,$aSumMrAss,$aSummSs,$aSumSsAss)
			//В каждом массиве 17 значений. $aSummMr,$aSummSs - значения суммаций, $aSumMrAss,$aSumSsAss - оценки суммаций.
			$aChemSumm = Summ_Chem_Ass($aChem, $aChemSs);
			$arrAssets[iAAPFD] = max($arrAssets[iAAPFD], $aChemSumm[1][3], $aChemSumm[3][3]);
			$arrAssets[iAChem] = max($arrAssets[iAChem], max($aChemSumm[1][0], $aChemSumm[3][0]), max($aChemSumm[1][1], $aChemSumm[3][1]), max($aChemSumm[1][2], $aChemSumm[3][2]), max($aChemSumm[1][4], $aChemSumm[3][4]), max($aChemSumm[1][5], $aChemSumm[3][5]), max($aChemSumm[1][6], $aChemSumm[3][6]), max($aChemSumm[1][7], $aChemSumm[3][7]), max($aChemSumm[1][8], $aChemSumm[3][8]), max($aChemSumm[1][9], $aChemSumm[3][9]), max($aChemSumm[1][10], $aChemSumm[3][10]), max($aChemSumm[1][11], $aChemSumm[3][11]), max($aChemSumm[1][12], $aChemSumm[3][12]), max($aChemSumm[1][13], $aChemSumm[3][13]), max($aChemSumm[1][14], $aChemSumm[3][14]), max($aChemSumm[1][15], $aChemSumm[3][15]), max($aChemSumm[1][16], $aChemSumm[3][16]));

			//Оценка виброаккустики
			$dEqNoise = 0;
			$dEqInfraNoise = 0;
			$dEqVibroOX = 0;
			$dEqVibroOY = 0;
			$dEqVibroOZ = 0;
			$dEqVibroLX = 0;
			$dEqVibroLY = 0;
			$dEqVibroLZ = 0;
			if(count($aNoiseLevel)>0)
			{
				$result = NoiseEql($aNoiseLevel, $aNoiseTime, $fWorkDay, true, $aNoiseLevel2, $aNoiseLevel3);
				 $dEqNoise = $result[0];

				 $dSuspNoise = $result[1];

				$arrAssets[iANoise] = WorkFactors::GetFactorAsset_Noise($result[0]);
				//echo($arrAssets[iANoise].':'.$result[0]);
			}
			if(count($aInfraNoiseLevel)>0)
			{
				$dEqInfraNoise = NoiseEql($aInfraNoiseLevel, $aInfraNoiseTime, $fWorkDay);
				$arrAssets[iAInfraNoise] = WorkFactors::GetFactorAsset_InfraNoise($dEqInfraNoise);
			}
			if(count($aUltraNoiseLevel)>0) $arrAssets[iAUltraNoise] = max($aUltraNoiseLevel);
			if(count($aVibroOLevelX)>0)
			{
				$dEqVibroOX = NoiseEql($aVibroOLevelX, $aVibroOTime, $fWorkDay);
				$dEqVibroOY = NoiseEql($aVibroOLevelY, $aVibroOTime, $fWorkDay);
				$dEqVibroOZ = NoiseEql($aVibroOLevelZ, $aVibroOTime, $fWorkDay);
				$arrAssets[iAVibroO] = max(WorkFactors::GetFactorAsset_TotalVibro($dEqVibroOX,'X'),WorkFactors::GetFactorAsset_TotalVibro($dEqVibroOY,'Y'),WorkFactors::GetFactorAsset_TotalVibro($dEqVibroOZ,'Z'));
			}
			if(count($aVibroLLevelX)>0)
			{
				$dEqVibroLX = NoiseEql($aVibroLLevelX, $aVibroLTime, $fWorkDay);
				$dEqVibroLY = NoiseEql($aVibroLLevelY, $aVibroLTime, $fWorkDay);
				$dEqVibroLZ = NoiseEql($aVibroLLevelZ, $aVibroLTime, $fWorkDay);
				$arrAssets[iAVibroL] = max(WorkFactors::GetFactorAsset_LocalVibro($dEqVibroLX),WorkFactors::GetFactorAsset_LocalVibro($dEqVibroLY),WorkFactors::GetFactorAsset_LocalVibro($dEqVibroLZ));
			}

			//Оценка микроклимата
			if(count($aMicroclimatTime)>0) $arrAssets[iAMicroclimat] = SrMicroclimat($aMicroclimatTime, $aMicroclimatMaxAsset, $fWorkDay);

			//Неионизирующее излучение
			if(count($aNoIonAsset)>0) $arrAssets[iANoIon] = max($aNoIonAsset);
			$iA3 = 0;$iA4 = 0;$iA5 = 0;$iA6 = 0;
			foreach ($aNoIonAsset as $value)
			{
				switch ($value)
				{
					case 3:$iA3++;break;
					case 4:$iA4++;break;
					case 5:$iA5++;break;
					case 6:$iA6++;break;
				}
			}
			if($iA3 > 1) {$arrAssets[iANoIon] = max($arrAssets[iANoIon], 4);}
			if($iA4 > 1) {$arrAssets[iANoIon] = max($arrAssets[iANoIon], 5);}
			if($iA5 > 1) {$arrAssets[iANoIon] = max($arrAssets[iANoIon], 6);}
			if($iA6 > 1) {$arrAssets[iANoIon] = max($arrAssets[iANoIon], 7);}

			//Оценка осещенности
			if(count($aLightTime)>0) $arrAssets[iALight] = SrLight($aLightTime, $aLightMaxAsset, $fWorkDay);

			//Новая напряженность
			if($bTennese || $bTennese5)
			{
				$aTenneseTotalAll[1] = WorkFactors::GetFactorAsset_Tennese_PS($aTenneseTotal[1]);
				$aTenneseTotalAll[2] = WorkFactors::GetFactorAsset_Tennese_OC($aTenneseTotal[2]);
				$aTenneseTotalAll[3] = WorkFactors::GetFactorAsset_Tennese_OP($aTenneseTotal[3]);
				$aTenneseTotalAll[4] = WorkFactors::GetFactorAsset_Tennese_GA($aTenneseTotal[4]);
				if($bTennese5)
				$aTenneseTotalAll[5] = WorkFactors::GetFactorAsset_Tennese_PO($aTenneseTotal[5]);
				$aTenneseTotalAll[6] = WorkFactors::GetFactorAsset_Tennese_MO($aTenneseTotal[6]);
				//DbConnect::Log($aTenneseTotalAll[1].' / '.$aTenneseTotalAll[2].' / '.$aTenneseTotalAll[3].' / '.$aTenneseTotalAll[4].' / '.$aTenneseTotalAll[5].' / '.$aTenneseTotalAll[6],'debug');
				$arrAssets[iATennese] = max($aTenneseTotalAll);
			}

			//Новая тяжесть
			if($bHeavy)
			{
				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyFD($aHeavyTotal[11], $aHeavyTotal[12], $aHeavyTotal[13]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[11] = $tmparr[0];
				$aHeavyTotalM[12] = $tmparr[1];
				$aHeavyTotalM[13] = $tmparr[2];
				$aHeavyTotalW[11] = $tmparr[3];
				$aHeavyTotalW[12] = $tmparr[4];
				$aHeavyTotalW[13] = $tmparr[5];

				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPiP($aHeavyTotal[21], $aHeavyTotal[22], $aHeavyTotal[23], $aHeavyTotal[24]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[21] = $tmparr[0];
				$aHeavyTotalM[22] = $tmparr[1];
				$aHeavyTotalM[23] = $tmparr[2];
				$aHeavyTotalM[24] = $tmparr[3];
				$aHeavyTotalW[21] = $tmparr[4];
				$aHeavyTotalW[22] = $tmparr[5];
				$aHeavyTotalW[23] = $tmparr[6];
				$aHeavyTotalW[24] = $tmparr[7];

				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySD($aHeavyTotal[31], $aHeavyTotal[32]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[31] = $tmparr[0];
				$aHeavyTotalM[32] = $tmparr[1];
				$aHeavyTotalW[31] = $tmparr[0];
				$aHeavyTotalW[32] = $tmparr[1];

				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavySN($aHeavyTotal[41], $aHeavyTotal[42], $aHeavyTotal[43]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[41] = $tmparr[0];
				$aHeavyTotalM[42] = $tmparr[1];
				$aHeavyTotalM[43] = $tmparr[2];
				$aHeavyTotalW[41] = $tmparr[3];
				$aHeavyTotalW[42] = $tmparr[4];
				$aHeavyTotalW[43] = $tmparr[5];

				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyRP($aHeavyTotal[51]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[51] = $tmparr[0];
				$aHeavyTotalW[51] = $tmparr[0];

				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyNK($aHeavyTotal[61]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[61] = $tmparr[0];
				$aHeavyTotalW[61] = $tmparr[0];

				$tmpAddonAsset = WorkFactors::GetFactorAsset_HeavyPP($aHeavyTotal[71], $aHeavyTotal[72]);
				$tmparr = explode(',', $tmpAddonAsset);
				$aHeavyTotalM[71] = $tmparr[0];
				$aHeavyTotalM[72] = $tmparr[1];
				$aHeavyTotalW[71] = $tmparr[0];
				$aHeavyTotalW[72] = $tmparr[1];
			}

			if(count($aHeavyTotalM)>0) $iHeavyMaxAssetM = max($aHeavyTotalM);
			$iA3 = 0;$iA4 = 0;
			foreach ($aHeavyTotalM as $value)
			{
				switch ($value)
				{
					case 3:$iA3++;break;
					case 4:$iA4++;break;
				}
			}
			if($iA3 > 1) {$iHeavyMaxAssetM = max($iHeavyMaxAssetM, 4);}
			if($iA4 > 1) {$iHeavyMaxAssetM = max($iHeavyMaxAssetM, 5);}

			if(count($aHeavyTotalW)>0) $iHeavyMaxAssetW = max($aHeavyTotalW);
			$iA3 = 0;$iA4 = 0;
			foreach ($aHeavyTotalW as $value)
			{
				switch ($value)
				{
					case 3:$iA3++;break;
					case 4:$iA4++;break;
				}
			}
			if($iA3 > 1) {$iHeavyMaxAssetW = max($iHeavyMaxAssetW, 4);}
			if($iA4 > 1) {$iHeavyMaxAssetW = max($iHeavyMaxAssetW, 5);}

			if($WorkCountW == 0) $arrAssets[iAHeavy] = $iHeavyMaxAssetM; else
			if(($WorkCount - $WorkCountW) == 0) $arrAssets[iAHeavy] = $iHeavyMaxAssetW; else
			$arrAssets[iAHeavy] = max($iHeavyMaxAssetW, $iHeavyMaxAssetM);

			$arrAssets[iAIon] = 0;

			if ($iIonVarDose != 0)	{ $sAssetIonDose = WorkFactors::GetFactorAsset_IONDose($iIonVarDose);	$arrAssets[iAIon] = max($arrAssets[iAIon], $sAssetIonDose); }
			if ($iIonVarEye != 0)	{ $sAssetIonEye = WorkFactors::GetFactorAsset_IONEye($iIonVarEye); $arrAssets[iAIon] = max($arrAssets[iAIon], $sAssetIonEye);}
			if ($iIonVarSkin != 0)	{ $sAssetIonSkin = WorkFactors::GetFactorAsset_IONSkin($iIonVarSkin); $arrAssets[iAIon] = max($arrAssets[iAIon], $sAssetIonSkin);}

			//Общая оценка
			$iA3 = 0;$iA4 = 0;$iA5 = 0;$iA6 = 0;
			foreach ($arrAssets as $value)
			{
				switch ($value)
				{
					case 3:$iA3++;break;
					case 4:$iA4++;break;
					case 5:$iA5++;break;
					case 6:$iA6++;break;
				}
			}
			$arrAssets[iATotal] = max($arrAssets);
			if($iA3 > 2) {$arrAssets[iATotal] = max($arrAssets[iATotal], 4);}
			if($iA4 > 1) {$arrAssets[iATotal] = max($arrAssets[iATotal], 5);}
			if($iA5 > 1) {$arrAssets[iATotal] = max($arrAssets[iATotal], 6);}
			if($iA6 > 1) {$arrAssets[iATotal] = max($arrAssets[iATotal], 7);}

			//Сохранение оценок
			$sql = "UPDATE `kctrud_arm2009`.`Arm_workplace` SET `iAChem` = '".$arrAssets[iAChem]."',
			`iABio` = '".$arrAssets[iABio]."', `iAAPFD` = '".$arrAssets[iAAPFD]."',
			`iANoise` = '".$arrAssets[iANoise]."', `iAInfraNoise` = '".$arrAssets[iAInfraNoise]."',
			`iAUltraNoise` = '".$arrAssets[iAUltraNoise]."', `iAVibroO` = '".$arrAssets[iAVibroO]."',
			`iAVibroL` = '".$arrAssets[iAVibroL]."', `iANoIon` = '".$arrAssets[iANoIon]."',
			`iAIon` = '".$arrAssets[iAIon]."', `iAMicroclimat` = '".$arrAssets[iAMicroclimat]."',
			`iALight` = '".$arrAssets[iALight]."', `iAHeavy` = '".$arrAssets[iAHeavy]."',
			`iAHeavyW` = '".$iHeavyMaxAssetW."', `iAHeavyM` = '".$iHeavyMaxAssetM."',
			`iATennese` = '".$arrAssets[iATennese]."', `iATotal` = '".$arrAssets[iATotal]."',
			`dEqNoise` = '".$dEqNoise."', `dEqInfraNoise` = '".$dEqInfraNoise."',
			`dSuspNoise` = '".$dSuspNoise."'
			, `dEqVibroOX` = '".$dEqVibroOX."', `dEqVibroOY` = '".$dEqVibroOY."', `dEqVibroOZ` = '".$dEqVibroOZ."'
			, `dEqVibroLX` = '".$dEqVibroLX."', `dEqVibroLY` = '".$dEqVibroLY."', `dEqVibroLZ` = '".$dEqVibroLZ."'
			WHERE `Arm_workplace`.`id` = ".$idRm.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		}

		//АПФД - Низкофиброгенные
		public static function GetFactorAsset_APFD_Low($fVar, $fVar1, $fPdu, $fPdu1)
		{
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset = 3;}
				if($tmpLevel > 3 && $tmpLevel <= 6){$iAsset = 4;}
				if($tmpLevel > 6 && $tmpLevel <= 10){$iAsset = 5;}
				if($tmpLevel > 10){$iAsset = 6;}
			}
			if($fPdu1 > -1)
			{
				$iAsset1 = 2;
				$tmpLevel = $fVar1 / $fPdu1;
				if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset1 = 3;}
				if($tmpLevel > 3 && $tmpLevel <= 6){$iAsset1 = 4;}
				if($tmpLevel > 6 && $tmpLevel <= 10){$iAsset1 = 5;}
				if($tmpLevel > 10){$iAsset1 = 6;}
			}
			return max($iAsset,$iAsset1);
		}
		//АПФД - Высокофиброгенные
		public static function GetFactorAsset_APFD_Hi($fVar, $fVar1, $fPdu, $fPdu1)
		{
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 2){$iAsset = 3;}
				if($tmpLevel > 2 && $tmpLevel <= 4){$iAsset = 4;}
				if($tmpLevel > 4 && $tmpLevel <= 10){$iAsset = 5;}
				if($tmpLevel > 10){$iAsset = 6;}
			}
			if($fPdu1 > -1)
			{
				$iAsset1 = 2;
				$tmpLevel = $fVar1 / $fPdu1;
				if($tmpLevel > 1 && $tmpLevel <= 2){$iAsset1 = 3;}
				if($tmpLevel > 2 && $tmpLevel <= 4){$iAsset1 = 4;}
				if($tmpLevel > 4 && $tmpLevel <= 10){$iAsset1 = 5;}
				if($tmpLevel > 10){$iAsset1 = 6;}
			}
			return max($iAsset,$iAsset1);
		}
		//Химия - ФМП
		public static function GetFactorAsset_Chem_FMP($fVar, $fPdu)
		{
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 5){$iAsset = 3;}
				if($tmpLevel > 5 && $tmpLevel <= 10){$iAsset = 4;}
				if($tmpLevel > 10){$iAsset = 5;}
			}
			return $iAsset;
		}
		//Химия - Алергены
		public static function GetFactorAsset_Chem_A($fVar, $fVar1, $fPdu, $fPdu1)
		{
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset = 4;}
				if($tmpLevel > 3 && $tmpLevel <= 15){$iAsset = 5;}
				if($tmpLevel > 15 && $tmpLevel <= 20){$iAsset = 6;}
				if($tmpLevel > 20){$iAsset = 7;}
			}
			if($fPdu1 > -1)
			{
				$iAsset1 = 2;
				$tmpLevel = $fVar1 / $fPdu1;
				if($tmpLevel > 1 && $tmpLevel <= 2){$iAsset1 = 3;}
				if($tmpLevel > 2 && $tmpLevel <= 5){$iAsset1 = 4;}
				if($tmpLevel > 5 && $tmpLevel <= 15){$iAsset1 = 5;}
				if($tmpLevel > 15 && $tmpLevel <= 20){$iAsset1 = 6;}
				if($tmpLevel > 20){$iAsset1 = 7;}
			}
			return max($iAsset,$iAsset1);
		}
		//Химия - Канцерогены
		public static function GetFactorAsset_Chem_K($fVar, $fPdu)
		{
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 2){$iAsset = 3;}
				if($tmpLevel > 2 && $tmpLevel <= 4){$iAsset = 4;}
				if($tmpLevel > 4 && $tmpLevel <= 10){$iAsset = 5;}
				if($tmpLevel > 10){$iAsset = 6;}
			}
			return $iAsset;
		}
		//Химия - Острое отравление
		public static function GetFactorAsset_Chem_OO($fVar, $fVar1, $fPdu, $fPdu1)
		{
			$iAsset = 0;
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 2){$iAsset = 3;}
				if($tmpLevel > 2 && $tmpLevel <= 4){$iAsset = 4;}
				if($tmpLevel > 4 && $tmpLevel <= 6){$iAsset = 5;}
				if($tmpLevel > 6 && $tmpLevel <= 10){$iAsset = 6;}
				if($tmpLevel > 10){$iAsset = 7;}
			}
			return $iAsset;
		}
		//Химия - раздражение
		public static function GetFactorAsset_Chem_OR($fVar, $fVar1, $fPdu, $fPdu1)
		{
			$iAsset = 0;
			if($fPdu > -1)
			{
				$iAsset = 2;
				$tmpLevel = $fVar / $fPdu;
				if($tmpLevel > 1 && $tmpLevel <= 2){$iAsset = 3;}
				if($tmpLevel > 2 && $tmpLevel <= 5){$iAsset = 4;}
				if($tmpLevel > 5 && $tmpLevel <= 10){$iAsset = 5;}
				if($tmpLevel > 10 && $tmpLevel <= 50){$iAsset = 6;}
				if($tmpLevel > 50){$iAsset = 7;}
			}
			return $iAsset;
		}
		//Химия - Общая концентрация
		public static function GetFactorAsset_Chem_All($fVar, $fVar1, $fPdu, $fPdu1)
		{
			if($fPdu > -1)
			{
				$iAsset = 2;
				if($fPdu>0)	$tmpLevel = $fVar / $fPdu; else $tmpLevel = $fVar;
				if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset = 3;}
				if($tmpLevel > 3 && $tmpLevel <= 10){$iAsset = 4;}
				if($tmpLevel > 10 && $tmpLevel <= 15){$iAsset = 5;}
				if($tmpLevel > 15 && $tmpLevel <= 20){$iAsset = 6;}
				if($tmpLevel > 20){$iAsset = 7;}
			}
			if($fPdu1 > -1)
			{
				$iAsset1 = 2;
				if($fPdu1>0) $tmpLevel = $fVar1 / $fPdu1; else $tmpLevel = $fVar1;
				if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset1 = 3;}
				if($tmpLevel > 3 && $tmpLevel <= 10){$iAsset1 = 4;}
				if($tmpLevel > 10 && $tmpLevel <= 15){$iAsset1 = 5;}
				if($tmpLevel > 15 && $tmpLevel <= 20){$iAsset1 = 6;}
				if($tmpLevel > 20){$iAsset1 = 7;}
			}
			return max($iAsset,$iAsset1);
		}
		//Плотность сигналов (световых, звуковых) и сообщений в единицу времени
		public static function GetFactorAsset_Tennese_PS($fVar1)
		{
			$iAsset = 1;
			if($fVar1 >= 76 && $fVar1 <= 175){$iAsset = 2;}
			if($fVar1 >= 176 && $fVar1 <= 300){$iAsset = 3;}
			if($fVar1 > 300){$iAsset = 4;}
			return $iAsset;
		}
		//Число производственных объектов одновременного набл юдения
		public static function GetFactorAsset_Tennese_OC($fVar1)
		{
			$iAsset = 1;
			if($fVar1 >= 6 && $fVar1 <= 10){$iAsset = 2;}
			if($fVar1 >= 11 && $fVar1 <= 25){$iAsset = 3;}
			if($fVar1 > 25){$iAsset = 4;}
			return $iAsset;
		}
		//Работа с оптическими приборами
		public static function GetFactorAsset_Tennese_OP($fVar1)
		{
			$iAsset = 1;
			if($fVar1 >= 26 && $fVar1 <= 50){$iAsset = 2;}
			if($fVar1 >= 51 && $fVar1 <= 75){$iAsset = 3;}
			if($fVar1 > 75){$iAsset = 4;}
			return $iAsset;
		}
		//Нагрузка на голосовой аппарат
		public static function GetFactorAsset_Tennese_GA($fVar1)
		{
			$iAsset = 1;
			if($fVar1 >= 16 && $fVar1 < 20){$iAsset = 2;}
			if($fVar1 >= 20 && $fVar1 <= 25){$iAsset = 3;}
			if($fVar1 > 25){$iAsset = 4;}
			return $iAsset;
		}
		//Число элементов (приемов), необходимых для реализации простого задания или многократно повторяющихся операций
		public static function GetFactorAsset_Tennese_PO($fVar1)
		{
			$iAsset = 1;
			if($fVar1 >= 6 && $fVar1 <= 9){$iAsset = 2;}
			if($fVar1 >= 3 && $fVar1 <= 5){$iAsset = 3;}
			if($fVar1 < 3){$iAsset = 4;}
			return $iAsset;
		}
		//Монотонность производственной обстановки (время пассивного наблюдения за ходом технологического процесса в % от времени смены)
		public static function GetFactorAsset_Tennese_MO($fVar1)
		{
			$iAsset = 1;
			if($fVar1 >= 76 && $fVar1 <= 80){$iAsset = 2;}
			if($fVar1 >= 81 && $fVar1 <= 90){$iAsset = 3;}
			if($fVar1 > 90){$iAsset = 4;}
			return $iAsset;
		}
		//Перемещение в пространстве
		public static function GetFactorAsset_HeavyPP($fVar1, $fVar2)
		{
			//Горизонталь
			$mA1 = 1;
			if($fVar1 >= 4 && $fVar1 < 8){$mA1 = 2;}
			if($fVar1 >= 8 && $fVar1 <= 12){$mA1 = 3;}
			if($fVar1 > 12){$mA1 = 4;}
			//Вертикаль
			$mA2 = 1;
			if($fVar2 >= 1 && $fVar2 < 2.5){$mA2 = 2;}
			if($fVar2 >= 2.5 && $fVar2 <= 5){$mA2 = 3;}
			if($fVar2 > 5){$mA2 = 4;}
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1.','.$mA2;
			return $tmpAddonAsset;
		}
		//Наклоны корпуса тела работника
		public static function GetFactorAsset_HeavyNK($fVar1)
		{
			$mA1 = 1;
			if($fVar1 > 50 && $fVar1 <= 100){$mA1 = 2;}
			if($fVar1 > 100 && $fVar1 <= 300){$mA1 = 3;}
			if($fVar1 > 300){$mA1 = 4;}
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1;
			return $tmpAddonAsset;
		}
		//Рабочая поза
		public static function GetFactorAsset_HeavyRP($fVar1)
		{
			$mA1 = $fVar1+1;
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1;
			return $tmpAddonAsset;
		}
		//Статическая нагрузка
		public static function GetFactorAsset_HeavySN($fVar1, $fVar2, $fVar3)
		{
			//Одна рука
			$mA1 = 1;
			if($fVar1 > 18000 && $fVar1 <= 36000){$mA1 = 2;}
			if($fVar1 > 36000 && $fVar1 <= 70000){$mA1 = 3;}
			if($fVar1 > 70000){$mA1 = 4;}
			$wA1 = 1;
			if($fVar1 > 11000 && $fVar1 <= 22000){$wA1 = 2;}
			if($fVar1 > 22000 && $fVar1 <= 42000){$wA1 = 3;}
			if($fVar1 > 42000){$wA1 = 4;}
			//Две руки
			$mA2 = 1;
			if($fVar2 > 36000 && $fVar2 <= 70000){$mA2 = 2;}
			if($fVar2 > 70000 && $fVar2 <= 140000){$mA2 = 3;}
			if($fVar2 > 140000){$mA2 = 4;}
			$wA2 = 1;
			if($fVar2 > 22000 && $fVar2 <= 42000){$wA2 = 2;}
			if($fVar2 > 42000 && $fVar2 <= 84000){$wA2 = 3;}
			if($fVar2 > 84000){$wA2 = 4;}
			//Корпус и ноги
			$mA3 = 1;
			if($fVar3 > 43000 && $fVar3 <= 100000){$mA3 = 2;}
			if($fVar3 > 100000 && $fVar3 <= 200000){$mA3 = 3;}
			if($fVar3 > 200000){$mA3 = 4;}
			$wA3 = 1;
			if($fVar3 > 26000 && $fVar3 <= 60000){$wA3 = 2;}
			if($fVar3 > 60000 && $fVar3 <= 120000){$wA3 = 3;}
			if($fVar3 > 120000){$wA3 = 4;}
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1 .','. $mA2 .','. $mA3 .','. $wA1 .','. $wA2 .','. $wA3;
			return $tmpAddonAsset;
		}
		//Стереотипные рабочие движения
		public static function GetFactorAsset_HeavySD($fVar1, $fVar2)
		{
			//Локальная
			$mA1 = 1;
			if($fVar1 > 20000 && $fVar1 <= 40000){$mA1 = 2;}
			if($fVar1 > 40000 && $fVar1 <= 60000){$mA1 = 3;}
			if($fVar1 > 60000){$mA1 = 4;}
			//Региональная
			$mA2 = 1;
			if($fVar2 > 10000 && $fVar2 <= 20000){$mA2 = 2;}
			if($fVar2 > 20000 && $fVar2 <= 30000){$mA2 = 3;}
			if($fVar2 > 30000){$mA2 = 4;}
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1 .','. $mA2;
			return $tmpAddonAsset;
		}
		//Масса поднимаемого и перемещаемого груза вручную
		public static function GetFactorAsset_HeavyPiP($fVar1, $fVar2, $fVar3, $fVar4)
		{
			//Разовое
			$mA1 = 1;
			if($fVar1 > 15 && $fVar1 <= 30){$mA1 = 2;}
			if($fVar1 > 30 && $fVar1 <= 35){$mA1 = 3;}
			if($fVar1 > 35){$mA1 = 4;}
			$wA1 = 1;
			if($fVar1 > 5 && $fVar1 <= 10){$wA1 = 2;}
			if($fVar1 > 10 && $fVar1 <= 12){$wA1 = 3;}
			if($fVar1 > 12){$wA1 = 4;}
			//Постоянное
			$mA2 = 1;
			if($fVar2 > 5 && $fVar2 <= 15){$mA2 = 2;}
			if($fVar2 > 15 && $fVar2 <= 20){$mA2 = 3;}
			if($fVar2 > 20){$mA2 = 4;}
			$wA2 = 1;
			if($fVar2 > 3 && $fVar2 <= 7){$wA2 = 2;}
			if($fVar2 > 7 && $fVar2 <= 10){$wA2 = 3;}
			if($fVar2 > 10){$wA2 = 4;}
			//Суммарное, с раб поверхности
			$mA3 = 1;
			if($fVar3 > 250 && $fVar3 <= 870){$mA3 = 2;}
			if($fVar3 > 870 && $fVar3 <= 1500){$mA3 = 3;}
			if($fVar3 > 1500){$mA3 = 4;}
			$wA3 = 1;
			if($fVar3 > 100 && $fVar3 <= 350){$wA3 = 2;}
			if($fVar3 > 350 && $fVar3 <= 700){$wA3 = 3;}
			if($fVar3 > 700){$wA3 = 4;}
			//Суммарное, с пола
			$mA4 = 1;
			if($fVar4 > 100 && $fVar4 <= 435){$mA4 = 2;}
			if($fVar4 > 435 && $fVar4 <= 600){$mA4 = 3;}
			if($fVar4 > 600){$mA4 = 4;}
			$wA4 = 1;
			if($fVar4 > 50 && $fVar4 <= 175){$wA4 = 2;}
			if($fVar4 > 175 && $fVar4 <= 350){$wA4 = 3;}
			if($fVar4 > 350){$wA4 = 4;}
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1 .','. $mA2 .','. $mA3 .','. $mA4 .','. $wA1 .','. $wA2 .','. $wA3 .','. $wA4;
			return $tmpAddonAsset;
		}
		//Физическая динамическая нагрузка
		public static function GetFactorAsset_HeavyFD($fVar1, $fVar2, $fVar3)
		{
			//Региональная нагрузка до 1м.
			$mA1 = 1;
			if($fVar1 > 2500 && $fVar1 <= 5000){$mA1 = 2;}
			if($fVar1 > 5000 && $fVar1 <= 7000){$mA1 = 3;}
			if($fVar1 > 7000){$mA1 = 4;}
			$wA1 = 1;
			if($fVar1 > 1500 && $fVar1 <= 3000){$wA1 = 2;}
			if($fVar1 > 3000 && $fVar1 <= 4000){$wA1 = 3;}
			if($fVar1 > 4000){$wA1 = 4;}
			//Общая нагрузка 1-5м.
			$mA2 = 1;
			if($fVar2 > 12500 && $fVar2 <= 25000){$mA2 = 2;}
			if($fVar2 > 25000 && $fVar2 <= 35000){$mA2 = 3;}
			if($fVar2 > 35000){$mA2 = 4;}
			$wA2 = 1;
			if($fVar2 > 7500 && $fVar2 <= 15000){$wA2 = 2;}
			if($fVar2 > 15000 && $fVar2 <= 25000){$wA2 = 3;}
			if($fVar2 > 25000){$wA2 = 4;}
			//Общая нагрузка более 5м.
			$mA3 = 1;
			if($fVar3 > 24000 && $fVar3 <= 46000){$mA3 = 2;}
			if($fVar3 > 46000 && $fVar3 <= 70000){$mA3 = 3;}
			if($fVar3 > 70000){$mA3 = 4;}
			$wA3 = 1;
			if($fVar3 > 14000 && $fVar3 <= 28000){$wA3 = 2;}
			if($fVar3 > 28000 && $fVar3 <= 40000){$wA3 = 3;}
			if($fVar3 > 40000){$wA3 = 4;}
			//Сбойка итоговой строки
			$tmpAddonAsset = $mA1 .','. $mA2 .','. $mA3 .','. $wA1 .','. $wA2 .','. $wA3;
			return $tmpAddonAsset;
		}
		//Рентгеновское, гамма- и нейтронное излучение - эффективная доза, мзв/год
		public static function GetFactorAsset_BIO($fVar, $fPdu)
		{
			$iAsset = 2;
			$tmpLevel = $fVar / $fPdu;
			if($tmpLevel > 1 && $tmpLevel <= 10){$iAsset = 3;}
			if($tmpLevel > 10 && $tmpLevel <= 100){$iAsset = 4;}
			if($tmpLevel > 100){$iAsset = 5;}
			return $iAsset;
		}
		//Рентгеновское, гамма- и нейтронное излучение - эффективная доза, мзв/год
		public static function GetFactorAsset_IONDose($fVar)
		{
			$iAsset = 2;
			if($fVar > 100) {$iAsset = 7;}
			if($fVar <= 100) {$iAsset = 6;}
			if($fVar <= 50) {$iAsset = 5;}
			if($fVar <= 20) {$iAsset = 4;}
			if($fVar <= 10) {$iAsset = 3;}
			if($fVar <= 5) {$iAsset = 2;}
			return $iAsset;
		}
		//Рентгеновское, гамма- и нейтронное излучение - эквивалентная доза в хрусталике глаза, мзв/год
		public static function GetFactorAsset_IONEye($fVar)
		{
			$iAsset = 2;
			if($fVar > 300) {$iAsset = 7;}
			if($fVar <= 300) {$iAsset = 6;}
			if($fVar <= 225) {$iAsset = 5;}
			if($fVar <= 150) {$iAsset = 4;}
			if($fVar <= 75) {$iAsset = 3;}
			if($fVar <= 37.5) {$iAsset = 2;}
			return $iAsset;
		}
		//Рентгеновское, гамма- и нейтронное излучение - эффективная доза в коже, кистях и стопах, мзв/год
		public static function GetFactorAsset_IONSkin($fVar)
		{
			$iAsset = 2;
			if($fVar > 1000) {$iAsset = 7;}
			if($fVar <= 1000) {$iAsset = 6;}
			if($fVar <= 750) {$iAsset = 5;}
			if($fVar <= 500) {$iAsset = 4;}
			if($fVar <= 250) {$iAsset = 3;}
			if($fVar <= 125) {$iAsset = 2;}
			return $iAsset;
		}
		//Переменное электромагнитное поле радиочастотного диапазона?, (В/м)2·ч, (А/м)2·ч
		public static function GetFactorAsset_EMIRCLow($fVar, $fPdu)
		{
			$iAsset = 2;
			if($fPdu == 0){$fPdu = 1;}
			$tmpLevel = $fVar / $fPdu;
			if($tmpLevel > 1 && $tmpLevel <= 5){$iAsset = 3;}
			if($tmpLevel > 5 && $tmpLevel <= 10){$iAsset = 4;}
			if($tmpLevel > 10){$iAsset = 5;}
			return $iAsset;
		}
		public static function GetFactorAsset_EMIRCMed($fVar, $fPdu)
		{
			$iAsset = 2;
			if($fPdu == 0){$fPdu = 1;}
			$tmpLevel = $fVar / $fPdu;
			if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset = 3;}
			if($tmpLevel > 3 && $tmpLevel <= 5){$iAsset = 4;}
			if($tmpLevel > 5 && $tmpLevel <= 10){$iAsset = 5;}
			if($tmpLevel > 10){$iAsset = 6;}
			return $iAsset;
		}
		public static function GetFactorAsset_EMIRCHi($fVar, $fPdu)
		{
			$iAsset = 2;
			if($fPdu == 0){$fPdu = 1;}
			$tmpLevel = $fVar / $fPdu;
			if($tmpLevel > 1 && $tmpLevel <= 3){$iAsset = 3;}
			if($tmpLevel > 3 && $tmpLevel <= 5){$iAsset = 4;}
			if($tmpLevel > 5 && $tmpLevel <= 10){$iAsset = 5;}
			if($tmpLevel > 10 && $tmpLevel <= 100){$iAsset = 6;}
			if($tmpLevel > 100){$iAsset = 7;}
			return $iAsset;
		}
		//Лазерное излучение
		public static function GetFactorAsset_EMILaser($fVar, $fPdu)
		{
			$iAsset = 2;
			if($fVar > $fPdu && $fVar <= 10*$fPdu){$iAsset = 4;}
			if($fVar > 10*$fPdu && $fVar <= 100*$fPdu){$iAsset = 5;}
			if($fVar > 100*$fPdu && $fVar <= 1000*$fPdu){$iAsset = 6;}
			if($fVar > 1000*$fPdu){$iAsset = 7;}
			return $iAsset;
		}
		//Ультрафиолетовое излучение, Вт/м2
		public static function GetFactorAsset_EMIUF($fVarA, $fVarB, $fVarC)
		{
			$iAssetA = 2;
			if($fVarA > 50){$iAssetA = 3;}
			$iAssetB = 2;
			if($fVarB > 0.05){$iAssetB = 3;}
			$iAssetC = 2;
			if($fVarC > 0.001){$iAssetC = 3;}
			return max($iAssetA,$iAssetB,$iAssetC);
		}
		//Электростатическое поле, кВ/м
		public static function GetFactorAsset_EMIPMP($fVar)
		{
			$iAsset = 2;
			$tmpLevel = $fVar / 8000;
			if($tmpLevel > 1 && $tmpLevel <= 5){$iAsset = 3;}
			if($tmpLevel > 5){$iAsset = 4;}
			return $iAsset;
		}
		//Электростатическое поле, кВ/м
		public static function GetFactorAsset_EMIESP($fVar)
		{
			$iAsset = 2;
			$tmpLevel = $fVar / 60;
			if($tmpLevel > 1 && $tmpLevel <= 5){$iAsset = 3;}
			if($tmpLevel > 5){$iAsset = 4;}
			return $iAsset;
		}
		//Переменное электромагнитное поле (промышленная частота 50 Гц), Э кВ/м, М А/м
		public static function GetFactorPdu_EMI50($TimeHour)
		{
			$value = 80;
			if($TimeHour<=4) $value = 400;
			if($TimeHour<=2) $value = 800;
			if($TimeHour<=1) $value = 1600;
			return $value;
		}
		public static function GetFactorAsset_EMI50($fVarE, $fVarM, $fPduE, $fPduM)
		{
			$iAssetE = 2;
			$tmpLevelE = $fVarE / $fPduE;
			if($tmpLevelE > 1 && $tmpLevelE <= 5){$iAssetE = 3;}
			if($tmpLevelE > 5 && $tmpLevelE <= 10){$iAssetE = 4;}
			if($tmpLevelE > 10 && $tmpLevelE <= 40){$iAssetE = 5;}
			if($tmpLevelE > 40){$iAssetE = 7;}

			$iAssetM = 2;
			$tmpLevelM = $fVarM / $fPduM;
			if($tmpLevelM > 1 && $tmpLevelM <= 5){$iAssetM = 3;}
			if($tmpLevelM > 5 && $tmpLevelM <= 10){$iAssetM = 4;}
			if($tmpLevelM > 10){$iAssetM = 5;}

			return max($iAssetE, $iAssetM);
		}

		//Температура воздуха, 0С
		public static function GetFactorAsset_Temperature($fVar, $sCategory)
		{
			$iAsset = 0;
			switch($sCategory)
			{
				case 0:
					if($fVar >= 22 && $fVar <= 24) {$iAsset = 1;}
					if($fVar > 24 && $fVar <= 25) {$iAsset = 2;}
					if($fVar >= 20 && $fVar < 22) {$iAsset = 2;}
					if($fVar >= 18 && $fVar < 20) {$iAsset = 3;}
					if($fVar >= 16 && $fVar < 18) {$iAsset = 4;}
					if($fVar >= 14 && $fVar < 16) {$iAsset = 5;}
					if($fVar >= 12 && $fVar < 14) {$iAsset = 6;}
					if($fVar < 12) {$iAsset = 7;}
				break;
				case 1:
					if($fVar >= 21 && $fVar <= 23) {$iAsset = 1;}
					if($fVar > 23 && $fVar <= 24) {$iAsset = 2;}
					if($fVar >= 19 && $fVar < 21) {$iAsset = 2;}
					if($fVar >= 17 && $fVar < 19) {$iAsset = 3;}
					if($fVar >= 15 && $fVar < 17) {$iAsset = 4;}
					if($fVar >= 13 && $fVar < 15) {$iAsset = 5;}
					if($fVar >= 11 && $fVar < 13) {$iAsset = 6;}
					if($fVar < 11) {$iAsset = 7;}
				break;
				case 2:
					if($fVar >= 19 && $fVar <= 21) {$iAsset = 1;}
					if($fVar > 21 && $fVar <= 23) {$iAsset = 2;}
					if($fVar >= 17 && $fVar < 19) {$iAsset = 2;}
					if($fVar >= 14 && $fVar < 17) {$iAsset = 3;}
					if($fVar >= 12 && $fVar < 14) {$iAsset = 4;}
					if($fVar >= 10 && $fVar < 12) {$iAsset = 5;}
					if($fVar >= 8 && $fVar < 10) {$iAsset = 6;}
					if($fVar < 8) {$iAsset = 7;}
				break;
				case 3:
					if($fVar >= 17 && $fVar <= 19) {$iAsset = 1;}
					if($fVar > 19 && $fVar <= 22) {$iAsset = 2;}
					if($fVar >= 15 && $fVar < 17) {$iAsset = 2;}
					if($fVar >= 13 && $fVar < 15) {$iAsset = 3;}
					if($fVar >= 11 && $fVar < 13) {$iAsset = 4;}
					if($fVar >= 9 && $fVar < 11) {$iAsset = 5;}
					if($fVar >= 7 && $fVar < 9) {$iAsset = 6;}
					if($fVar < 7) {$iAsset = 7;}
				break;
				case 4:
					if($fVar >= 16 && $fVar <= 18) {$iAsset = 1;}
					if($fVar > 18 && $fVar <= 21) {$iAsset = 2;}
					if($fVar >= 13 && $fVar < 16) {$iAsset = 2;}
					if($fVar >= 12 && $fVar < 13) {$iAsset = 3;}
					if($fVar >= 10 && $fVar < 12) {$iAsset = 4;}
					if($fVar >= 8 && $fVar < 10) {$iAsset = 5;}
					if($fVar >= 6 && $fVar < 8) {$iAsset = 6;}
					if($fVar < 6) {$iAsset = 7;}
				break;
			}
			return $iAsset;
		}
		//ТНС - индекс, 0С
		public static function GetFactorAsset_TNSIndex($fVar, $sCategory)
		{
			$iAsset = 0;
			switch($sCategory)
			{
				case 0:
					if($fVar > 31){$iAsset = 7;}
					if($fVar >= 28.7 && $fVar <= 31){$iAsset = 6;}
					if($fVar >= 27.5 && $fVar <= 28.6){$iAsset = 5;}
					if($fVar >= 26.7 && $fVar <= 27.4){$iAsset = 4;}
					if($fVar >= 26.5 && $fVar <= 26.6){$iAsset = 3;}
					if($fVar < 26.5){$iAsset = 2;}
				break;
				case 1:
					if($fVar > 30.3){$iAsset = 7;}
					if($fVar >= 28 && $fVar <= 30.3){$iAsset = 6;}
					if($fVar >= 27 && $fVar <= 27.9){$iAsset = 5;}
					if($fVar >= 26.2 && $fVar <= 26.9){$iAsset = 4;}
					if($fVar >= 25.9 && $fVar <= 26.1){$iAsset = 3;}
					if($fVar < 25.9){$iAsset = 2;}
				break;
				case 2:
					if($fVar > 29.9){$iAsset = 7;}
					if($fVar >= 27.4 && $fVar <= 29.9){$iAsset = 6;}
					if($fVar >= 26.3 && $fVar <= 27.3){$iAsset = 5;}
					if($fVar >= 25.6 && $fVar <= 26.2){$iAsset = 4;}
					if($fVar >= 25.2 && $fVar <= 25.5){$iAsset = 3;}
					if($fVar < 25.2){$iAsset = 2;}
				break;
				case 3:
					if($fVar > 29.1){$iAsset = 7;}
					if($fVar >= 26.5 && $fVar <= 29.1){$iAsset = 6;}
					if($fVar >= 25.1 && $fVar <= 26.4){$iAsset = 5;}
					if($fVar >= 24.3 && $fVar <= 25){$iAsset = 4;}
					if($fVar >= 24 && $fVar <= 24.2){$iAsset = 3;}
					if($fVar < 24){$iAsset = 2;}
				break;
				case 4:
					if($fVar > 27.9){$iAsset = 7;}
					if($fVar >= 25.8 && $fVar <= 27.9){$iAsset = 6;}
					if($fVar >= 23.5 && $fVar <= 25.7){$iAsset = 5;}
					if($fVar >= 22.1 && $fVar <= 23.4){$iAsset = 4;}
					if($fVar >= 21.9 && $fVar <= 22){$iAsset = 3;}
					if($fVar < 21.9){$iAsset = 2;}
				break;
			}
			return $iAsset;
		}
		//Относительная влажность воздуха, %
		public static function GetFactorAsset_AirDry($fVar)
		{
			$iAsset = 0;
			if($fVar >= 15 && $fVar <= 75) {$iAsset = 2;}
			if($fVar >= 40 && $fVar <= 60) {$iAsset = 1;}
			if($fVar <15) {$iAsset = 3;}
			if($fVar <10) {$iAsset = 4;}
			return $iAsset;
		}
		//Cкорость движения воздуха, м/с
		public static function GetFactorAsset_AirSpeed($fVar, $sCategory)
		{
			$iAsset = 2;
			switch($sCategory)
			{
				case 0:
					if($fVar >= 0.6) {$iAsset = 3;}
				break;
				case 1:
					if($fVar >= 0.6) {$iAsset = 3;}
				break;
				case 2:
					if($fVar >= 0.6) {$iAsset = 3;}
				break;
				case 3:
					if($fVar >= 0.6) {$iAsset = 3;}
				break;
				case 4:
					if($fVar >= 0.6) {$iAsset = 3;}
				break;
			}
			return $iAsset;
		}
		//Экспозиционная доза теплового облучения, Вт*ч
		public static function GetFactorAsset_HeartExpDose($fVar)
		{
			$iAsset = 2;
			if($fVar > 4800) {$iAsset = 7;}
			if($fVar <= 4800) {$iAsset = 6;}
			if($fVar <= 3800) {$iAsset = 5;}
			if($fVar <= 2600) {$iAsset = 4;}
			if($fVar <= 1500) {$iAsset = 3;}
			if($fVar <= 500) {$iAsset = 2;}
			return $iAsset;
		}
		//Тепловое излучение, Вт/м3
		public static function GetFactorAsset_HeartRay($fVar)
		{
			$iAsset = 2;
			if($fVar > 2800) {$iAsset = 7;}
			if($fVar <= 2800) {$iAsset = 6;}
			if($fVar <= 2500) {$iAsset = 5;}
			if($fVar <= 2000) {$iAsset = 4;}
			if($fVar <= 1500) {$iAsset = 3;}
			if($fVar <= 140) {$iAsset = 2;}
			return $iAsset;
		}
		//Освещенность рабочей поверхности, лк
		public static function GetFactorAsset_Light($fVar, $fPdu)
		{
			$iAsset = 0;
			if($fVar < ($fPdu*0.5)){$iAsset=4;}
			if($fVar >= ($fPdu*0.5)){$iAsset=3;}
			if($fVar >= $fPdu){$iAsset=2;}
			return $iAsset;
		}
		public static function GetFactorAsset_LightAddon($fVar)
		{
			$iAsset = 0;
			return $iAsset;
		}
		//Шум, дБа
		public static function GetFactorAsset_Noise($fVar)
		{
			$iAsset = 0;
			if($fVar <= 80){$iAsset=2;}
			if($fVar > 80){$iAsset=3;}
			if($fVar > 85){$iAsset=4;}
			if($fVar > 95){$iAsset=5;}
			if($fVar > 105){$iAsset=6;}
			if($fVar > 115){$iAsset=7;}
			return $iAsset;
		}
		//Инфразвук, дБЛин
		public static function GetFactorAsset_InfraNoise($fVar)
		{
			if($fVar <= 110){$iAsset=2;}
			if($fVar > 110){$iAsset=3;}
			if($fVar > 115){$iAsset=4;}
			if($fVar > 120){$iAsset=5;}
			if($fVar > 125){$iAsset=6;}
			if($fVar > 130){$iAsset=7;}
			return $iAsset;
		}
		//Ультразвук, дБ
		public static function GetFactorAsset_UltraNoise($fVar, $fPdu)
		{
			$iAsset = 0;
			$fVar = $fVar - $fPdu;
			if($fVar <= 0){$iAsset=2;}
			if($fVar > 0){$iAsset=3;}
			if($fVar > 10){$iAsset=4;}
			if($fVar > 20){$iAsset=5;}
			if($fVar > 30){$iAsset=6;}
			if($fVar > 40){$iAsset=7;}
			return $iAsset;
		}
		//Локальная вибрация, дБ
		public static function GetFactorAsset_LocalVibro($fVar)
		{
			$iAsset = 0;
			if($fVar <= 126){$iAsset=2;}
			if($fVar > 126){$iAsset=3;}
			if($fVar > 129){$iAsset=4;}
			if($fVar > 132){$iAsset=5;}
			if($fVar > 135){$iAsset=6;}
			if($fVar > 138){$iAsset=7;}
			return $iAsset;
		}
		//Общая вибрация, дБ
		public static function GetFactorAsset_TotalVibro($fVar, $sAcis)
		{
			$iAsset = 0;
			switch ($sAcis)
			{
				case 'X':
				case 'Y':
					if($fVar <= 112){$iAsset=2;}
					if($fVar > 112){$iAsset=3;}
					if($fVar > 118){$iAsset=4;}
					if($fVar > 124){$iAsset=5;}
					if($fVar > 130){$iAsset=6;}
					if($fVar > 136){$iAsset=7;}
				break;
				case 'Z':
					if($fVar <= 115){$iAsset=2;}
					if($fVar > 115){$iAsset=3;}
					if($fVar > 121){$iAsset=4;}
					if($fVar > 127){$iAsset=5;}
					if($fVar > 133){$iAsset=6;}
					if($fVar > 139){$iAsset=7;}
				break;
			}
			return $iAsset;
		}
		//=====================================================================================================================================
	}

//Расчет эквивалентного уровня звука
function NoiseEql ($aNoiseLevel, $aNoiseTime, $fLongDay = 8, $bNoise = false, $aNoiseLevel2 = null, $aNoiseLevel3 = null)
{
	if (!$bNoise)
	{
		$tmpSum = 0;
		for($i = 0; $i < count($aNoiseLevel);$i++)
		{
			$aNoiseTime[$i] = str_replace(',','.',$aNoiseTime[$i]);
			$aNoiseLevel[$i] = str_replace(',','.',$aNoiseLevel[$i]);
			$tmpSum = $tmpSum + ($aNoiseTime[$i]*pow(10, $aNoiseLevel[$i]*0.1));
		}
		$noiseTotal = 10*log10($tmpSum/8);

		return round($noiseTotal,0);
	}
	$nc = new NoiseCalc($aNoiseLevel, $aNoiseLevel2, $aNoiseLevel3, $aNoiseTime, $fLongDay);
	$aReturn = array();
	$aReturn[] = $nc->equal;
	$aReturn[] = $nc->suspense;
	return $aReturn;
}

//Расчет средневзвешенной велечины микроклимата
function SrMicroclimat ($aMicroclimatTime, $aMicroclimatMaxAsset, $fLongDay = 8)
{
	$iTotalAsset = 0;
	$tmpTopSum = 0;
	$tmpSum = 0;
	for($i = 0; $i < count($aMicroclimatTime);$i++)
	{
		$tmpSum += $aMicroclimatTime[$i];
		$tmpTopSum += $aMicroclimatMaxAsset[$i]*$aMicroclimatTime[$i];
	}
	if($tmpSum < $fLongDay)
	{
		$tmpTopSum += ($fLongDay - $tmpSum)*1;
		$iTotalAsset = $tmpTopSum / $fLongDay;
	}
	else
	{
		$iTotalAsset = $tmpTopSum / $tmpSum;
	}

	return round($iTotalAsset);
}

//Расчет средневзвешенной велечины освещения
function SrLight ($aLightTime, $aLightMaxAsset, $fLongDay = 8)
{
	$iTotalAsset = 0;
	$tmpTopSum = 0;
	$tmpSum = 0;
	for($i = 0; $i < count($aLightTime);$i++)
	{
		$aLightTime[$i] = makeToFloat($aLightTime[$i]);
		$aLightMaxAsset[$i] = makeToFloat($aLightMaxAsset[$i]);
		$tmpSum += $aLightTime[$i];
		$tmpTopSum += ($aLightMaxAsset[$i]-2)*$aLightTime[$i];
	}
	if($tmpSum < $fLongDay)
	{
		$tmpTopSum += ($fLongDay - $tmpSum)*0;
		$iTotalAsset = $tmpTopSum / $fLongDay;
	}
	else
	{
		$iTotalAsset = $tmpTopSum / $tmpSum;
	}
//	DbConnect::Log($iTotalAsset .' / '.$fLongDay.' / '.(round($iTotalAsset)+2), "light_calc", UserControl::GetUserLoginId());

	if($iTotalAsset >= 1.5) $iTotalAsset = 4;
	if($iTotalAsset >= 0.5 && $iTotalAsset < 1.5) $iTotalAsset = 3;
	if($iTotalAsset < 0.5) $iTotalAsset = 2;


	return $iTotalAsset;
}

function Summ_Add_Chem($sFeatCode, $sVar1, $sVar2, $sPdu1, $sPdu2, $aChemMr, $aChemSs)
{
	(float)$vOtnMr = $sVar1 / $sPdu1;
//	DbConnect::Log($sFeatCode,'Debug');
	if (strpos($sFeatCode, 'a') > -1 && $sPdu1 != -1) {array_push($aChemMr[0], $vOtnMr);}
	if (strpos($sFeatCode, 'b') > -1 && $sPdu1 != -1) {array_push($aChemMr[1], $vOtnMr);}
	if (strpos($sFeatCode, 'c') > -1 && $sPdu1 != -1) {array_push($aChemMr[2], $vOtnMr);}
	if (strpos($sFeatCode, 'd') > -1 && $sPdu1 != -1) {array_push($aChemMr[3], $vOtnMr);}
	if (strpos($sFeatCode, 'e') > -1 && $sPdu1 != -1) {array_push($aChemMr[4], $vOtnMr);}
	if (strpos($sFeatCode, 'f') > -1 && $sPdu1 != -1) {array_push($aChemMr[5], $vOtnMr);}
	if (strpos($sFeatCode, 'g') > -1 && $sPdu1 != -1) {array_push($aChemMr[6], $vOtnMr);}
	if (strpos($sFeatCode, 'h') > -1 && $sPdu1 != -1) {array_push($aChemMr[7], $vOtnMr);}
	if (strpos($sFeatCode, 'i') > -1 && $sPdu1 != -1) {array_push($aChemMr[8], $vOtnMr);}
	if (strpos($sFeatCode, 'j') > -1 && $sPdu1 != -1) {array_push($aChemMr[9], $vOtnMr);}
	if (strpos($sFeatCode, 'k') > -1 && $sPdu1 != -1) {array_push($aChemMr[10], $vOtnMr);}
	if (strpos($sFeatCode, 'l') > -1 && $sPdu1 != -1) {array_push($aChemMr[11], $vOtnMr);}
	if (strpos($sFeatCode, 'm') > -1 && $sPdu1 != -1) {array_push($aChemMr[12], $vOtnMr);}
	if (strpos($sFeatCode, 'n') > -1 && $sPdu1 != -1) {array_push($aChemMr[13], $vOtnMr);}
	if (strpos($sFeatCode, 'o') > -1 && $sPdu1 != -1) {array_push($aChemMr[14], $vOtnMr);}
	if (strpos($sFeatCode, 'p') > -1 && $sPdu1 != -1) {array_push($aChemMr[15], $vOtnMr);}
	if (strpos($sFeatCode, 'q') > -1 && $sPdu1 != -1) {array_push($aChemMr[16], $vOtnMr);}

	(float)$vOtnSs = $sVar2 / $sPdu2;
	if (strpos($sFeatCode, 'a') > -1 && $sPdu2 != -1) {array_push($aChemSs[0], $vOtnSs);}
	if (strpos($sFeatCode, 'b') > -1 && $sPdu2 != -1) {array_push($aChemSs[1], $vOtnSs);}
	if (strpos($sFeatCode, 'c') > -1 && $sPdu2 != -1) {array_push($aChemSs[2], $vOtnSs);}
	if (strpos($sFeatCode, 'd') > -1 && $sPdu2 != -1) {array_push($aChemSs[3], $vOtnSs);}
	if (strpos($sFeatCode, 'e') > -1 && $sPdu2 != -1) {array_push($aChemSs[4], $vOtnSs);}
	if (strpos($sFeatCode, 'f') > -1 && $sPdu2 != -1) {array_push($aChemSs[5], $vOtnSs);}
	if (strpos($sFeatCode, 'g') > -1 && $sPdu2 != -1) {array_push($aChemSs[6], $vOtnSs);}
	if (strpos($sFeatCode, 'h') > -1 && $sPdu2 != -1) {array_push($aChemSs[7], $vOtnSs);}
	if (strpos($sFeatCode, 'i') > -1 && $sPdu2 != -1) {array_push($aChemSs[8], $vOtnSs);}
	if (strpos($sFeatCode, 'j') > -1 && $sPdu2 != -1) {array_push($aChemSs[9], $vOtnSs);}
	if (strpos($sFeatCode, 'k') > -1 && $sPdu2 != -1) {array_push($aChemSs[10], $vOtnSs);}
	if (strpos($sFeatCode, 'l') > -1 && $sPdu2 != -1) {array_push($aChemSs[11], $vOtnSs);}
	if (strpos($sFeatCode, 'm') > -1 && $sPdu2 != -1) {array_push($aChemSs[12], $vOtnSs);}
	if (strpos($sFeatCode, 'n') > -1 && $sPdu2 != -1) {array_push($aChemSs[13], $vOtnSs);}
	if (strpos($sFeatCode, 'o') > -1 && $sPdu2 != -1) {array_push($aChemSs[14], $vOtnSs);}
	if (strpos($sFeatCode, 'p') > -1 && $sPdu2 != -1) {array_push($aChemSs[15], $vOtnSs);}
	if (strpos($sFeatCode, 'q') > -1 && $sPdu2 != -1) {array_push($aChemSs[16], $vOtnSs);}

	//DbConnect::Log(count($aChemSs[3]),'Debug');
	return $retArray = array($aChemMr, $aChemSs);
}

function Summ_Chem_Ass($aChemMr, $aChemSs)
{
	(float)$iSummMr = 0.0;
	(float)$iSummSs = 0.0;

	//Заполнение массивов дефолтными значениями
	$aSummMr = array(0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0);
	$aSummSs = array(0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0,0.0);
	$aSumMrAss = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	$aSumSsAss = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

	//Суммирование по формуле
	$iCountMr = 0;
	foreach($aChemMr as $aChemArr)
	{
		//DbConnect::Log(count($aChemArr),'Debug');
		if (count($aChemArr) > 1)
		{
			foreach($aChemArr as $iChemVar)
			{
				(float)$aSummMr[$iCountMr] = (float)$aSummMr[$iCountMr] + (float)$iChemVar;

			}
		}
		$iCountMr = $iCountMr + 1;
	}

	$iCountSs = 0;

	foreach($aChemSs as $aChemArr)
	{

		if (count($aChemArr) > 1)
		{
			foreach($aChemArr as $iChemVar)
			{
				(float)$aSummSs[$iCountSs] = (float)$aSummSs[$iCountSs] + (float)$iChemVar;

			}
		}
		$iCountSs = $iCountSs + 1;
	}

	//Проставление оценок суммации по группам веществ МР
	for($iCountMrAss = 0; $iCountMrAss<17; $iCountMrAss++)
	{
		if((float)$aSummMr[$iCountMrAss] != 0.0)
		{
			if ($iCountMrAss != 1 AND $iCountMrAss != 3) {$aSumMrAss[$iCountMrAss] = WorkFactors::GetFactorAsset_Chem_All($aSummMr[$iCountMrAss],0,1,-1);}
			if ($iCountMrAss == 1) {$aSumMrAss[$iCountMrAss] = WorkFactors::GetFactorAsset_Chem_A($aSummMr[$iCountMrAss],0,1,-1);}
			if ($iCountMrAss == 3) {$aSumMrAss[$iCountMrAss] = WorkFactors::GetFactorAsset_APFD_Low($aSummMr[$iCountMrAss],0,1,-1);}
		}
	}

	//Проставление оценок суммации по группам веществ СС
	for($iCountSsAss = 0; $iCountSsAss<17; $iCountSsAss++)
	{
		if((float)$aSummSs[$iCountSsAss] != 0.0)
		{
			if ($iCountSsAss != 1 AND $iCountSsAss != 4 AND $iCountSsAss != 3) {$aSumSsAss[$iCountSsAss] = WorkFactors::GetFactorAsset_Chem_All(0,$aSummSs[$iCountSsAss],-1,1);}
			if ($iCountSsAss == 1) {$aSumSsAss[$iCountSsAss] = WorkFactors::GetFactorAsset_Chem_A(0,$aSummSs[$iCountSsAss],-1,1);}
			if ($iCountSsAss == 4) {$aSumSsAss[$iCountSsAss] = WorkFactors::GetFactorAsset_Chem_K($aSummSs[$iCountSsAss],1);}
			if ($iCountSsAss == 3) {$aSumSsAss[$iCountSsAss] = WorkFactors::GetFactorAsset_APFD_Low(0,$aSummSs[$iCountSsAss],-1,1);}
		}
	}


	/*for($iCounter = 0; $iCounter < 17; $iCounter++)
	{
		DbConnect::Log('№'.$iCounter.'|'.'Значение по сс:'.$aSummSs[$iCounter].'|'.'Оценка:'.$aSumSsAss[$iCounter],'Debug');
	}*/

	//Паковка значений и оценок суммации в один массив и возврат
	return $aTotalSumm = array($aSummMr,$aSumMrAss,$aSummSs,$aSumSsAss);
}


//Функция расчета пылевой нагрузки среды
//$fVar - фактическая среднесменная концентрация пыли в зоне дыхания работника, мг/м3;
//$iDays = 200 - число смен, отработанных в календарном году в условиях воздействия АПФД;
//$iCapacity - объем легочной вентиляции за смену, м3:
//$vPdk - Пдк вещества
function APDF_Dense_Load($fVar,  $vPdk, $iDays = 200, $iCapacity = 4)
{
	$fPN = 0.0;
	$fPN = (float)$fVar * $iDays *$iCapacity;
	$iAsset = 1;
	$fKpn = (float)$vPdk * $iDays *$iCapacity;
		if($vPdk <= 2)
		{
			$iAsset = WorkFactors::GetFactorAsset_APFD_Hi($fPN,0,$fKpn,-1);
		}
		else
		{
			$iAsset = WorkFactors::GetFactorAsset_APFD_Low($fPN,0,$fKpn,-1);
		}
	return $iAsset;
}

?>

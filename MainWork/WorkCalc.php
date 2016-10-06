<?php
	include_once('LowLevel/userValidator.php');
	include_once('UserControl/userControl.php');
	include_once('MainWork/GroupWork.php');
		
	class WorkCalc
	{
		public static function Add_Event($idWorkGroup, $sName, $sInfo, $sSerial, $dDateStart, $dDateEnd)
		{
			$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_calendar (idParent, idWorkGroup, sName, sInfo, sSerial, dDateStart, dDateEnd) VALUES ('.UserControl::GetUserLoginId().',"'.$idWorkGroup.'","'.$sName.'","'.$sInfo.'","'.$sSerial.'","'.date('Y-m-d 00:00:00', strtotime($dDateStart)).'","'.date('Y-m-d 00:00:00', strtotime($dDateEnd)).'");');
			
			return mysql_insert_id();
		}
		
		public static function Remove_Event($idEvent)
		{
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), "DELETE FROM `Arm_calendar` WHERE id = $idEvent;");
		}
		
		public static function Edit_Event($id, $idWorkGroup, $sName, $sInfo, $sSerial, $dDateStart, $dDateEnd)
		{
			$vReturn = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_calendar SET idWorkGroup = '.$idWorkGroup.', sName = "'.$sName.'", sInfo = "'.$sInfo.'", sSerial = "'.$sSerial.'", dDateStart = "'.date('Y-m-d 00:00:00', strtotime($dDateStart)).'", dDateEnd = "'.date('Y-m-d 00:00:00', strtotime($dDateEnd)).'" WHERE `id` = '.$id.';');
		}
		
		public static function Get_Event($id)
		{
			$sql = "SELECT * FROM Arm_calendar WHERE `id` = ".$id.";";
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
			while ($vRow = mysql_fetch_assoc($vResult))
			{
				//Формирование листа для передачи
				$aList[] = array($vRow['id'], $vRow['idParent'],$vRow['idWorkGroup'],$vRow['sName'],$vRow['sInfo'],$vRow['sSerial'],StringWork::StrToDateFormatLite($vRow['dDateStart']),StringWork::StrToDateFormatLite($vRow['dDateEnd']));
			}
			return $aList;
		}
		
		public static function Get_Event_List($dDateStart, $dDateEnd, $idParent, $idWorkGroup = '-1')
		{
			if ($idWorkGroup != '-1')
			{
				$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, idParent, idWorkGroup, sName, sInfo, sSerial, dDateStart, dDateEnd FROM Arm_calendar WHERE idParent IN ('.$idParent.') AND idWorkGroup IN ('.$idWorkGroup.') 
AND (
(dDateStart > \''.date('Y-m-d 00:00:00', strtotime($dDateStart)).'\' AND dDateStart < \''.date('Y-m-d 00:00:00', strtotime($dDateEnd)).'\')
OR
(dDateEnd > \''.date('Y-m-d 00:00:00', strtotime($dDateStart)).'\' AND dDateEnd < \''.date('Y-m-d 00:00:00', strtotime($dDateEnd)).'\')
)
ORDER BY dDateStart;');
			}
			else
			{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, idParent, idWorkGroup, sName, sInfo, sSerial, dDateStart, dDateEnd FROM Arm_calendar WHERE idParent IN ('.$idParent.')
AND (
(dDateStart > \''.date('Y-m-d 00:00:00', strtotime($dDateStart)).'\' AND dDateStart < \''.date('Y-m-d 00:00:00', strtotime($dDateEnd)).'\')
OR
(dDateEnd > \''.date('Y-m-d 00:00:00', strtotime($dDateStart)).'\' AND dDateEnd < \''.date('Y-m-d 00:00:00', strtotime($dDateEnd)).'\')
)
ORDER BY dDateStart;');
			}
			return $vResult;			
		}
	}
?>
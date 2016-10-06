<?php
	include_once('userControl.php');
	include_once('Util/String.php');

	class UserTariff{

		public static function ChangeTariff($iMonth, $iTariffMoney, $sTariffName, $UserId = -1)
		{
			$iSoworcers = 1000;

			switch($sTariffName)
				{
					case 'Coop':
						$iSoworcers = 4;
					break;
					case 'Corp':
						$iSoworcers = 9;
					break;
					case 'Net':
						$iSoworcers = 49;
					break;
					default:
						$iSoworcers = 1000;
					break;
				}

			UserTariff::ChangeTariffData($iMonth, round($iTariffMoney/$iMonth), $sTariffName, $iSoworcers, $UserId);
		}

		//Запись данных тарифа (изменение)
		//$siTariffMoney - указывать стоимость исключительно только за 1 месяц!!
		public static function ChangeTariffData($iMonths, $iTariffMoney, $sTariffName, $iSoWorkersNum, $UserId = -1)
		{
			//Установка тарифа
                        						DbConnect::Log("Подготовка к изменению тарифа.", "TariffChange", $UserId);
//			DbConnect::Log("Подготовка к изменению тарифа. [Текущий тариф: ".UserTariff::GetTariffNameRus(UserTariff::GetTariffName()).", осталось дней: ".UserTariff::GetTariffDays().", сумма: ".UserTariff::GetTariffMoneys()." руб.]", "tariff_change", $UserId);
			$dNowDateNow = date('Y-m-d H:i:s');
			$dNowDateNow = date_create($dNowDateNow);
			$dNowDate = date('Y-m-d H:i:s');
			$dNowDate = date_create($dNowDate);
			$iMonths = (string)$iMonths;
			$dNowDateEnd = date_add($dNowDate, new DateInterval("P".$iMonths."M"));
			if($UserId == -1)
			{
				UserControl::ChangeUserDataNonCrypt('dTariffDateStart', $dNowDateNow->format('Y-m-d'));
				UserControl::ChangeUserDataNonCrypt('dTariffDate', $dNowDateEnd->format('Y-m-d'));
				UserControl::ChangeUserDataNonCrypt('iTariffMoney', $iTariffMoney, false);
				UserControl::ChangeUserDataNonCrypt('sTariffName', $sTariffName);
				UserControl::ChangeUserDataNonCrypt('iTariffSoWorkers', $iSoWorkersNum, false);

				//Зачистка соворкеров
				$rSoworkers = UserTariff::GetSoWorkersResult();
				if (mysql_num_rows($rSoworkers) > $iSoWorkersNum)
				{
					$tmpDelSoworker = mysql_num_rows($rSoworkers) - $iSoWorkersNum;
					while($vRow = mysql_fetch_array($rSoworkers))
					{
						if($tmpDelSoworker > 0)
						{
							UserTariff::DelSoWorker($vRow[idChild]);
							$tmpDelSoworker--;
						}
					}
				}
			}
			else
			{
				$sql = "UPDATE `Arm_users` SET `sTariffName` = '".$sTariffName."', `dTariffDate` = '".$dNowDateEnd->format('Y-m-d')."', `dTariffDateStart` = '".$dNowDateNow->format('Y-m-d')."', `iTariffSoWorkers` = '".$iSoWorkersNum."', `iTariffMoney` = '".$iTariffMoney."' WHERE `Arm_users`.`id` = ".$UserId.";";
//				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				DbConnect::GetSqlQuery($sql);

				//Зачистка соворкеров
				$sql = 'SELECT id, idParent, idChild, sEmail FROM Arm_soworkers WHERE idParent = '.$UserId;
				$rSoworkers = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
				if (mysql_num_rows($rSoworkers) > $iSoWorkersNum)
				{
					$tmpDelSoworker = mysql_num_rows($rSoworkers) - $iSoWorkersNum;
					while($vRow = mysql_fetch_array($rSoworkers))
					{
						if($tmpDelSoworker > 0)
						{
							UserTariff::DelSoWorker($vRow[idChild]);
							$tmpDelSoworker--;
						}
					}
				}
			}
			DbConnect::Log("Тариф изменен. [".$dNowDateNow->format('Y-m-d')." - ".$dNowDateEnd->format('Y-m-d').", ".$iTariffMoney." руб., ".$sTariffName.", ".$iSoWorkersNum." - с.р.]", "tariff_change", $UserId);
		}

		// Проверка и снятие тарифа
		public static function CheckTariffEnd()
		{
			if (UserTariff::GetTariffName() != 'Base')
			{
				if((int)UserTariff::GetTariffDays() < 0)
				{
					UserTariff::ChangeTariffData(120, 0, 'Base', 0);
				}
			}
		}

		// Получение остатка от тарифа в днях
		public static function GetTariffDays()
		{
			$dNowDate = date('Y-m-d H:i:s');
			$dNowDate = date_create($dNowDate);
			$dBaseBata = date_create(UserControl::GetUserFieldValueNonCrypt('dTariffDate'));
			$sDays = (strtotime($dBaseBata->format('Y-m-d')) - strtotime($dNowDate->format('Y-m-d')))/3600/24;

			return (int)$sDays;
		}

		//Получение денежного эквивалента остатка в днях
		public static function GetTariffMoneys()
		{
			$iDays = UserTariff::GetTariffDays();
			$iMoneys = UserControl::GetUserFieldValueNonCrypt('iTariffMoney');
			return (int)round($iMoneys/31*$iDays);
		}

		//Получение имени тарифа
		public static function GetTariffName($UserId = '')
		{
			$sTariffName = UserControl::GetUserFieldValueNonCrypt('sTariffName', $UserId);
			return $sTariffName;
		}

		//Получение даты смены тарифа
		public static function GetTariffStartDate()
		{
			$dBaseData = date_create(UserControl::GetUserFieldValueNonCrypt('dTariffDateStart'));
			return $dBaseData->format("Y-m-d");
		}

		//Получение даты окончания тарифа
		public static function GetTariffEndDateString()
		{
			$dBaseData = date_create(UserControl::GetUserFieldValueNonCrypt('dTariffDate'));
			return $dBaseData->format("Y-m-d");
		}

		//Полная дата окончания тарифа (для пользователя)
		public static function GetTariffEndDateFullString()
		{
			setlocale(LC_ALL, 'rus');
			$dBaseData = date_create(UserControl::GetUserFieldValueNonCrypt('dTariffDate'));
			$sBaseData = StringWork::DateFormatFull($dBaseData);

			return $sBaseData;
		}

		//Полное наименование тарифа (для пользователя)
		public static function GetUserTariffNameRus($UserId = '')
		{
			$sBaseName = UserTariff::GetTariffNameRus(UserTariff::GetTariffName($UserId));
			return $sBaseName;
		}

		//Полное наименование тарифа
		public static function GetTariffNameRus($sBaseName)
		{
//			$sBaseName = str_replace ("Demo", "Демонстрационный&nbsp;режим",$sBaseName);
			$sBaseName = str_replace ("Base", "Базовый&nbsp;тариф",$sBaseName);
			$sBaseName = str_replace ("Pers", "Персональный&nbsp;тариф",$sBaseName);
			$sBaseName = str_replace ("Coop", "Совместный&nbsp;тариф",$sBaseName);
			$sBaseName = str_replace ("Corp", "Корпоративный&nbsp;тариф",$sBaseName);
			$sBaseName = str_replace ("Net", "Сетевой&nbsp;тариф",$sBaseName);
			$sBaseName = str_replace ("BetaTest", "Режим открытого тестирования",$sBaseName);
			return $sBaseName;
		}

		//Подлкючение приглашения соворкера
		public static function SetSoWorkerEmail($sSoWorkerEmail)
		{
			$sSoWorkerEmail = strtolower($sSoWorkerEmail);
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_soworkers (`idParent`,`sEmail`) VALUES ('.UserControl::GetUserLoginId().',"'.$sSoWorkerEmail.'")');
		}

		//Подключение удаление приглашения
		public static function DelSoWorkerEmail($sSoWorkerEmail)
		{
			$sSoWorkerEmail = strtolower($sSoWorkerEmail);
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_soworkers WHERE sEmail = "'.$sSoWorkerEmail.'" AND idParent = '.UserControl::GetUserLoginId());
			DbConnect::Log('Бахнули: DELETE FROM Arm_soworkers WHERE sEmail = "'.$sSoWorkerEmail.'" AND idParent = '.UserControl::GetUserLoginId(), "tariff_change_del_sowork");
		}

		//Подключение соворкера по ключу
		public static function SetSoWorker($sSoWorkerKey)
		{
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_soworkers (`idParent`,`idChild`) VALUES ('.UserControl::GetUserLoginId().','.$sSoWorkerKey.')');
		}

		//Удаление соворкера по ключу
		public static function DelSoWorker($sSoWorkerKey)
		{
			UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_soworkers WHERE idParent = '.UserControl::GetUserLoginId().' AND idChild = '.$sSoWorkerKey);
		}

		//Проверка возможности создания соворкеров
		public static function IsCanAddSoWorkers()
		{
			$bCanAdd = 'false';
			if ((int)UserTariff::GetSoWorkersCount()<(int)UserTariff::GetTariffSoWorkers())
			{
				$bCanAdd = 'true';
			}
			return $bCanAdd;
		}

		//Возврат количества текущих и приглашенных соворкеров
		public static function GetSoWorkersCount()
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_soworkers WHERE idParent = '.UserControl::GetUserLoginId());
			return mysql_num_rows($vResult);
		}

		//Получение возможного количества соворкеров по тарифу
		public static function GetTariffSoWorkers()
		{
			$iSoWorkersNum = UserControl::GetUserFieldValueNonCrypt('iTariffSoWorkers');
			return $iSoWorkersNum;
		}

		//Получение списка совроркеров
		public static function GetSoWorkersResult()
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, idParent, idChild, sEmail FROM Arm_soworkers WHERE idParent = '.UserControl::GetUserLoginId());
			return $vResult;
		}

		public static function IsDoubleAddEmail($sEmail)
		{
			$sEmail = strtolower($sEmail);
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id FROM Arm_soworkers WHERE idParent = '.UserControl::GetUserLoginId().' AND sEmail = "'.$sEmail.'"');
			if (mysql_num_rows($vResult)>0) {return true;} else {return false;}
		}

		public static function IsDoubleAddKey($sUserKey)
		{       $sSql = 'SELECT id FROM Arm_soworkers WHERE idParent = '.UserControl::GetUserLoginId().' AND idChild = '.$sUserKey;
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sSql);
			if (mysql_num_rows($vResult)>0) {return true;} else {return false;}
		}


	}
?>

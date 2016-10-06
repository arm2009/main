<?php
	session_start();

	include_once('LowLevel/userValidator.php');
	include_once('Util/String.php');

	class UserControl {
		public static $sSalt = '04022009';

		public static function GetSalt()
		{
			return UserControl::$sSalt;
		}


		public static function Register($sName, $sPassword, $sPhone, $sName1, $sName2)
		{
                    //Получение из базы значения зашифрованного имени
					$sName = DbConnect::ToBaseStr($sName);
					$sName = strtolower($sName);
                    $vResult = DbConnect::GetSqlQuery("SELECT sName FROM Arm_users WHERE sName = ENCODE('" . $sName . "','".UserControl::$sSalt."')");
                    //Проверка на дублирование
                    if (mysql_num_rows($vResult) > 0)
                    {
                        return 'Double name';
                    }
					else
					{
                        if (iconv_strlen($sName) > 0)
                        {
                            //Хэширование нового пароля
                            $sPassword = md5($sPassword);
                            $sPhone = DbConnect::ToBaseStr($sPhone);
                            $sName1 = DbConnect::ToBaseStr($sName1);
                            $sName2 = DbConnect::ToBaseStr($sName2);
							$dNowDate = date('Y-m-d H:i:s');$dNowDate = date_create($dNowDate);
                            //Кодирование и запись в базу данных нового пользователя
			    $sSql = "INSERT INTO Arm_users (sName, sPassword, sPhone, sName1, sTariffName, sName2, dCreateDate, sOrgName, iTariffSoWorkers) VALUES ("
                                    . "ENCODE('" . $sName . "', '" .UserControl::$sSalt. "'),'"
                                    . $sPassword ."',"
                                    . "ENCODE('" . $sPhone . "', '" .UserControl::$sSalt. "'),"
									. "ENCODE('" . $sName1 . "', '" .UserControl::$sSalt. "'),"
									. "'Base',"
									. "ENCODE('" . $sName2 . "', '" .UserControl::$sSalt. "'),"
									. "'".$dNowDate->format('Y-m-d')."',ENCODE('Моя организация проводящая СУОТ','".UserControl::$sSalt."'), 10000)";
                            DbConnect::GetSqlQuery($sSql);

                            //Получение ключа нового пользователя
                            $iInsertedKey = mysql_insert_id();

							//Простановка автоприглашений
							$vResultSW = DbConnect::GetSqlQuery("SELECT id FROM Arm_soworkers WHERE sEmail='".$sName."'");
							if (mysql_num_rows($vResultSW)>0)
							{
								while($vRow = mysql_fetch_array($vResultSW))
								{
									DbConnect::GetSqlQuery("UPDATE Arm_soworkers SET sEmail = '', idChild = ".$iInsertedKey." WHERE id = ".$vRow['id']);
								}
							}

                        	DbConnect::Log("Пользователь ".$sName." зарегестрирован.", "user_reg", $iInsertedKey);
                            return $iInsertedKey;
                        } else {
                            return 'Wrong name';
                        }
                    }
		}

				public static function IsLoginExist($sUserName)
				{
					$bExist = false;
					$sUserName = strtolower($sUserName);
					$sUserName = DbConnect::ToBaseStr($sUserName);
//                  $sUserName = mysql_real_escape_string($sUserName);
                    //Получение строки пользователя с введенным именем
					$sSql = "SELECT id, sPassword FROM Arm_users WHERE sName = ENCODE('" . $sUserName . "','" .UserControl::$sSalt. "')";
                    $vResult = DbConnect::GetSqlQuery($sSql);
					if (mysql_num_rows($vResult) > 0)
					{
						$bExist = true;
					}
					return $bExist;
				}

				public static function IsLoginExistReturnId($sUserName)
				{
					$iId = -1;
					$sUserName = strtolower($sUserName);
					$sUserName = DbConnect::ToBaseStr($sUserName);
                    //Получение строки пользователя с введенным именем
                    $vResult = DbConnect::GetSqlQuery("SELECT id FROM Arm_users WHERE sName = ENCODE('" . $sUserName . "','" .UserControl::$sSalt. "')");
					if (mysql_num_rows($vResult) > 0)
					{
						$iId = (int)mysql_result($vResult, 0, 0);
					}
					return $iId;
				}

		//Выход пользователя
		public static function Logout()
		{
			setcookie("sUserKey", "", time()-1209600);
			setcookie("sUserHash2", "", time()-1209600);
			unset($_SESSION['sUserKey']);
			unset($_SESSION['sUserHash2']);
		}

		//Вход пользователя
                public static function Login($sUserName, $sUserPassword, $bIsSelfMachine = true)
                {
			$sUserName = strtolower($sUserName);
			$sUserName = DbConnect::ToBaseStr($sUserName);
					//$sUserName = mysql_real_escape_string($sUserName);
                    //Получение строки пользователя с введенным именем
                    $vResult = DbConnect::GetSqlQuery("SELECT id, sPassword FROM Arm_users WHERE sName = ENCODE('" . $sUserName . "','" .UserControl::$sSalt. "')");
                    if (mysql_num_rows($vResult) > 0)
                    {
                        $sPassword = mysql_result($vResult, 0, 1);
                        //Проверка на совпадение хэшей паролей
                        if (md5($sUserPassword) == $sPassword)
                        {
                            $iUserKey = mysql_result($vResult, 0, 0);
                            //Создание временного (второго) хэша пароля в базе с случайным ключем
                            $sHash2 = md5($sPassword.rand());
                            //Запись второго хэша
                            DbConnect::GetSqlQuery("UPDATE Arm_users SET sHash2 = '".$sHash2."' WHERE id = ".$iUserKey);
                            //Получение закодированного ключа вошедшего пользователя
                            $sUserKeyCrypt = DataCrypt::Encode($iUserKey);
			    //echo $sUserKeyCrypt;
			//Выбор записи в куки или сессии
                        if ($bIsSelfMachine)
			{
                            setcookie("sUserKey", (string)$sUserKeyCrypt, time()+1209600);
                            setcookie("sUserHash2", $sHash2, time()+1209600);
			}
			else
			{
				$_SESSION['sUserKey'] = (string)$sUserKeyCrypt;
				$_SESSION['sUserHash2'] = $sHash2;
			}
				DbConnect::Log("Пользователь ".$sUserName." вошел в систему.", "user_login", $iUserKey);

                            return $sUserKeyCrypt;
                        }
			else
			{
				return 'false';
			}
                    } else {
						DbConnect::Log("Неудачная попытка пользователя ".$sUserName." войти в систему.", "user_login_err");
                        return 'false';
                    }
                }

				//Изменение значений пользователя
                public static function ChangeUserData($sFieldName, $sFieldValue)
                {
                    $bDone = false;
                    $sUserKeyCrypt = UserControl::GetUserLoginIdCrypt();
					$sUserHash2 = UserControl::GetUserHash2();
                    $sFieldName = addslashes($sFieldName);
                    //$sFieldValue = addslashes($sFieldValue);
		   			$sFieldValue = DbConnect::ToBaseStr($sFieldValue);
                    //echo $sUserKeyCrypt.$sUserHash2;
                    $bUserValid = UserValidator::isUserValidCrypt($sUserKeyCrypt, $sUserHash2);
                    if ($bUserValid)
                    {
                        $sUserKey = DataCrypt::Encode($sUserKeyCrypt);
                        $sKeyCrypt = DataCrypt::$KeyCrypt;
                        DbConnect::GetSqlQuery("UPDATE Arm_users SET ".$sFieldName." = ENCODE('".$sFieldValue."','" . UserControl::$sSalt . "') WHERE id = ".$sUserKey);
						DbConnect::Log("Изменение пользовательских данных. Значение поля ".$sFieldName." изменено.", "user_login_err", $sUserKey);
                        $bDone = true;
                    }
                    return $bDone;
                }

		//Изменение значений пользователя
                public static function ChangeUserDataNonCrypt($sFieldName, $sFieldValue, $bString = true, $sUserId = '-1')
                {
                    $bDone = false;
                    $sFieldName = addslashes($sFieldName);
                    $sFieldValue = addslashes($sFieldValue);
		    $sFieldValue = DbConnect::ToBaseStr($sFieldValue);

			if ($sUserId = '-1')
			{
                    $sUserKeyCrypt = UserControl::GetUserLoginIdCrypt();
		    $sUserHash2 = UserControl::GetUserHash2();

                    $bUserValid = UserValidator::isUserValidCrypt($sUserKeyCrypt, $sUserHash2);
                    if ($bUserValid)
                    {
                        $sUserKey = DataCrypt::Encode($sUserKeyCrypt);
                        $sKeyCrypt = DataCrypt::$KeyCrypt;
						if ($bString) //Использовать ли кавычки в запросе
						{
	                        			DbConnect::GetSqlQuery("UPDATE Arm_users SET ".$sFieldName." = '".$sFieldValue."' WHERE id = ".$sUserKey);
						}
						else
						{
							DbConnect::GetSqlQuery("UPDATE Arm_users SET ".$sFieldName." = ".$sFieldValue." WHERE id = ".$sUserKey);
						}
						//DbConnect::Log("Изменение пользовательских данных. Значение поля ".$sFieldName." изменено.", "user_login_err", $sUserKey);
                        $bDone = true;
                    }
			}
			else
			{
				if ($bString) //Использовать ли кавычки в запросе
						{
	                        			DbConnect::GetSqlQuery("UPDATE Arm_users SET ".$sFieldName." = '".$sFieldValue."' WHERE id = ".$sUserId);
						}
						else
						{
							DbConnect::GetSqlQuery("UPDATE Arm_users SET ".$sFieldName." = ".$sFieldValue." WHERE id = ".$sUserId);
						}
			}
                    return $bDone;
                }

				//Создание кода, для временной сслыки по восстановлению пароля
				public static function GenerateRestoreCode($sUserLogin)
				{
					$sUserHash = md5($sUserLogin.rand());
					$sUserLogin = strtolower($sUserLogin);
					$sUserLogin = DbConnect::ToBaseStr($sUserLogin);
					DbConnect::GetSqlQuery("UPDATE Arm_users SET sRestoreCode = '".$sUserHash."', dRestoreDate = '".date('Y:m:d G:i:s')."' WHERE sName = ENCODE('".$sUserLogin."', '".UserControl::$sSalt."')");
					DbConnect::Log("Пользователь ".$sUserLogin." запросил восстановление пароля.", "user_restore");
					return $sUserHash;
				}

				//Восстановление пароля, по сути изменение
				public static function RestorePassword($sUserTempHash, $sNewPassword)
				{
					$sUserPassword = md5($sNewPassword);
					DbConnect::GetSqlQuery("UPDATE Arm_users SET sPassword = '".$sUserPassword."', sRestoreCode = '' WHERE sRestoreCode = '".$sUserTempHash."'");
					DbConnect::Log("Пользователь восстановил пароль.", "user_restore");
				}

				//Проверка кода временной ссылки на действительность
				public static function IsRestored($sHash)
				{
					$bCanRestore = false;
					$vResult = DbConnect::GetSqlQuery("SELECT id, sRestoreCode, dRestoreDate FROM Arm_users WHERE sRestoreCode = '".$sHash."'");
					if (mysql_num_rows($vResult)>0)
					{
						//Расчет разницы в датах
						$dNowDate = date('Y-m-d H:i:s');
						$dNowDate =	date_create($dNowDate);
						$dBaseDate = mysql_result($vResult, 0, 2);

						$delta = (strtotime($dBaseDate) - strtotime($dNowDate->format('Y-m-d')))/3600/24;

						if ($delta < 3)
						{
							$bCanRestore = true;
						}
						else
						{
							DbConnect::Log("Неудачная попытка доступа к просроченно временной странице восстановления пароля.", "user_restore_err");
						}
					}
					else
					{
						DbConnect::Log("Неудачная попытка доступа к временной странице восстановления пароля по не существующей hash сумме.", "user_restore_err");
					}
					return $bCanRestore;
				}
		//Получение id пользователя из куков или сессий
		public static function GetUserLoginId()
		{
			$iUserId = -1;
			if(isset($_COOKIE['sUserKey']))
			{
				UserValidator::isUserValidCrypt($_COOKIE['sUserKey'],$_COOKIE['sUserHash2']);
				$iUserId = DataCrypt::Encode($_COOKIE['sUserKey']);
			}
			if (isset($_SESSION['sUserKey']))
			{
				UserValidator::isUserValidCrypt($_SESSION['sUserKey'],$_SESSION['sUserHash2']);
				$iUserId = DataCrypt::Encode($_SESSION['sUserKey']);
			}
			return $iUserId;
		}

		//Получение закодированного id пользователя из куков или сессий
		public static function GetUserLoginIdCrypt()
		{
			$iUserId = -1;

			if(isset($_COOKIE['sUserKey']))
			{
//echo('111');
//				echo $_COOKIE['sUserKey'];
				UserValidator::isUserValidCrypt($_COOKIE['sUserKey'],$_COOKIE['sUserHash2']);
				$iUserId = $_COOKIE['sUserKey'];
			}
			if (isset($_SESSION['sUserKey']))
			{
//echo('222');
				UserValidator::isUserValidCrypt($_SESSION['sUserKey'],$_SESSION['sUserHash2']);
				$iUserId = $_SESSION['sUserKey'];
			}
//echo('123');
			return $iUserId;
		}

		//Получение hash2 пользователя из куков или сессий
		public static function GetUserHash2()
		{
			$sUserHash2 = -1;
			if(isset($_COOKIE['sUserHash2']))
			{
				UserValidator::isUserValidCrypt($_COOKIE['sUserKey'],$_COOKIE['sUserHash2']);
				$sUserHash2 = $_COOKIE['sUserHash2'];
			}
			if (isset($_SESSION['sUserHash2']))
			{
				UserValidator::isUserValidCrypt($_SESSION['sUserKey'],$_SESSION['sUserHash2']);
				$sUserHash2 = $_SESSION['sUserHash2'];
			}
			return $sUserHash2;
		}

		//Проверка, осуществлен ли вход
		public static function IsLogin()
		{
			if (isset($_COOKIE['sUserKey']) || isset($_SESSION['sUserKey']))
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		//Возврат значения ячейки базы данных для текущего пользователя
		public static function GetUserFieldValue($sFieldName)
		{

			$sQuery = "SELECT DECODE(".$sFieldName.", '".UserControl::$sSalt."') FROM Arm_users WHERE id = ".UserControl::GetUserLoginId();
//			DbConnect::Log("SELECT DECODE".$sFieldName.", '04022009' FROM Arm_users WHERE id = ".UserControl::GetUserLoginId(), "user_reg", $iInsertedKey);
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sQuery);
			if ($vResult)
			{
				return mysql_result($vResult, 0, 0);
			}
		}

		//Возврат значения ячейки базы данных для заданного пользователя
		public static function GetUserFieldValueFromId($sFieldName, $sUserId)
		{

			if($sUserId <> '')
			{
			$sQuery = "SELECT DECODE(".$sFieldName.", '".UserControl::$sSalt."') FROM Arm_users WHERE id = ".$sUserId;
//			DbConnect::Log("SELECT DECODE".$sFieldName.", '04022009' FROM Arm_users WHERE id = ".UserControl::GetUserLoginId(), "user_reg", $iInsertedKey);
			$vResult = DbConnect::GetSqlQuery($sQuery);
			if ($vResult)
			{
				return mysql_result($vResult, 0, 0);
			}
			}
			else
			{
				return '';
			}
		}

		//Возврат не кодированного значения ячейки базы данных для текущего пользователя
		public static function GetUserFieldValueNonCrypt($sFieldName)
		{
			$sQuery = "SELECT ".$sFieldName." FROM Arm_users WHERE id = ".UserControl::GetUserLoginId();
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sQuery);
			if ($vResult)
			{
				return mysql_result($vResult, 0, 0);
			}
		}

		public static function isUserValidExit()
		{
			//Проверка срока действия тарифа
			if (!UserValidator::isUserValidCrypt(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2()))
			{
				header ('Location: index.php');
				exit();
			}
		}

		public static function isUserValid()
		{
			$bValid = false;
			if (UserValidator::isUserValidCrypt(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2()))
			{
				$bValid = true;
			}
			return $bValid;
		}

		//Возврат даты создания в ввиде XX.XxxX.XX
		public static function GetUserDataCreate()
		{
			$dDateCreate = date_create(UserControl::GetUserFieldValueNonCrypt('dCreateDate'));
			$sDateCreate = StringWork::DateFormatFull($dDateCreate);
			return $sDateCreate;
		}

		public static function GetUserName($iUserKey)
		{
			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), "SELECT DECODE(sName1, '".UserControl::$sSalt."'), DECODE(sName2, '".UserControl::$sSalt."') FROM Arm_users WHERE id =".$iUserKey);
			return mysql_result($vResult, 0, 0).' '.mysql_result($vResult, 0, 1);
		}
	}

?>

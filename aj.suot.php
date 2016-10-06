<?php
	include_once('UserControl/userControl.php');
	include_once('LowLevel/emailSend.php');
	include_once('UserControl/userTariff.php');
	include_once('LowLevel/userValidator.php');
	include_once('Util/String.php');
		
			if ($_POST['action'] == 'addInfoAcr' && isset($_POST['id']) && isset($_POST['idGroup']))
			{
				echo SuotWork::CopyOrg($_POST['id'], $_POST['idGroup'], 'acredit');
			}
			
			if ($_POST['action'] == 'delInfoAcr' && isset($_POST['id']))
			{
				SuotWork::DelOrg($_POST['id'], 'acredit');
			}

			if ($_POST['action'] == 'addInfoDev' && isset($_POST['id']) && isset($_POST['idGroup']))
			{
				echo SuotWork::CopyOrg($_POST['id'], $_POST['idGroup'], 'device');
			}
			
			if ($_POST['action'] == 'delInfoDevice' && isset($_POST['id']))
			{
				SuotWork::DelOrg($_POST['id'], 'device');
			}

			if ($_POST['action'] == 'addInfoStuff' && isset($_POST['id']) && isset($_POST['idGroup']))
			{
				echo SuotWork::CopyOrg($_POST['id'], $_POST['idGroup'], 'stuff');				
			}
			
			if ($_POST['action'] == 'addInfoExpert' && isset($_POST['id']) && isset($_POST['idGroup']))
			{
				echo SuotWork::CopyOrg($_POST['id'], $_POST['idGroup'], 'expert');				
			}
			
			if ($_POST['action'] == 'delInfoStuff' && isset($_POST['id']))
			{
				SuotWork::DelOrg($_POST['id'], 'stuff');
			}

		if (isset($_POST['action']) && isset($_POST['id']))
		{
			//Чтение акредитации из базы
			if ($_POST['action'] == 'arcOpen')
			{
				$vResult = $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, dDateCreate, dDateFinish FROM Arm_acredit WHERE id ='.$_POST['id']);
				$result = array();
				$result['sName'] = mysql_result($vResult, 0,0);
				$result['dDateCreate'] = StringWork::DateFormatLite(new DateTime(mysql_result($vResult, 0,1)));
				$result['dDateFinish'] = StringWork::DateFormatLite(new DateTime(mysql_result($vResult, 0,2)));
				
				echo json_encode($result);
			}

			//Удаление акредитации из базы
			if ($_POST['action'] == 'acrDel')
			{
				$vResult = $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_acredit WHERE id ='.$_POST['id']);				
			}
			
			//Чтение сотрудника из базы
			if ($_POST['action'] == 'stuffOpen')
			{
				$vResult = $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, sSertNum, dSertDate, sPost, sReestrNum FROM Arm_stuff WHERE id ='.$_POST['id']);
				$result = array();
				$result['sName'] = mysql_result($vResult, 0,0);
				$result['sSertNum'] = mysql_result($vResult, 0,1);
				$result['dSertDate'] = StringWork::DateFormatLite(new DateTime(mysql_result($vResult, 0,2)));
				$result['sPost'] = mysql_result($vResult, 0,3);
				$result['sReestrNum'] = mysql_result($vResult, 0,4);
				
				echo json_encode($result);
			}

			//Удаление сотрудника из базы
			if ($_POST['action'] == 'stuffDel')
			{
				$vResult = $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_stuff WHERE id ='.$_POST['id']);				
			}
			
			//Чтение устройства из базы
			if ($_POST['action'] == 'deviceOpen')
			{
				$vResult = $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, sReestrNum, dCheckDate, sCheckNum, sFactoryNum, sFactName, sMethodName FROM Arm_devices WHERE id ='.$_POST['id']);
				$result = array();
				$result['sName'] = mysql_result($vResult, 0,0);
				$result['sReestrNum'] = mysql_result($vResult, 0,1);
				$result['dCheckDate'] = StringWork::DateFormatLite(new DateTime(mysql_result($vResult, 0,2)));
				$result['sCheckNum'] = mysql_result($vResult, 0,3);
				$result['sFactoryNum'] = mysql_result($vResult, 0,4);
				$result['sFactName'] = mysql_result($vResult, 0,5);
				$result['sMethodName'] = mysql_result($vResult, 0,6);
				
				echo json_encode($result);
			}

			//Удаление устройства из базы
			if ($_POST['action'] == 'deviceDel')
			{
				$vResult = $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_devices WHERE id ='.$_POST['id']);				
			}
		}
//---------------------------------------------------------------------------------------		
	if (isset($_POST['sDeviceName']))
	{
		//Добавление девайса в базу
		if ($_POST['action'] == 'addDevice')
		{
				$dDeviceCheckDate = new DateTime($_POST['dDeviceCheckDate']);
				$sDeviceCheckDate = $dDeviceCheckDate->format('Y-m-d');
				
				$vResultEx = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT * FROM Arm_devices WHERE sName LIKE "'.$_POST['sDeviceName'].'" AND idParent = '.UserControl::GetUserLoginId());
				
				if (mysql_num_rows($vResultEx) == 0)
				{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_devices (sName, sReestrNum, dCheckDate, sCheckNum, idParent, sFactoryNum, sFactName, sMethodName) VALUES ("'.$_POST['sDeviceName'].'","'.$_POST['sDeviceReestrNum'].'","'.$sDeviceCheckDate.'","'.$_POST['sDeviceCheckNum'].'","'.UserControl::GetUserLoginId().'", "'.$_POST['sFactoryNum'].'", "'.$_POST['sFactName'].'", "'.$_POST['sMethodName'].'")');
					$sIdTag = mysql_insert_id();
					echo SuotWork::AddDivDevice($_POST['sDeviceName'],  $_POST['sDeviceReestrNum'], $_POST['dDeviceCheckDate'], $_POST['sDeviceCheckNum'], $sIdTag);
				}
				else
				{
					echo 'double';
				}
		}
			
		//Редактирование девайса
		if ($_POST['action'] == 'editDevice' && isset($_POST['id']))
		{
				$dCheckDate = new DateTime($_POST['dDeviceCheckDate']);
				$sBaseCheckDate = $dCheckDate->format('Y-m-d');
				
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_devices SET sName="'.$_POST['sDeviceName'].'", sReestrNum = "'.$_POST['sDeviceReestrNum'].'", dCheckDate="'.$sBaseCheckDate.'", sCheckNum = "'.$_POST['sDeviceCheckNum'].'", sFactoryNum = "'.$_POST['sFactoryNum'].'", sFactName = "'.$_POST['sFactName'].'", sMethodName = "'.$_POST['sMethodName'].'" WHERE id = '.$_POST['id']);
				echo SuotWork::AddDivDevice($_POST['sDeviceName'],  $_POST['sDeviceReestrNum'], $_POST['dDeviceCheckDate'], $_POST['sDeviceCheckNum'], $_POST['id'], 'display: yes;');
		}
	}
//-----------------------------------------------------------------------------------------------
		if (isset($_POST['sStuffName']))
		{
			//Добавление сотрудника в базу
			if ($_POST['action'] == 'addStuff')
			{
				$dStuffSertDate = new DateTime($_POST['dStuffSertDate']);
				$sStuffSertDate = $dStuffSertDate->format('Y-m-d');
				
				$vResultEx = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT * FROM Arm_stuff WHERE sName LIKE "'.$_POST['sStuffName'].'" AND idParent = '.UserControl::GetUserLoginId());
				
				if (mysql_num_rows($vResultEx) == 0)
				{
				
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_stuff (sName, sSertNum, dSertDate, sPost, sReestrNum, idParent) VALUES ("'.$_POST['sStuffName'].'","'.$_POST['sStuffSertNum'].'","'.$sStuffSertDate.'","'.$_POST['sStuffPost'].'","'.$_POST['sStuffReestrNum'].'","'.UserControl::GetUserLoginId().'")');
					$sIdTag = mysql_insert_id();
					echo SuotWork::AddDivStuff($_POST['sStuffName'], $_POST['sStuffPost'], $_POST['dStuffSertDate'], $_POST['sStuffSertNum'], $_POST['sStuffReestrNum'], $sIdTag);
				}
				else
				{
					echo 'double';
				}
				
			}
			
			
			//Редактирование сотрудника
			if ($_POST['action'] == 'editStuff' && isset($_POST['id']))
			{
				$dSertDate = new DateTime($_POST['dStuffSertDate']);
				$sBaseSertDate = $dSertDate->format('Y-m-d');
				
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_stuff SET sName="'.$_POST['sStuffName'].'", sSertNum = "'.$_POST['sStuffSertNum'].'", dSertDate="'.$sBaseSertDate.'", sPost = "'.$_POST['sStuffPost'].'", sReestrNum = "'.$_POST['sStuffReestrNum'].'" WHERE id = '.$_POST['id']);
				if(strlen(trim($_POST['sStuffSertNum'])) > 0)
				echo $_POST['sStuffName'].'<br /><span class="comment">'.$_POST['sStuffPost'].'<br />Сертификат эксперта № '.$_POST['sStuffSertNum'].' от '.$_POST['dStuffSertDate'].' г., номер в реестре '.$_POST['sStuffReestrNum'].'</span>';
				else
				echo $_POST['sStuffName'].'<br /><span class="comment">'.$_POST['sStuffPost'].'</span>';
			}
		}
		//-----------------------------------------------------------------------------------------------
		if (isset($_POST['sAcrName']) && isset($_POST['sAcrDateCreate']) && isset($_POST['sAcrDateFinish']))
		{
			//Добавление акредитации в базу
			if ($_POST['action'] == 'addArc')
			{
				$dAcrDateFinish = new DateTime($_POST['sAcrDateFinish']);
				$sBaseAcrDateFinish = $dAcrDateFinish->format('Y-m-d');
				
				$dAcrDateCreate = new DateTime($_POST['sAcrDateCreate']);
				$sBaseAcrDateCreate = $dAcrDateCreate->format('Y-m-d');
				
				$vResultEx = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT * FROM Arm_acredit WHERE sName LIKE "'.$_POST['sAcrName'].'" AND idParent = '.UserControl::GetUserLoginId());
				
				if (mysql_num_rows($vResultEx) == 0)
				{			
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_acredit (sName, dDateCreate, dDateFinish, idParent) VALUES ("'.$_POST['sAcrName'].'","'.$sBaseAcrDateCreate.'","'.$sBaseAcrDateFinish.'","'.UserControl::GetUserLoginId().'")');
					$sIdTag = mysql_insert_id();
					echo SuotWork::AddDivAcredit($_POST['sAcrName'], $_POST['sAcrDateCreate'], $_POST['sAcrDateFinish'], $sIdTag);
				}
				else
				{
					echo 'double';
				}		
			}
			//Редактирование акредитации
			if ($_POST['action'] == 'editArc' && isset($_POST['id']))
			{
				$dAcrDateFinish = new DateTime($_POST['sAcrDateFinish']);
				$sBaseAcrDateFinish = $dAcrDateFinish->format('Y-m-d');
				
				$dAcrDateCreate = new DateTime($_POST['sAcrDateCreate']);
				$sBaseAcrDateCreate = $dAcrDateCreate->format('Y-m-d');
				
				UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'UPDATE Arm_acredit SET sName="'.$_POST['sAcrName'].'", dDateCreate="'.$sBaseAcrDateCreate.'", dDateFinish="'.$sBaseAcrDateFinish.'" WHERE id = '.$_POST['id']);
				echo $_POST['sAcrName'].'<br /><span class="comment">Действителен до '.$_POST['sAcrDateFinish'].' г.</span>';
			}
			

		}

	
	class SuotWork
	{
		//Добавление дива акредитации
		public static function AddDivAcredit($sName, $sDateCreate, $sDateFinish, $sId, $sStyleDisplay = 'display: none;')
		{
			return '<div style="'.$sStyleDisplay.'"  class="block block_left_round block_right_round block_accreditation pointer block_edit" tag ="'.$sId.'" title="Изменить сведения" id="popup" onClick="ClickEditAcredit(this)">'.$sName.'<br />
<span class="comment">Действителен до '.$sDateFinish.' г.</span></div>';
		}
		
		//Добавление дива сотрудника
		public static function AddDivStuff($sName, $sPost, $sSertDate, $sSertNumber, $sReestrNum, $sId, $sStyleDisplay = 'display: none;')
		{
			if ($sSertNumber != '')
			{
				return '<div style="'.$sStyleDisplay.'"  class="block block_left_round block_right_round block_user pointer block_edit" tag ="'.$sId.'" title="Изменить сведения" id="popup" onClick="ClickEditStuff(this)">'.$sName.'<br /><span class="comment">'.$sPost.'<br />
				  Сертификат эксперта № '.$sSertNumber.' от '.$sSertDate.' г., номер в реестре '.$sReestrNum.'</span></div>';
			} else
			{
				return '<div style="'.$sStyleDisplay.'"  class="block block_left_round block_right_round block_user pointer block_edit" tag ="'.$sId.'" title="Изменить сведения" id="popup" onClick="ClickEditStuff(this)">'.$sName.'<br /><span class="comment">'.$sPost.'<br />
				  </span></div>';
			}
		}
		
		//Добавление дива устройства
		public static function AddDivDevice($sName, $sReestrNum, $sCheckDate, $sCheckNum, $sId, $sStyleDisplay = 'display: none;')
		{
			if ($sReestrNum=='' || $sReestrNum==null) {$sReestrNum=' - ';}
			$dDate = new DateTime((string)$sCheckDate);
			$dNowDate  = new DateTime();
			$sInterval = date_diff($dDate, $dNowDate);
			if ((int)($sInterval->format('%R%a'))>0) { $sStyleDisplay = $sStyleDisplay.'color:#B22222;';}
			return '<div style="'.$sStyleDisplay.'" class="block block_left_round block_right_round block_box pointer block_edit" tag ="'.$sId.'" title="Изменить сведения" id="popup" onClick="ClickEditDevice(this)">'.$sName.'<br />
<span class="comment">№ '.$sReestrNum.' в государственном реестре средств измерений<br />Свидетельство о поверке № '.$sCheckNum.', до '.$sCheckDate.' г.</span></div>';
		}
		
		public static function GetDivsAccredit()
		{
			$sReturnDivs = '';
 			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, dDateCreate, dDateFinish FROM Arm_acredit WHERE idParent ='.UserControl::GetUserLoginId());

			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{

					$dDateCreate = StringWork::DateFormatLite(new DateTime($vRow['dDateCreate']));
					$dDateFinish = StringWork::DateFormatLite(new DateTime($vRow['dDateFinish']));
					$sReturnDivs = $sReturnDivs.SuotWork::AddDivAcredit($vRow['sName'], $dDateCreate, $dDateFinish, $vRow['id'], 'display: yes;');
					//echo $sReturnDivs;
				}
			}
			return $sReturnDivs;
			
		}
		
		public static function GetDivsStuff()
		{
			$sReturnDivs = '';
 			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sPost, sSertNum, dSertDate, sReestrNum FROM Arm_stuff WHERE idParent ='.UserControl::GetUserLoginId());
			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$sSertDate = StringWork::DateFormatLite(new DateTime($vRow['dSertDate']));
					$sReturnDivs = $sReturnDivs.SuotWork::AddDivStuff($vRow['sName'], $vRow['sPost'], $sSertDate, $vRow['sSertNum'], $vRow['sReestrNum'], $vRow['id'], 'display: yes;');
				}
			}
			return $sReturnDivs;
		}
		
		public static function GetDivsDevice()
		{
			$sReturnDivs = '';
 			$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sReestrNum, dCheckDate, sCheckNum FROM Arm_devices WHERE idParent ='.UserControl::GetUserLoginId());
			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$dCheckDate = StringWork::DateFormatLite(new DateTime($vRow['dCheckDate']));
					$sReturnDivs = $sReturnDivs.SuotWork::AddDivDevice($vRow['sName'], $vRow['sReestrNum'], $dCheckDate, $vRow['sCheckNum'], $vRow['id'], 'display: yes;');
//					echo '!!';
				}
			}
			return $sReturnDivs;
		}
		
		public static function AddInfoStuffDiv($id, $sName, $sPost, $bVisible = false)
		{
			if ($bVisible)
			{
				$sDisplay = 'display: yes;';
			}
			else
			{
				$sDisplay = 'display: none;';
			}
			return '<div style="'.$sDisplay.'" tag="'.$id.'" class="block block_left_round block_right_round block_user pointer block_delete" onClick="ClickDelDevInfoStuff(this)" title="Удалить сведения">'.$sName.'<br />
	      <span class="comment">'.$sPost.'<br />
	      </span></div>';
		}
		
		public static function AddInfoAcrDiv($id, $sName, $sFDate, $bVisible = false)
		{
			if ($bVisible)
			{
				$sDisplay = 'display: yes;';
			}
			else
			{
				$sDisplay = 'display: none;';
			}
			return '<div style="'.$sDisplay.'" tag="'.$id.'" class="block block_left_round block_right_round block_accreditation pointer block_delete" onClick="ClickDelDevInfoAcr(this)" title="Удалить сведения">'.$sName.'<br />
      <span class="comment">Действителен до '.$sFDate.' г.</span></div>';
		}
		
		public static function AddInfoDevDiv($id, $sName, $sReestrNum, $sCheckNum, $sCheckDate, $bVisible = false)
		{
			if ($bVisible)
			{
				$sDisplay = 'display: yes;';
			}
			else
			{
				$sDisplay = 'display: none;';
			}
			return '<div style="'.$sDisplay.'" tag="'.$id.'" class="block block_left_round block_right_round block_box pointer block_delete" onClick="ClickDelDevInfoDev(this)" title="Удалить сведения">'.$sName.'<br />
	      <span class="comment">№ '.$sReestrNum.' в государственном реестре средств измерений<br />
	        Свидетельство о поверке № '.$sCheckNum.', до '.$sCheckDate.' г.</span></div>';
		}
		
		public static function CopyOrg($id, $idGroup, $sType)
		{
			switch ($sType)
			{
				case 'device':
				{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, sReestrNum, dCheckDate, sCheckNum, sFactoryNum, sFactName, sMethodName FROM Arm_devices WHERE id ='.$id);
					
					$sName = mysql_result($vResult, 0,0);
					
					$sql2 = 'SELECT * FROM Arm_groupDevices WHERE idGroup = '.$idGroup.' AND sName LIKE "'.$sName.'";';
					$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
								
					if (mysql_num_rows($vResult2) == 0)
					{
					
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_groupDevices (sName, sReestrNum, dCheckDate, sCheckNum, idGroup, sFactoryNum, sFactName, sMethodName) VALUES ("'.mysql_result($vResult, 0,0).'","'.mysql_result($vResult, 0,1).'","'.mysql_result($vResult, 0,2).'","'.mysql_result($vResult, 0,3).'",'.$idGroup.',"'.mysql_result($vResult, 0,4).'","'.mysql_result($vResult, 0,5).'","'.mysql_result($vResult, 0,6).'");');
					return SuotWork::AddInfoDevDiv(mysql_insert_id(), mysql_result($vResult, 0,0), mysql_result($vResult, 0,1), mysql_result($vResult, 0,3), mysql_result($vResult, 0,2));}
			break;
					}

				case 'stuff':
				{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, sPost, sSertNum, dSertDate, sReestrNum FROM Arm_stuff WHERE id ='.$id);
					
					$sName = mysql_result($vResult, 0,0);
					
					$sql2 = 'SELECT * FROM Arm_groupStuff WHERE idGroup = '.$idGroup.' AND sName LIKE "'.$sName.'" AND bExpert = 0;';
					$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
								
					if (mysql_num_rows($vResult2) == 0)
					{				
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_groupStuff (sName, sPost, sSertNum, dSertDate, sReestrNum, idGroup) VALUES ("'.mysql_result($vResult, 0,0).'", "'.mysql_result($vResult, 0,1).'", "'.mysql_result($vResult, 0,2).'", "'.mysql_result($vResult, 0,3).'", "'.mysql_result($vResult, 0,4).'",'.$idGroup.');');
					return SuotWork::AddInfoStuffDiv(mysql_insert_id(), mysql_result($vResult, 0,0), mysql_result($vResult, 0,1));
					}
					else
					{
											echo $sql2;
					}
			break;
				}
				case 'acredit':
				{
					
					
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, dDateCreate, dDateFinish FROM Arm_acredit WHERE id ='.$id);	
					$sName = mysql_result($vResult, 0,0);
					
					$sql2 = 'SELECT * FROM Arm_groupAcredit WHERE idGroup = '.$idGroup.' AND sName LIKE "'.$sName.'";';
					$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
								
					if (mysql_num_rows($vResult2) == 0)
					{
						UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_groupAcredit (sName, dDateCreate, dDateFinish, idGroup) VALUES ("'.$sName.'", "'.mysql_result($vResult, 0,1).'", "'.mysql_result($vResult, 0,2).'",'.$idGroup.');');
						return SuotWork::AddInfoAcrDiv(mysql_insert_id(), mysql_result($vResult, 0,0), mysql_result($vResult, 0,2));
					}
			break;
				}
				case 'expert':
				{
					$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT sName, sPost, sSertNum, dSertDate, sReestrNum FROM Arm_stuff WHERE id ='.$id);
					$sName = mysql_result($vResult, 0,0);
					
					$sql2 = 'SELECT * FROM Arm_groupStuff WHERE idGroup = '.$idGroup.' AND sName LIKE "'.$sName.'" AND bExpert = 1;';
					$vResult2 = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql2);
								
					if (mysql_num_rows($vResult2) == 0)
					{	
					
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'INSERT INTO Arm_groupStuff (sName, sPost, sSertNum, dSertDate, sReestrNum, idGroup, bExpert) VALUES ("'.mysql_result($vResult, 0,0).'", "'.mysql_result($vResult, 0,1).'", "'.mysql_result($vResult, 0,2).'", "'.mysql_result($vResult, 0,3).'", "'.mysql_result($vResult, 0,4).'",'.$idGroup.', 1);');
					return SuotWork::AddInfoStuffDiv(mysql_insert_id(), mysql_result($vResult, 0,0), mysql_result($vResult, 0,1));
					}
			break;
				}
			}
		}
		
		public static function DelOrg($id, $sType)
		{
			switch ($sType)
			{
				case 'device':
				{
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_groupDevices WHERE id='.$id.';');
			break;
				}
				case 'stuff':
				{
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_groupStuff WHERE id='.$id.';');
			break;
				}
				case 'acredit':
				{
					UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'DELETE FROM Arm_groupAcredit WHERE id='.$id.';');		
			break;
				}
			}
		}
	}
?>
<?php
	include_once('UserControl/userControl.php');
	include_once('LowLevel/emailSend.php');
	include_once('UserControl/userTariff.php');

	if (isset($_POST['action']))
	{
		echo (string)UserTariff::GetSoWorkersCount();
	}
	
	if (isset($_POST['emailAdd']))
	{
		$result = array();
		$iUserKey = (int)UserControl::IsLoginExistReturnId($_POST['emailAdd']);
		$sEmail = $_POST['emailAdd'];

		if ($iUserKey==-1)
		{
			if(!UserTariff::IsDoubleAddEmail($_POST['emailAdd']))
			{
				Email::CommunicationNewmail($sEmail, 'Приглашение ARM2009', 'Вас пригласили в Арм2009.');
				UserTariff::SetSoWorkerEmail($sEmail);
				$sDiv = userTariffPage::getDiv($sEmail);
				$result['email'] =$sDiv;
			}
			else
			{
				$result['error'] = 'exist';
			}
		}
		else
		{
			if(!UserTariff::IsDoubleAddKey($iUserKey))
			{
        	      		$sEmail = UserTariff::SetSoWorker($iUserKey);
				$sDiv = userTariffPage::getDiv($sEmail, 'none', $iUserKey);
				$result['user'] = $sDiv;
			}
			else
			{
				$result['error'] = 'exist';
			}

		}
		
		if (UserTariff::IsCanAddSoWorkers()=='false') {$result['full'] = 'true';}
		
		echo json_encode($result);
	}
	
	if (isset($_POST['userDel']))
	{
		UserTariff::DelSoWorker($_POST['userDel']);
		echo (string)UserTariff::IsCanAddSoWorkers();
	}
	
	if (isset($_POST['emailDel']))
	{
		UserTariff::DelSoWorkerEmail($_POST['emailDel']);
		echo UserTariff::IsCanAddSoWorkers();
	}

	if (isset($_POST['checkLogin']))
	{
		echo UserControl::GetUserFieldValue("sName");
	}
	
class userTariffPage
{
	function getDiv($sEmail,$sVisible = 'none', $sValue=-1)
	{
		if ($sValue==-1)
		{
			return '<div style="display: '.$sVisible.';" class="block block_left_round block_right_round block_mail pointer block_delete" value ="'.$sEmail.'" title="Отозвать приглашение" id="popup" onClick="deleteDivEmail(this)">'.$sEmail.'<br /><span class="comment">Не зарегистрирован как пользователь АРМ 2009 и получил приглашение от Вашего имени</span></div>';
		}
		else
		{
			return '<div style="display: '.$sVisible.';" class="block block_left_round block_right_round block_user pointer block_delete" value ="'.$sValue.'" title="Отозвать приглашение" id="popup" onClick="deleteDivUser(this)">'.UserControl::GetUserName($sValue).'<br /><span class="comment">Зарегистрирован в АРМ 2009 и имеет полный доступ к Вашему информационному пространству</span></div>';
		}
	}
	
	public static function getDivs()
	{
		$vResult = UserTariff::GetSoWorkersResult();
		$sResult = '';
		//$vResult = mysql_fetch_assoc($vResult);
		if (mysql_num_rows($vResult) > 0)
		{
			while($vRow = mysql_fetch_array($vResult))
			{
				if((int)$vRow['idChild'] > 0)
				{
					$sNameUser = UserControl::GetUserName($vRow['idChild']);
					$sResult = $sResult.userTariffPage::getDiv($sNameUser, "yes", $vRow['idChild']);
				}
				else
				{
					$sResult = $sResult.userTariffPage::getDiv($vRow['sEmail'], "yes");
				}
			}
			
		}
		return $sResult;
	}
}
?>
<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";

	if(isset($_POST['find']) && strlen($_POST['find'])>3)
	{
		$sql ="SELECT `Nd_ok01694`.`sName`, `Nd_ok01694`.`sCode`, `Nd_ok01694`.`id`, (`Nd_Etks`.`sName`) AS `sfEtks`, `Nd_ok01694`.`sKch`, `Nd_ok01694`.`sRazr`, `Nd_ok01694`.`sOkz`, `Nd_ok01694`.`sKat`, `Nd_ok01694`.`sEtks` FROM `Nd_ok01694`, `Nd_Etks` WHERE (`Nd_ok01694`.`sCode` LIKE '%".DbConnect::ToBaseStr($_POST['find'])."%' OR `Nd_ok01694`.`sName` LIKE '%".DbConnect::ToBaseStr($_POST['find'])."%')  AND `Nd_ok01694`.`sEtks` = `Nd_Etks`.`iCode` ORDER BY `Nd_ok01694`.`iPrioritet`, `Nd_ok01694`.`sName`;";
		
		$result = DbConnect::GetSqlQuery($sql);	
		if (mysql_num_rows($result) > 0)
		{
			while($vRow = mysql_fetch_array($result))
			{
				if(mysql_num_rows($result) == 1)
				{
					echo('
					<p><input name="checkbox'.$vRow[sCode].'" type="checkbox" id="checkbox'.$vRow[sCode].'" value="'.$vRow[sCode].'" checked="checked" />
					<label for="checkbox'.$vRow[sCode].'">'.$vRow[sName].'<br /><span class="comment gray">'.$vRow[sCode].', '.$vRow[sfEtks].'</span></label></p>
					');	
				}
				else
				{
					echo('
					<p><input name="checkbox'.$vRow[sCode].'" type="checkbox" id="checkbox'.$vRow[sCode].'" value="'.$vRow[sCode].'" />
					<label for="checkbox'.$vRow[sCode].'">'.$vRow[sName].'<br /><span class="comment gray">'.$vRow[sCode].', '.$vRow[sfEtks].'</span></label></p>
					');	
				}
			}
		}
		else
		{
			echo('<p style="text-align:center;">Уточните запрос...</p>');
		}
	}
	else
	{
		echo('<p style="text-align:center;">Уточните запрос...</p>');
	}
//<br /><br /><span class="gray">Благодарим за помощь: antohag.</span>
?>
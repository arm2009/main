<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";


if(isset($_GET[etks]))
{
	//Быстрый поиск по выпуску ЕТКС
	$sql = "SELECT `sName` FROM `Nd_Etks` WHERE `sName` LIKE '%".DbConnect::ToBaseStr($_GET['term'])."%' LIMIT 0, 5";
 	$result = DbConnect::GetSqlQuery($sql);	

	if (mysql_num_rows($result) > 0)
	{
		while($vRow = mysql_fetch_array($result))
		{
			$row['value']=htmlspecialchars($vRow[sName]);
			$aResult[] = $row;
		}
	}
	echo json_encode($aResult);	
}
else
{
	//Быстрый поиск по названию должности / профессии
	$sql ="SELECT `Nd_ok01694`.`sName`, `Nd_ok01694`.`sCode`, `Nd_ok01694`.`id`, (`Nd_Etks`.`sName`) AS `sEtks` FROM `Nd_ok01694`, `Nd_Etks` WHERE (`Nd_ok01694`.`sCode` LIKE '%".DbConnect::ToBaseStr($_GET['term'])."%' OR `Nd_ok01694`.`sName` LIKE '%".DbConnect::ToBaseStr($_GET['term'])."%') AND (`Nd_ok01694`.`sCode` NOT LIKE '".DbConnect::ToBaseStr($_GET['term'])."' OR `Nd_ok01694`.`sName` NOT LIKE '".DbConnect::ToBaseStr($_GET['term'])."') AND `Nd_ok01694`.`sEtks` = `Nd_Etks`.`iCode` ORDER BY `Nd_ok01694`.`iPrioritet` DESC, `Nd_ok01694`.`sName` LIMIT 0, 10;";
 	$result = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($result) > 0)
	{
		while($vRow = mysql_fetch_array($result))
		{
			if(!isset($_GET[code]))
			{
				$row['value']=$vRow[sName];
				$row['code']=$vRow[sCode];
			}
			else
			{
				$row['name']=$vRow[sName];
				$row['value']=$vRow[sCode];
			}
			$row['etks']=htmlspecialchars($vRow[sEtks]);
			$row['id']=$vRow[id];
			$aResult[] = $row;
		}
	}
	echo json_encode($aResult);
}

?>
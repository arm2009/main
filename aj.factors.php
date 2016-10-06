<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";

	//Быстрый поиск по названию должности / профессии
	$sql ="SELECT `sName` FROM `Nd_factors` WHERE `sName` LIKE '%".DbConnect::ToBaseStr($_GET['term'])."%' ORDER BY `sName`;";
 	$result = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($result) > 0)
	{
		while($vRow = mysql_fetch_array($result))
		{
			$row['value']=$vRow[sName];
			$aResult[] = $row;
		}
	}
	echo json_encode($aResult);

?>
<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";

if(isset($_GET[iGrId]))
{
	//Быстрый поиск по выпуску ЕТКС
	$sql = "SELECT DISTINCT `Arm_rmPoints`.`sName` FROM `Arm_rmPoints`, `Arm_rmPointsRm`, `Arm_workplace`, `Arm_group` WHERE `Arm_rmPoints`.`id` = `Arm_rmPointsRm`.`idPoint` AND `Arm_rmPointsRm`.`idRm` = `Arm_workplace`.`id` AND `Arm_workplace`.`idGroup` = `Arm_group`.`id` AND `Arm_group`.`id` = $_GET[iGrId] AND `Arm_rmPoints`.`sName` LIKE '%".DbConnect::ToBaseStr($_GET['term'])."%' ORDER BY  `Arm_rmPoints`.`sName` ASC;";
//DbConnect::Log($sql,'debug');

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
?>
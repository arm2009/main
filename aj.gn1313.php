<?
include_once "LowLevel/dataCrypt.php";
include_once "UserControl/userControl.php";
include_once "LowLevel/userValidator.php";

if(isset($_GET[sType]))
{
	if($_GET[sType]==31) $sql = "SELECT `id`, `sName` FROM `Nd_gn1313` WHERE `sFeat` NOT LIKE 'Ф' AND Nd_gn1313.gnversion = 1 ORDER BY `sName`;";
	if($_GET[sType]==8) $sql = "SELECT `id`, `sName` FROM `Nd_gn1313` WHERE `sFeat` LIKE '%Ф%' AND Nd_gn1313.gnversion = 1 ORDER BY `sName`;";
 	$result = DbConnect::GetSqlQuery($sql);	

	if (mysql_num_rows($result) > 0)
	{
		while($vRow = mysql_fetch_array($result))
		{
			echo('<label><input type="checkbox" name="factors_gn" value="'.$vRow[id].'" id="factors_gn_'.$vRow[id].'" />'.$vRow[sName].'</label><br />');
		}
	}
}
?>
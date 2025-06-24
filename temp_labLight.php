<? 
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/WorkPlace.php";
	include_once "MainWork/WorkFactors.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Документ без названия</title>
</head>

<body>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
  <tr>
    <td>#</td>
    <td>Рабочее место</td>
    <td>Время прибывания</td>
    <td>Всего</td>
    <td>Всего ПДУ</td>
    <td>Общее</td>
    <td>Общее ПДУ</td>
    <td>Оценка</td>
  </tr>
  <?
	$idWorkGroup = 18;
	$sql = "SELECT * FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysqli_num_rows($vResult) > 0)
	{
		while($vRow = mysqli_fetch_array($vResult))
		{
			if (strlen($vRow[sNumAnalog]) > 0)
			{
				$vRow[iNumber] = $vRow[iNumber].'А';
			}			
			
			echo('
  <tr>
    <td>'.$vRow[iNumber].'</td>
    <td>'.$vRow[sName].'</td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
			');
			
			//Перебор зон
			$aZone = WorkFactors::GetPointsList($vRow[id]);
			foreach ($aZone as &$value)
			{
				echo('
				<tr>
				<td>&nbsp;</td>
				<td>'.$value[2].'</td>
				<td>'.$value[3].'</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				</tr>
				');
				$aFactors = WorkFactors::GetFactorsList($value[0],$vRow[id]);
				foreach ($aFactors as &$valueF)
				{
					if($valueF[16] == 17)
					echo('
					<tr>
					<td>&nbsp;</td>
					<td></td>
					<td></td>
					<td>'.$valueF[3].'</td>
					<td>'.$valueF[5].'</td>
					<td>'.$valueF[7].'</td>
					<td>'.$valueF[11].'</td>
					<td>'.$valueF[6].'</td>
					</tr>
					');
				}
			}
		}
	}
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
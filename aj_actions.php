<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";

	//Быстрый поиск по названию должности / профессии
	/*$sql ="SELECT `sName` FROM `Nd_factors` WHERE `sName` LIKE '%".DbConnect::ToBaseStr($_GET['term'])."%' ORDER BY `sName`;";
 	$result = DbConnect::GetSqlQuery($sql);

	if (mysql_num_rows($result) > 0)
	{
		while($vRow = mysql_fetch_array($result))
		{
			$row['value']=$vRow[sName];
			$aResult[] = $row;
		}
	}
	echo json_encode($aResult);*/
	
	if ($_POST['sHeader']=='162')
	{
		$sql = "SELECT * FROM Nd_162 GROUP BY Razdel ORDER BY NPunkt;";
		$result = DbConnect::GetSqlQuery($sql);
		
		$sHtml = '';
		
		if (mysql_num_rows($result) > 0)
		{
			while($vRow = mysql_fetch_array($result))
			{
				$sHtml = $sHtml.'<div id="header_h1_'.$vRow['id'].'" onclick="RoollClick(\'h1_'.$vRow['id'].'\')" class="rollDown" title=""><strong>'.$vRow['Razdel'].'</strong></div>        
		        <div id="body_h1_'.$vRow['id'].'" style="display:none;margin:10px; margin-left:30px;">';
				$sql2 = "SELECT * FROM Nd_162 WHERE Razdel LIKE '".$vRow['Razdel']."' AND PRazdel NOT LIKE '' GROUP BY PRazdel ORDER BY NPunkt;";
				$result2 = DbConnect::GetSqlQuery($sql2);
				if (mysql_num_rows($result2) > 0)
				{
					while($vRow2 = mysql_fetch_array($result2))
					{
						$sHtml = $sHtml.'<div id="header_hh1_'.$vRow2['id'].'" onclick="RoollClick(\'hh1_'.$vRow2['id'].'\')" class="rollDown" title=""><strong>'.$vRow2['PRazdel'].'</strong></div>        
			        <div id="body_hh1_'.$vRow2['id'].'" style="display:none;margin:10px; margin-left:30px;">';
					
					
					//$sHtml = $sHtml.'12</br>';
					
					$sql3 = "SELECT * FROM Nd_162 WHERE PRazdel LIKE '".$vRow2['PRazdel']."';";
					$result3 = DbConnect::GetSqlQuery($sql3);
					if (mysql_num_rows($result3) > 0)
					{
						while($vRow3 = mysql_fetch_array($result3))
						{
								$sHtml = $sHtml.'<label><input type="checkbox" name="162" value="'.$vRow3['id'].'" id="162_'.$vRow3['id'].'" />'.$vRow3['NPunkt'].'. '.$vRow3['Punkt'].'</label><br />';
						}
					}
					
					$sHtml = $sHtml.'</div>';
				}
					
					
				}
				
				$sql4 = "SELECT * FROM Nd_162 WHERE Razdel LIKE '".$vRow['Razdel']."' AND PRazdel LIKE '';";
				$result4 = DbConnect::GetSqlQuery($sql4);
				if (mysql_num_rows($result4) > 0)
				{
					while($vRow4 = mysql_fetch_array($result4))
					{
						/*$sHtml = $sHtml.'<div id="header_hh1_'.$vRow2['id'].'" onclick="Slide("hh1_'.$vRow2['id'].'")" class="rollDown" title="11">'.$vRow2['PRazdel'].'</div>        
			        <div id="body_hh1_'.$vRow2['id'].'" style="display:none;margin:10px; margin-left:30px;"></div>';*/
						//$sHtml = $sHtml.$vRow4['Punkt'].'</br>';
						$sHtml = $sHtml.'<label><input type="checkbox" name="162" value="'.$vRow4[id].'" id="162_'.$vRow4[id].'" />'.$vRow4['NPunkt'].'. '.$vRow4[Punkt].'</label><br />';
					}
				}
				
				$sHtml = $sHtml."</div>";
			}
		}
		
		echo $sHtml;
	}
	
	if ($_POST['sHeader']=='163')
	{
		$sql = "SELECT * FROM Nd_163 GROUP BY Razdel  ORDER BY NPunkt;";
		$result = DbConnect::GetSqlQuery($sql);
		
		$sHtml = '';
		
		if (mysql_num_rows($result) > 0)
		{
			while($vRow = mysql_fetch_array($result))
			{
				$sHtml = $sHtml.'<div id="header_h1_'.$vRow['id'].'" onclick="RoollClick(\'h1_'.$vRow['id'].'\')" class="rollDown" title=""><strong>'.$vRow['Razdel'].'</strong></div>        
		        <div id="body_h1_'.$vRow['id'].'" style="display:none;margin:10px; margin-left:30px;">';
				$sql2 = "SELECT * FROM Nd_163 WHERE Razdel LIKE '".$vRow['Razdel']."' AND PRazdel NOT LIKE '' GROUP BY PRazdel ORDER BY NPunkt;";
				$result2 = DbConnect::GetSqlQuery($sql2);
				if (mysql_num_rows($result2) > 0)
				{
					while($vRow2 = mysql_fetch_array($result2))
					{
						$sHtml = $sHtml.'<div id="header_hh1_'.$vRow2['id'].'" onclick="RoollClick(\'hh1_'.$vRow2['id'].'\')" class="rollDown" title=""><strong>'.$vRow2['PRazdel'].'</strong></div>        
			        <div id="body_hh1_'.$vRow2['id'].'" style="display:none;margin:10px; margin-left:30px;">';
					
					
					//$sHtml = $sHtml.'12</br>';
					
					$sql3 = "SELECT * FROM Nd_163 WHERE PRazdel LIKE '".$vRow2['PRazdel']."';";
					$result3 = DbConnect::GetSqlQuery($sql3);
					if (mysql_num_rows($result3) > 0)
					{
						while($vRow3 = mysql_fetch_array($result3))
						{
								$sHtml = $sHtml.'<label><input type="checkbox" name="163" value="'.$vRow3['id'].'" id="163_'.$vRow3['id'].'" />'.$vRow3['NPunkt'].'. '.$vRow3['Punkt'].'</label><br />';
						}
					}
					
					$sHtml = $sHtml.'</div>';
				}
					
					
				}
				
				$sql4 = "SELECT * FROM Nd_163 WHERE Razdel LIKE '".$vRow['Razdel']."' AND PRazdel LIKE '';";
				$result4 = DbConnect::GetSqlQuery($sql4);
				if (mysql_num_rows($result4) > 0)
				{
					while($vRow4 = mysql_fetch_array($result4))
					{
						/*$sHtml = $sHtml.'<div id="header_hh1_'.$vRow2['id'].'" onclick="Slide("hh1_'.$vRow2['id'].'")" class="rollDown" title="11">'.$vRow2['PRazdel'].'</div>        
			        <div id="body_hh1_'.$vRow2['id'].'" style="display:none;margin:10px; margin-left:30px;"></div>';*/
						//$sHtml = $sHtml.$vRow4['Punkt'].'</br>';
						$sHtml = $sHtml.'<label><input type="checkbox" name="163" value="'.$vRow4[id].'" id="163_'.$vRow4[id].'" />'.$vRow4[NPunkt].'. '.$vRow4[Punkt].'</label><br />';
					}
				}
				
				$sHtml = $sHtml."</div>";
			}
		}
		
		echo $sHtml;
	}
	
	if (isset($_POST['prikaz']) && isset($_POST['punkt']))
	{
		switch($_POST['prikaz'])
		{
			case '162':
				$sql = "SELECT * FROM Nd_162 WHERE NPunkt = ".$_POST['punkt'].";";
				$result = DbConnect::GetSqlQuery($sql);
				break;
			case '163':
				$sql = "SELECT * FROM Nd_163 WHERE NPunkt = ".$_POST['punkt'].";";
				$result = DbConnect::GetSqlQuery($sql);
				break;				
		}
		
		$sResult = '';
		
		if (mysql_num_rows($result) > 0)
				{
					while($vRow = mysql_fetch_array($result))
					{
						$sPRazdel = '';
						if ($vRow['PRazdel'] != '') {$sPRazdel = ' - '.$vRow['PRazdel'];}
						$sResult = $vRow['Razdel'].$sPRazdel.', п.'.$vRow['NPunkt'].' "'.$vRow['Punkt'].'"';
					}
				}
		echo $sResult;
	}
?>
<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/WorkPlace.php";

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
	if ($_POST['sHeader']=='ps1')
	{
		$sqlPS1 = "SELECT * FROM Nd_pens WHERE sNum LIKE '1%' AND idParent = -1 ORDER BY id;";
		$resultPS1 = DbConnect::GetSqlQuery($sqlPS1);
		
		$sHtml = '';
		
		if (mysql_num_rows($resultPS1) > 0)
		{
			while ($vRowPS1 = mysql_fetch_array($resultPS1))
			{
				$sHtml = $sHtml.'<div id="header_h1_'.$vRowPS1['id'].'" onclick="RoollClick(\'h1_'.$vRowPS1['id'].'\')" class="rollDown" title="'.$vRowPS1['sInfo'].'"><strong>'.$vRowPS1['sName'].'</strong></div>        <div id="body_h1_'.$vRowPS1['id'].'" style="display:none;margin:10px; margin-left:30px;">';	
				
				$sqlPS11 = "SELECT * FROM Nd_pens WHERE idParent = ".$vRowPS1['id']." ORDER BY id;";

				$resultPS11 = DbConnect::GetSqlQuery($sqlPS11);
				
				if (mysql_num_rows($resultPS11) > 0)
				{
					while ($vRowPS11 = mysql_fetch_array($resultPS11))
					{
						$sStrong1 = '';
						$sStrong2 = '';
						if (strpos($vRowPS11['sNum'], '-') == '') {$sStrong1 = '<strong>'; $sStrong2 = '</strong>'; }
						
						
						$sHtml = $sHtml.'<label><input type="checkbox" name="pens1" value="'.$vRowPS11['id'].'" id="pens1_'.$vRowPS11['id'].'" />'.$sStrong1.$vRowPS11['sNum'].' '.$vRowPS11['sName'].$sStrong2.'</label><br />';
					}
				}
				
				$sHtml = $sHtml.'</div>';
			}
		}
		
		echo $sHtml;
	}
	
	if ($_POST['sHeader']=='ps2')
	{
		$sqlPS1 = "SELECT * FROM Nd_pens WHERE sNum LIKE '2%' AND idParent = -1 ORDER BY id;";
		$resultPS1 = DbConnect::GetSqlQuery($sqlPS1);
		
		$sHtml = '';
		
		if (mysql_num_rows($resultPS1) > 0)
		{
			while ($vRowPS1 = mysql_fetch_array($resultPS1))
			{
				$sHtml = $sHtml.'<div id="header_h1_'.$vRowPS1['id'].'" onclick="RoollClick(\'h1_'.$vRowPS1['id'].'\')" class="rollDown" title="'.$vRowPS1['sInfo'].'"><strong>'.$vRowPS1['sName'].'</strong></div>        <div id="body_h1_'.$vRowPS1['id'].'" style="display:none;margin:10px; margin-left:30px;">';	
				
				$sqlPS11 = "SELECT * FROM Nd_pens WHERE idParent = ".$vRowPS1['id']." ORDER BY id;";

				$resultPS11 = DbConnect::GetSqlQuery($sqlPS11);
				
				if (mysql_num_rows($resultPS11) > 0)
				{
					while ($vRowPS11 = mysql_fetch_array($resultPS11))
					{
						$sStrong1 = '';
						$sStrong2 = '';
						if (strpos($vRowPS11['sNum'], '-') == '') {$sStrong1 = '<strong>'; $sStrong2 = '</strong>'; }
						
						
						$sHtml = $sHtml.'<label><input type="checkbox" name="pens1" value="'.$vRowPS11['id'].'" id="pens1_'.$vRowPS11['id'].'" />'.$sStrong1.$vRowPS11['sNum'].' '.$vRowPS11['sName'].$sStrong2.'</label><br />';
					}
				}
				
				$sHtml = $sHtml.'</div>';
			}
		}
		
		echo $sHtml;
	}
	
	if ($_POST['sHeader']=='psFz')
	{
		$sHtml = '';
		$sHtml = $sHtml.'<div id="header_hh1_1" onclick="RoollClick(\'hh1_1\')" class="rollDown" title="ГЛАВА VI. Порядок сохранения и конвертации (преобразования) ранее приобретенных прав. Статья 27. Сохранение права на досрочное назначение трудовой пенсии."><strong>Статья 27. Сохранение права на досрочное назначение трудовой пенсии</strong></div>        <div id="body_hh1_1" style="display:none;margin:10px; margin-left:30px;">';
		
		$sql1 = 'SELECT * FROM Nd_pensFz WHERE iState = 27 ORDER BY id';
		$result1 = DbConnect::GetSqlQuery($sql1);
		
		if (mysql_num_rows($result1) > 0)
				{
					while ($vRow1 = mysql_fetch_array($result1))
					{
						$sHtml = $sHtml.'<label><input type="checkbox" name="pensFz" value="'.$vRow1['id'].'" id="pensFz_'.$vRow1['id'].'" />'.$vRow1['sNum'].' '.$vRow1['sName'].'</label><br />';
					}
				}
		
		$sHtml = $sHtml.'</div>';
		
		$sHtml = $sHtml.'<div id="header_hh1_2" onclick="RoollClick(\'hh1_2\')" class="rollDown" title="ГЛАВА VI. Порядок сохранения и конвертации (преобразования) ранее приобретенных прав. Статья 28. Сохранение права на досрочное назначение трудовой пенсии отдельным категориям граждан."><strong>Статья 28. Сохранение права на досрочное назначение трудовой пенсии отдельным категориям граждан</strong></div>
		<div id="body_hh1_2" style="display:none;margin:10px; margin-left:30px;">';
		
		$sql2 = 'SELECT * FROM Nd_pensFz WHERE iState = 28 ORDER BY id';
		$result2 = DbConnect::GetSqlQuery($sql2);
		
		if (mysql_num_rows($result2) > 0)
				{
					while ($vRow2 = mysql_fetch_array($result2))
					{
						$sHtml = $sHtml.'<label><input type="checkbox" name="pensFz" value="'.$vRow2['id'].'" id="pensFz_'.$vRow2['id'].'" />'.$vRow2['sNum'].' '.$vRow2['sName'].'</label><br />';
					}
				}
		
		$sHtml = $sHtml.'</div>';
		echo $sHtml;
	}
	
	//ицина первый том
	if ($_POST['sHeader']=='med1')
	{
		$sHtml = '';
		$sHtml = $sHtml.'<div id="header_h1_1" onclick="RoollClick(\'h1_1\')" class="rollDown" title=""><strong>1. Химические факторы</strong></div><div id="body_h1_1" style="display:none;margin:10px; margin-left:30px;">';
			$sHtml = $sHtml.'<div id="header_h2_1" onclick="RoollClick(\'h2_1\')" class="rollDown" title=""><strong>1.1 Химические вещества, обладающие выраженными особенностями действия на организм</strong></div><div id="body_h2_1" style="display:none;margin:10px; margin-left:30px;">';
			
			$sql1 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "1.1%" AND sPer LIKE "";';
			$result1 = DbConnect::GetSqlQuery($sql1);
			
			while ($vRow1 = mysql_fetch_array($result1))
					{
						$sHtml = $sHtml.'<div id="header_h3_'.$vRow1['id'].'" onclick="RoollClick(\'h3_'.$vRow1['id'].'\')" class="rollDown" title=""><strong>'.$vRow1['sPunkt'].$vRow1['sName'].'</strong></div><div id="body_h3_'.$vRow1['id'].'" style="display:none;margin:10px; margin-left:30px;">';
						
						$sql2 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "'.$vRow1['sPunkt'].'%" AND id <> '.$vRow1['id'].' AND sPunkt NOT LIKE "";';
						$result2 = DbConnect::GetSqlQuery($sql2);
					
						while ($vRow2 = mysql_fetch_array($result2))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow2['id'].'" id="med1_'.$vRow2['id'].'" />'.$vRow2['sPunkt'].' '.$vRow2['sName'].'</label><br />';
						}
						
						$sHtml = $sHtml.'</div>';
					}
			
		
			$sHtml = $sHtml.'</div>';
			
			$sHtml = $sHtml.'<div id="header_h2_2" onclick="RoollClick(\'h2_2\')" class="rollDown" title=""><strong>1.2 Вещества и соединения, объединенные химической структурой</strong></div><div id="body_h2_2" style="display:none;margin:10px; margin-left:30px;">';
			
			$sql4 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "1.2%" AND sPer LIKE "" AND length(sPunkt) < 8;';
			$result4 = DbConnect::GetSqlQuery($sql4);
			
			while ($vRow4 = mysql_fetch_array($result4))
						{
							$sHtml = $sHtml.'<div id="header_h3_'.$vRow4['id'].'" onclick="RoollClick(\'h3_'.$vRow4['id'].'\')" class="rollDown" title=""><strong>'.$vRow4['sPunkt'].$vRow4['sName'].':</strong></div><div id="body_h3_'.$vRow4['id'].'" style="display:none;margin:10px; margin-left:30px;">';
							
							$sql5 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "'.$vRow4['sPunkt'].'%" AND sPer NOT LIKE "";';
							$result5 = DbConnect::GetSqlQuery($sql5);
			
							while ($vRow5 = mysql_fetch_array($result5))
							{
								$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow5['id'].'" id="med1_'.$vRow5['id'].'" />'.$vRow5['sPunkt'].' '.$vRow5['sName'].'</label><br />';
							}
		
			$sHtml = $sHtml.'</div>';
						}
			
			$sql3 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "1.2%" AND sPer NOT LIKE "" AND length(sPunkt) < 8;';
			$result3 = DbConnect::GetSqlQuery($sql3);
			
			while ($vRow3 = mysql_fetch_array($result3))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow3['id'].'" id="med1_'.$vRow3['id'].'" />'.$vRow3['sPunkt'].' '.$vRow3['sName'].'</label><br />';
						}
		
			$sHtml = $sHtml.'</div>';
			
			$sHtml = $sHtml.'<div id="header_h2_3" onclick="RoollClick(\'h2_3\')" class="rollDown" title=""><strong>1.3. Сложные химические смеси, композиции, химические вещества определенного назначения, включая:</strong></div><div id="body_h2_3" style="display:none;margin:10px; margin-left:30px;">';
		
			$sql4 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "1.3%" AND sPer LIKE "" AND length(sPunkt) < 8;';
			$result4 = DbConnect::GetSqlQuery($sql4);
			
			while ($vRow4 = mysql_fetch_array($result4))
						{
							$sHtml = $sHtml.'<div id="header_h3_'.$vRow4['id'].'" onclick="RoollClick(\'h3_'.$vRow4['id'].'\')" class="rollDown" title=""><strong>'.$vRow4['sPunkt'].$vRow4['sName'].':</strong></div><div id="body_h3_'.$vRow4['id'].'" style="display:none;margin:10px; margin-left:30px;">';
							
							$sql5 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "'.$vRow4['sPunkt'].'%" AND sPer NOT LIKE "";';
							$result5 = DbConnect::GetSqlQuery($sql5);
			
							while ($vRow5 = mysql_fetch_array($result5))
							{
								$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow5['id'].'" id="med1_'.$vRow5['id'].'" />'.$vRow5['sPunkt'].' '.$vRow5['sName'].'</label><br />';
							}
		
			$sHtml = $sHtml.'</div>';
						}
			
			$sql3 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "1.3%" AND sPer NOT LIKE "" AND length(sPunkt) < 8;';
			$result3 = DbConnect::GetSqlQuery($sql3);
			
			while ($vRow3 = mysql_fetch_array($result3))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow3['id'].'" id="med1_'.$vRow3['id'].'" />'.$vRow3['sPunkt'].' '.$vRow3['sName'].'</label><br />';
						}
		
			$sHtml = $sHtml.'</div>';
		
		$sHtml = $sHtml.'</div>';
		
		$sHtml = $sHtml.'<div id="header_h1_2" onclick="RoollClick(\'h1_2\')" class="rollDown" title=""><strong>2. Биологические факторы</strong></div><div id="body_h1_2" style="display:none;margin:10px; margin-left:30px;">';
			
			$sql8 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "2.%" AND sPer LIKE "" AND length(sPunkt) < 8;';
			$result8 = DbConnect::GetSqlQuery($sql8);
			
			while ($vRow8 = mysql_fetch_array($result8))
						{
							$sHtml = $sHtml.'<div id="header_h4_'.$vRow8['id'].'" onclick="RoollClick(\'h4_'.$vRow8['id'].'\')" class="rollDown" title=""><strong>'.$vRow8['sPunkt'].$vRow8['sName'].':</strong></div><div id="body_h4_'.$vRow8['id'].'" style="display:none;margin:10px; margin-left:30px;">';
							
							$sql9 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "'.$vRow8['sPunkt'].'%" AND sPer NOT LIKE "";';
							$result9 = DbConnect::GetSqlQuery($sql9);
			
							while ($vRow9 = mysql_fetch_array($result9))
							{
								$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow9['id'].'" id="med1_'.$vRow9['id'].'" />'.$vRow9['sPunkt'].' '.$vRow9['sName'].'</label><br />';
							}
							
							$sHtml = $sHtml.'</div>';
						}
		
			$sql7 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "2.%" AND sPer NOT LIKE "" AND length(sPunkt) < 6;';
			$result7 = DbConnect::GetSqlQuery($sql7);
			
			while ($vRow7 = mysql_fetch_array($result7))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow7['id'].'" id="med1_'.$vRow7['id'].'" />'.$vRow7['sPunkt'].' '.$vRow7['sName'].'</label><br />';
						}
		
		$sHtml = $sHtml.'</div>';
		
		$sHtml = $sHtml.'<div id="header_h1_3" onclick="RoollClick(\'h1_3\')" class="rollDown" title=""><strong>3. Физические факторы</strong></div><div id="body_h1_3" style="display:none;margin:10px; margin-left:30px;">';
		
		$sql8 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "3.%" AND sPer LIKE "" AND length(sPunkt) < 6;';
			$result8 = DbConnect::GetSqlQuery($sql8);
			
			while ($vRow8 = mysql_fetch_array($result8))
						{
							$sHtml = $sHtml.'<div id="header_h4_'.$vRow8['id'].'" onclick="RoollClick(\'h4_'.$vRow8['id'].'\')" class="rollDown" title=""><strong>'.$vRow8['sPunkt'].$vRow8['sName'].':</strong></div><div id="body_h4_'.$vRow8['id'].'" style="display:none;margin:10px; margin-left:30px;">';
							
							$sql9 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "'.$vRow8['sPunkt'].'%" AND sPer NOT LIKE "";';
							$result9 = DbConnect::GetSqlQuery($sql9);
			
							while ($vRow9 = mysql_fetch_array($result9))
							{
								$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow9['id'].'" id="med1_'.$vRow9['id'].'" />'.$vRow9['sPunkt'].' '.$vRow9['sName'].'</label><br />';
							}
							
							$sHtml = $sHtml.'</div>';
						}
		
			$sql7 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "3.%" AND sPer NOT LIKE "" AND length(sPunkt) < 6;';
			$result7 = DbConnect::GetSqlQuery($sql7);
			
			while ($vRow7 = mysql_fetch_array($result7))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow7['id'].'" id="med1_'.$vRow7['id'].'" />'.$vRow7['sPunkt'].' '.$vRow7['sName'].'</label><br />';
						}
		
		$sHtml = $sHtml.'</div>';
		
		$sHtml = $sHtml.'<div id="header_h1_4" onclick="RoollClick(\'h1_4\')" class="rollDown" title=""><strong>4. Факторы трудового процесса</strong></div><div id="body_h1_4" style="display:none;margin:10px; margin-left:30px;">';
		
		$sql8 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "4.%" AND sPer LIKE "" AND length(sPunkt) < 6;';
			$result8 = DbConnect::GetSqlQuery($sql8);
			
			while ($vRow8 = mysql_fetch_array($result8))
						{
							$sHtml = $sHtml.'<div id="header_h4_'.$vRow8['id'].'" onclick="RoollClick(\'h4_'.$vRow8['id'].'\')" class="rollDown" title=""><strong>'.$vRow8['sPunkt'].$vRow8['sName'].':</strong></div><div id="body_h4_'.$vRow8['id'].'" style="display:none;margin:10px; margin-left:30px;">';
							
							$sql9 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "'.$vRow8['sPunkt'].'%" AND sPer NOT LIKE "";';
							$result9 = DbConnect::GetSqlQuery($sql9);
			
							while ($vRow9 = mysql_fetch_array($result9))
							{
								$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow9['id'].'" id="med1_'.$vRow9['id'].'" />'.$vRow9['sPunkt'].' '.$vRow9['sName'].'</label><br />';
							}
							
							$sHtml = $sHtml.'</div>';
						}
		
			$sql7 = 'SELECT * FROM Nd_med1 WHERE sPunkt LIKE "4.%" AND sPer NOT LIKE "" AND length(sPunkt) < 6;';
			$result7 = DbConnect::GetSqlQuery($sql7);
			
			while ($vRow7 = mysql_fetch_array($result7))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med1" value="'.$vRow7['id'].'" id="med1_'.$vRow7['id'].'" />'.$vRow7['sPunkt'].' '.$vRow7['sName'].'</label><br />';
						}
		
		$sHtml = $sHtml.'</div>';
		
		echo $sHtml;
	}
	
		//Медицина второй том
	if ($_POST['sHeader']=='med2')
	{
		$sql7 = 'SELECT * FROM Nd_med2;';
			$result7 = DbConnect::GetSqlQuery($sql7);
			
			while ($vRow7 = mysql_fetch_array($result7))
						{
							$sHtml = $sHtml.'<label><input type="checkbox" name="med2" value="'.$vRow7['id'].'" id="med2_'.$vRow7['id'].'" />'.$vRow7['sPunkt'].' '.$vRow7['sName'].'</label><br />';
						}
						
			echo $sHtml;
	}
	
	function GetFullNamePensFz($id)
	{
		$sql = 'SELECT * FROM Nd_pensFz WHERE id = '.$id;
		$result = DbConnect::GetSqlQuery($sql);
		
		$sRes = 'Федеральный закон №173 "О трудовых пенсиях в Российской Федерации" от 17.12.2001, ';
		
		if (mysql_num_rows($result) > 0)
				{
					while ($vRow = mysql_fetch_array($result))
					{
						switch ($vRow['iState'])
						{
							case '27':
							$sRes = $sRes.'Глава VI. "Порядок сохранения и конвертации (преобразования) ранее приобретенных прав", Статья 27. "Сохранение права на досрочное назначение трудовой пенсии", ';
							break;
							case '28':
							$sRes = $sRes.'Глава VI. "Порядок сохранения и конвертации (преобразования) ранее приобретенных прав", Статья 28. "Сохранение права на досрочное назначение трудовой пенсии отдельным категориям граждан", ';
							break;
						}
						
						$sRes = $sRes.'п.п '.$vRow['sName'].'.';	
					}
				}
		return $sRes;
	}
	
	
	if (isset($_POST['prikaz']) && isset($_POST['id']))
	{
		$sResult = '';
		
		switch($_POST['prikaz'])
		{
			case 'pens':
			$sResult = WorkPlace::GetFullNamePens($_POST['id']);
			break;	
			case 'pensFz':
			$sResult = GetFullNamePensFz($_POST['id']);
			break;
		}
				
		echo $sResult;
	}
	
	if (isset($_POST['prikaz']))
	{
		if ($_POST['prikaz'] == 'med')
		{
			$sResult = WorkPlace::GetFullNameMed($_POST['idMed1'], $_POST['idMed2']);
			echo $sResult;
		}
		
	}
?>
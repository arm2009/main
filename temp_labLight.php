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
    <td>var1</td>
    <td>pdu2</td>
    <td>var2</td>
    <td>pdu2</td>
    <td>var3</td>
    <td>pdu3</td>
    <td>var4</td>
    <td>pdu4</td>
    <td>var5</td>
    <td>pdu5</td>
    <td>Оценка</td>
    <td>Дата</td>
  </tr>
  <?
	$idWorkGroup = 854;
	$idGroup = -1;
	$sql = "SELECT * FROM `Arm_workplace` WHERE `idGroup` = ".$idWorkGroup." AND `idParent` > -1 ORDER BY `iNumber`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
            $firstRm = true;
			if (strlen($vRow[sNumAnalog]) > 0)
			{
				$vRow[iNumber] = $vRow[iNumber].'А';
			}			

			//Вставка номера рабочего места
			if($idGroup <> $vRow[idParent])
			{
				$idGroup = $vRow[idParent];
				$GroupName = DbConnect::GetSqlCell("SELECT `sName` FROM `Arm_workplace` WHERE `id` = $vRow[idParent];");
				echo("<tr><td colspan='15' style='background-color:#000;color:#FFF;'><strong>$GroupName</strong></td></tr>");
			}

			//Перебор зон
            $aZone = array();
			$aZone = WorkFactors::GetPointsList($vRow[id]);
			foreach ($aZone as &$value)
			{
                $firstFuck = true;
				$aFactors = WorkFactors::GetFactorsList($value[0],$vRow[id]);
                if($aFactors)
				foreach ($aFactors as &$valueF)
				{
					if($valueF[15] == 13)//(strpos($value[2], 'ЗИЛ') !== false || strpos($value[2], 'ГАЗ') !== false) && $valueF[15] == 43) //$valueF[15] == 8493
                    {
						//Выбор направления и дат
						if($vRow[iNumber] <172 || ($vRow[iNumber]>192 && $vRow[iNumber]<216))
						{
							$NewDate = '02.11.2015';
							if($vRow[iNumber]>84 && $vRow[iNumber]<94) $NewDate = '03.11.2015';
							if($vRow[iNumber]>124 && $vRow[iNumber]<171) $NewDate = '04.11.2015';
							$vRow[sName] .= ' (Красноярск '.$NewDate.')';
						}
						if($vRow[iNumber]>171 && $vRow[iNumber]<193)
						{
							$NewDate = '28.10.2015';
							$vRow[sName] .= ' (Север '.$NewDate.')';
							
						}
						if($vRow[iNumber]>303 && $vRow[iNumber]<366)
						{
							$NewDate = '17.11.2015';
							if($vRow[iNumber]>309 && $vRow[iNumber]<334) $NewDate = '19.11.2015';
							if($vRow[iNumber]>342 && $vRow[iNumber]<366) $NewDate = '18.11.2015';
//								$vRow[sName] .= ' (Юг 17-19.11)';
							$vRow[sName] .= ' (Юг '.$NewDate.')';
						}
						if($vRow[iNumber]>365 && $vRow[iNumber]<438)
						{
							$NewDate = '05.11.2015';
							if($vRow[iNumber]>371 && $vRow[iNumber]<411) $NewDate = '06.11.2015';
//								$vRow[sName] .= ' (Запад 05-06.11)';
							$vRow[sName] .= ' (Запад '.$NewDate.')';
						}
						if($vRow[iNumber]>215 && $vRow[iNumber]<304)
						{
							$NewDate = '10.11.2015';
							if($vRow[iNumber]>222 && $vRow[iNumber]<276) $NewDate = '11.11.2015';
//								$vRow[sName] .= ' (Восток 10-12.11)';
							$vRow[sName] .= ' (Восток '.$NewDate.')';
						}					
						
                        if($firstRm)
                        {
							//Вставка номера рабочего места
							if($idGroup <> $vRow[idParent])
							{
								$idGroup = $vRow[idParent];
								$GroupName = DbConnect::GetSqlCell("SELECT `sName` FROM `Arm_workplace` WHERE `id` = $vRow[idParent];");
								echo("<tr><td colspan='15' style='background-color:#000;color:#FFF;'><strong>$GroupName</strong></td></tr>");
							}
														
                            echo('
                              <tr>
                                <td><strong>'.$vRow[iNumber].'</strong></td>
                                <td><strong>'.$vRow[sName].'</strong></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
								<td>&nbsp;</td>
                              </tr>
                            ');
                            $firstRm = false;
                        }
                        if($firstFuck)
                        {
                            echo('
                            <tr>
                            <td>&nbsp;</td>
                            <td><em>'.$value[0].' / '.$value[2].'</em></td>
                            <td><em>'.$value[3].'</em></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
							<td>&nbsp;</td>
                            </tr>
                            ');
                            $firstFuck = false;
                        }

						if($valueF[4] == $NewDate) $NewDate = '';

                        echo('
                        <tr>
                        <td style="font-size:50%"><strong>'.$NewDate.'</strong></td>
                        <td><strong>'.$valueF[0].' / '.$valueF[2].'</strong></td>
                        <td><strong>ф.'.$valueF[15].' / г.'.$valueF[16].'</strong></td>
                        <td><strong>'.$valueF[3].'</strong></td>
                        <td><strong>'.$valueF[5].'</strong></td>
                        <td><strong>'.$valueF[7].'</strong></td>
                        <td><strong>'.$valueF[11].'</strong></td>
                        <td><strong>'.$valueF[8].'</strong></td>
                        <td><strong>'.$valueF[12].'</strong></td>
                        <td><strong>'.$valueF[9].'</strong></td>
                        <td><strong>'.$valueF[13].'</strong></td>
                        <td><strong>'.$valueF[10].'</strong></td>
                        <td><strong>'.$valueF[14].'</strong></td>
                        <td><strong>'.$valueF[6].'</strong></td>
						<td><strong>'.$valueF[4].'</strong></td>
                        </tr>
                        ');

                        //Вносим изменения
                        //array($vRow['id'], $vRow['idPoint'],$vRow['sName'],$vRow['var1'],StringWork::StrToDateFormatLite($vRow['dtControl']),$vRow['fPdu1'],StringWork::iToClassNameLite($vRow['iAsset']), $vRow['var2'],$vRow['var3'],$vRow['var4'],$vRow['var5'],$vRow['fPdu2'],$vRow['fPdu3'],$vRow['fPdu4'],$vRow['fPdu5'],$vRow[idFactor],$vRow[idFactorGroup],$vRow[sAddonAsset]);
                        //WorkFactors::EditPointAddLight($valueF[0], $sLightPolygone, $sLightHeight, $sLightDark, $sLightType);
                        //EditFactor($inIdFactor, $inIdRm, $fFact1, $fPdu1, $dControl, $fFact2, $fPdu2, $fFact3, $fPdu3, $fFact4, $fPdu4, $fFact5, $fPdu5)
                        //if(!$valueF[7] || $valueF[7] == 0)
                        //if(!empty($NewDate))
                      	//WorkFactors::EditFactor($valueF[0], $vRow[id], -1, -1, $NewDate, -1, -1, -1, -1, -1, -1, -1, -1);
						//WorkFactors::EditFactor($valueF[0], $vRow[id], round(rand(80,90)), -1, $NewDate, round(rand(80,90)), -1, round(rand(85,95)), -1, -1, -1, -1, -1);
						//WorkFactors::EditFactor($valueF[0], $vRow[id], 1, -1, $NewDate, -1, -1, -1, -1, -1, -1, -1, -1);
						//WorkFactors::EditFactor($valueF[0], $vRow[id], -1, -1, $NewDate, -1, -1, -1, -1, -1, -1, -1, -1);

						$aAddon = array();
						if(!in_array($value[0], $aAddon))
						{
							//WorkFactors::DelFactor($valueF[0]);
							//WorkFactors::AddFactor($value[0], 8493, '', $vRow[id]);
							//$aAddon[] = $value[0];
						}
                    }
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

<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
    include_once "MainWork/WorkFactors.php";
    $idGroup = (int) $_GET['idgr'];
    ini_set('memory_limit','64M');
?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Результат анализа</td>
        </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:300px;" class="comment">

<?
$sql = "SELECT Arm_workplace.id, Arm_workplace.iNumber, Arm_workplace.sName, Arm_workplace.sOk, Arm_workplace.sETKS, sSnils FROM Arm_workplace WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1
AND (Arm_workplace.sName IS NULL OR Arm_workplace.sName = '' OR Arm_workplace.sOk IS NULL OR Arm_workplace.sOk = ''OR Arm_workplace.sETKS IS NULL OR Arm_workplace.sETKS = '' OR Arm_workplace.sSnils IS NULL OR Arm_workplace.sSnils = '') ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):

                if(!$vRow['sName'] || strlen(trim($vRow['sName'])) == 0) {$text = ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], 'Отсутствует название должности / професссии.', $text); $errCount++;}
                if(!$vRow['sOk'] || strlen(trim($vRow['sOk'])) == 0) {$text = ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], 'Отсутствует код ОК.', $text); $errCount++;}
                if(!$vRow['sETKS'] || strlen(trim($vRow['sETKS'])) == 0) {$text = ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], 'Отсутствует ЕТКС / КС.', $text); $errCount++;}
                if(!$vRow['sSnils'] || strlen(trim($vRow['sSnils'])) == 0) {$text = ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], 'Отсутствует СНИЛС.', $text); $errCount++;}

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_nameerr" onclick="RoollClick('nameerr');" class="rollDown">Отсутсвие данных рабочего места (<? echo($errCount); ?>)</div>
            <div id="body_nameerr" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>

<?
$sql = "SELECT Arm_workplace.id, Arm_workplace.iNumber, Arm_workplace.sName, Arm_rmPointsRm.idPoint FROM Arm_workplace LEFT JOIN Arm_rmPointsRm ON Arm_rmPointsRm.idRm = Arm_workplace.id WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1 GROUP BY Arm_workplace.id ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):

                if(!$vRow['idPoint'] || strlen(trim($vRow['idPoint'])) == 0) {$text = ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], 'Отсутствуют зоны / оборудование / материалы.', $text); $errCount++;};

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_nozone" onclick="RoollClick('nozone');" class="rollDown">Отсутсвие зон прибывания / оборудования (<? echo($errCount); ?>)</div>
            <div id="body_nozone" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>

<?
$sql = "SELECT Arm_rmPoints.id, Arm_rmPoints.sName as PointName, Arm_workplace.id as RmId, Arm_workplace.iNumber, Arm_workplace.sName, Arm_rmFactors.id as FactorsId FROM Arm_rmPointsRm LEFT JOIN Arm_workplace ON Arm_rmPointsRm.idRm = Arm_workplace.id LEFT JOIN Arm_rmPoints ON Arm_rmPointsRm.idPoint = Arm_rmPoints.id LEFT JOIN Arm_rmFactors ON Arm_rmFactors.idPoint = Arm_rmPoints.id WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1 AND Arm_rmPoints.sName NOT LIKE 'ПЭВМ%' GROUP BY Arm_rmPoints.id ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):

                if(!$vRow['FactorsId'] || strlen(trim($vRow['FactorsId'])) == 0) {$text = ErrCheckInsertErr($vRow['RmId'], $vRow['iNumber'], $vRow['sName'], $vRow['PointName'].'.', $text); $errCount++;};

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_NoFactors" onclick="RoollClick('NoFactors');" class="rollDown">Отсутсвие факторов в зонах прибывания / оборудовании (<? echo($errCount); ?>)</div>
            <div id="body_NoFactors" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>

<?
$sql = "SELECT Arm_workplace.id, Arm_workplace.iNumber, Arm_workplace.sName FROM Arm_workplace WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1 ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):

                $tmpStr = WorkFactors::GetTime($vRow['id']);
                if(!empty($tmpStr))
                {
                    $tmpStr = str_replace('<br>', ' ', $tmpStr);
                    $tmpStr = str_replace('Обратите внимание, время воздействия источников вредных факторов не соответствует продолжительности смены', 'Продолжительность смены', $tmpStr);
                    $text = ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], $tmpStr, $text); $errCount++;
                }

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_ErrTime" onclick="RoollClick('ErrTime');" class="rollDown">Превышение рабочего времени (<? echo($errCount); ?>).</div>
            <div id="body_ErrTime" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>

<?
$sql = "SELECT DISTINCT(Arm_Siz.SizName), Arm_Siz.Sert, Arm_workplace.id, Arm_workplace.iNumber, Arm_workplace.sName FROM Arm_Siz LEFT JOIN Arm_workplace ON Arm_workplace.id = Arm_Siz.rmId WHERE Arm_workplace.idGroup = $idGroup AND (Arm_Siz.Sert IS NULL OR Arm_Siz.Sert = '') GROUP BY Arm_Siz.SizName ORDER BY Arm_Siz.SizName;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):

                if(!empty($text)) $text .= '<br>';
                $text .= $vRow['SizName'].'.'; $errCount++;

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_SizSert" onclick="RoollClick('SizSert');" class="rollDown">Отсутсвие сертификата соответсвия СИЗ (<? echo($errCount); ?>).</div>
            <div id="body_SizSert" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>

<?
$sql = "SELECT Arm_workplace.id as RmId, Arm_workplace.iNumber, Arm_workplace.sName as WorkplaceName, Nd_factors.sName
FROM Arm_rmPointsRm
LEFT JOIN Arm_workplace ON Arm_rmPointsRm.idRm = Arm_workplace.id
LEFT JOIN Arm_rmPoints ON Arm_rmPointsRm.idPoint = Arm_rmPoints.id
LEFT JOIN Arm_rmFactors ON Arm_rmFactors.idPoint = Arm_rmPoints.id
LEFT JOIN Nd_factors ON Nd_factors.id = Arm_rmFactors.idFactor
WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1 AND Arm_rmFactors.idFactorGroup NOT IN (8,32) AND (Arm_rmFactors.var1 IS NULL OR Arm_rmFactors.var1 = '') AND (Arm_rmFactors.var2 IS NULL OR Arm_rmFactors.var2 = '') AND (Arm_rmFactors.var3 IS NULL OR Arm_rmFactors.var3 = '')
GROUP BY Arm_rmFactors.id
ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):

            //echo("[$errCount]");
            $text .= "<strong>[$vRow[iNumber] / $vRow[WorkplaceName]]</strong> $vRow[sName]<br>"; $errCount++;//ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], "sName", $text); $errCount++;

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_NullValue" onclick="RoollClick('NullValue');" class="rollDown">Отсутсвие результатов измерений (<? echo($errCount); ?>).</div>
            <div id="body_NullValue" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>


<?
/*
$sql = "SELECT Arm_workplace.id as RmId, Arm_workplace.iNumber, Arm_workplace.sName, iATotal
FROM Arm_workplace
WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1
ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysqli_fetch_assoc($vResult)):
            $bClass = true;

            if(strpos($vRow[sName], 'Наполнитель баллонов') !== false)
            {
                if($vRow[iATotal] <> 4)
                {
                    $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.2+<br>";
                    $errCount++;
                }
                $bClass = false;
            }

            if(strpos($vRow[sName], 'Сливщик-раз') !== false)
            {
                if($vRow[iATotal] <> 4)
                {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.2+<br>";
                $errCount++;
                }
                $bClass = false;
            }

            if(strpos($vRow[sName], 'газораздаточной стан') !== false)
            {
                if($vRow[iATotal] <> 4)
                {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.2+<br>";
                $errCount++;
                }
                $bClass = false;
            }

            if(strpos($vRow[sName], 'сварщик') !== false)
            {
                if($vRow[iATotal] <> 4)
                {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.2+<br>";
                $errCount++;
                }
                $bClass = false;
            }

            if(strpos($vRow[sName], 'дефектоскопист') !== false)
            {
                if($vRow[iATotal] <> 4)
                {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.2+<br>";
                $errCount++;
                }
                $bClass = false;
            }

            if(strpos($vRow[sName], 'медник') !== false)
            {
                if($vRow[iATotal] <> 4)
                {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.2+<br>";
                $errCount++;
                }
                $bClass = false;
            }

            if(strpos($vRow[sName], 'автомобиля') !== false)
            {
                if(strpos($vRow[sName], '2 гр') !== false)
                {
                    if($vRow[iATotal] <> 3) {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.1+<br>";
                $errCount++;
                    }
                $bClass = false;
                }

                if(strpos($vRow[sName], '3 гр') !== false)
                {
                    if($vRow[iATotal] <> 3) {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.1+<br>";
                $errCount++;
                    }
                $bClass = false;
                }
            }

            if(strpos($vRow[sName], 'эксплуатации и ремонту газового') !== false)
            {
                //129,131,134,141,287,349,417
                if( $vRow[iNumber] ==  129 ||
                  $vRow[iNumber] ==  131 ||
                  $vRow[iNumber] ==  134 ||
                  $vRow[iNumber] ==  141 ||
                  $vRow[iNumber] ==  287 ||
                  $vRow[iNumber] ==  349 ||
                  $vRow[iNumber] ==  417 )
                {
                    if($vRow[iATotal] <> 3) {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 3.1+<br>";
                $errCount++;
                    }
                $bClass = false;
                }
            }*/

            /*if(strpos($vRow[sName], 'ремонту газового') !== false)
            {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." возможно должно быть 3.1<br>";
                $errCount++;
                $bClass = false;
            }

            if($vRow[iATotal] > 2 && $bClass)
            {
                $text .= "<strong>[$vRow[iNumber] / $vRow[sName]]</strong> ".StringWork::iToClassNameLite($vRow[iATotal])." должно быть 1.0-2.0<br>";
                $errCount++;
            }

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_Assets" onclick="RoollClick('Assets');" class="rollDown">Проверка на заданные параметры оценок (<? echo($errCount); ?>).</div>
            <div id="body_Assets" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>

            <?
                /*
                Выборка всех точек группы данных
                SELECT Arm_rmPoints.id, Arm_rmPoints.sName FROM Arm_rmPoints JOIN Arm_rmPointsRm ON Arm_rmPointsRm.idPoint = Arm_rmPoints.id JOIN Arm_workplace ON Arm_workplace.id = Arm_rmPointsRm.idRm WHERE Arm_workplace.idGroup = 854 GROUP BY Arm_rmPoints.id ORDER BY Arm_rmPoints.sName

                Обновление времени
                UPDATE Arm_rmPointsRm SET Arm_rmPointsRm.sTime = 1 WHERE Arm_rmPointsRm.idPoint IN (8060, 8236, 8065, 8523, 8050, 7973, 8064, 7971, 7985, 8278, 7970, 8515, 8728, 8011, 8002, 8521, 8513, 7984, 8279, 8062, 8234, 8235, 7977, 8522, 8514, 8066, 8277, 8061, 7969, 8176, 7965, 8177, 8067, 8275, 7992, 7972, 7961, 8540, 8190, 8524, 8179, 8059, 8058, 8057, 8616, 8013, 8276, 7959, 7974, 7988, 7975, 8056, 8518, 8519, 8525, 7960, 8014, 8049, 8015, 7986, 7963, 7966, 8615, 7964)

                Обновление с условием по названию должности
                UPDATE Arm_rmPointsRm JOIN Arm_workplace ON Arm_workplace.id = Arm_rmPointsRm.idRm SET Arm_rmPointsRm.sTime = 1 WHERE Arm_rmPointsRm.idPoint IN (7962) AND Arm_workplace.sName LIKE ('%Водитель автомобиля%')

                */
            ?>


          </div></td>
        </tr>
      </table>
  </div></td>
</tr>
<tr class="blockmargin">
<td>&nbsp;</td>
</tr>
<tr class="blockmargin">
<td height="1px" bgcolor="#0099CC"></td>
</tr>
<tr class="blockmargin">
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><input type="submit" class="input_button" id="buttonClose"value="Закрыть" onclick="return PoupUpMessgeClose();"/></td>
</tr>
</table>
<?
function ErrCheckInsertErr($RmId, $RmNum, $RmName, $RmErrText, $allErrorText)
{
    if(!empty($allErrorText)) $allErrorText .= '<br>';
    $allErrorText .= "<strong>[$RmNum / $RmName]</strong> $RmErrText";
    return $allErrorText;
}
?>
<script>
$(document).ready(function(e) {

    progressAll_hide();

});
</script>

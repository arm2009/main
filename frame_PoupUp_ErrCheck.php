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
            while($vRow = mysql_fetch_assoc($vResult)):

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
            while($vRow = mysql_fetch_assoc($vResult)):

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
            while($vRow = mysql_fetch_assoc($vResult)):

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
            while($vRow = mysql_fetch_assoc($vResult)):

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
            while($vRow = mysql_fetch_assoc($vResult)):

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
            while($vRow = mysql_fetch_assoc($vResult)):

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
$sql = "SELECT Arm_workplace.iNumber, Arm_workplace.iATotal FROM Arm_workplace
WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1 ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            while($vRow = mysql_fetch_assoc($vResult)):

            //echo("[$errCount]");
            $text .= "'$vRow[iNumber]' => '$vRow[iATotal]',"; $errCount++;//ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], "sName", $text); $errCount++;

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_AssetScheme" onclick="RoollClick('AssetScheme');" class="rollDown">Слепок итоговых оценок на текущую дату (<? echo($errCount); ?>).</div>
            <div id="body_AssetScheme" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               [<? echo($text); ?>]
            </div>
            <? endif; ?>
            
<?
$sql = "SELECT Arm_workplace.iNumber, Arm_workplace.iATotal FROM Arm_workplace
WHERE Arm_workplace.idGroup = $idGroup AND `Arm_workplace`.`idParent` > -1 ORDER BY Arm_workplace.iNumber;";
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
$text = ''; $errCount = 0;
            
			$CheckAsset = array('1' => '2','2' => '2','3' => '2','4' => '2','5' => '2','6' => '2','7' => '2','8' => '2','9' => '2','10' => '2','11' => '2','12' => '2','13' => '2','14' => '2','15' => '2','16' => '2','17' => '2','18' => '2','19' => '2','20' => '2','21' => '2','22' => '2','23' => '2','24' => '2','25' => '2','26' => '2','27' => '2','28' => '2','29' => '2','30' => '2','31' => '2','32' => '2','33' => '2','34' => '2','35' => '2','36' => '2','37' => '2','38' => '2','39' => '2','40' => '2','41' => '2','42' => '2','43' => '4','44' => '4','45' => '3','46' => '2','47' => '3','48' => '2','49' => '4','50' => '2','51' => '2','52' => '2','53' => '2','54' => '2','55' => '2','56' => '2','57' => '2','58' => '2','59' => '2','60' => '2','61' => '2','62' => '4','63' => '2','64' => '2','65' => '4','66' => '2','67' => '2','68' => '2','69' => '4','70' => '2','71' => '2','72' => '2','73' => '2','74' => '2','75' => '2','76' => '4','77' => '4','78' => '2','79' => '2','80' => '2','81' => '2','82' => '2','83' => '4','84' => '2','85' => '2','86' => '2','87' => '2','88' => '2','89' => '2','90' => '2','91' => '2','92' => '2','93' => '2','94' => '2','95' => '2','96' => '2','97' => '2','98' => '2','99' => '2','100' => '2','101' => '2','102' => '2','103' => '2','104' => '2','105' => '2','106' => '2','107' => '2','108' => '2','109' => '2','110' => '2','111' => '2','112' => '2','113' => '2','114' => '2','115' => '2','116' => '2','117' => '2','118' => '2','119' => '2','120' => '2','121' => '2','122' => '2','123' => '2','124' => '2','125' => '2','126' => '2','127' => '2','128' => '2','129' => '2','130' => '2','131' => '2','132' => '2','133' => '2','134' => '2','135' => '2','136' => '2','137' => '2','138' => '4','139' => '4','140' => '4','141' => '2','142' => '2','143' => '2','144' => '2','145' => '3','146' => '2','147' => '4','148' => '4','149' => '2','150' => '2','151' => '3','152' => '2','153' => '3','154' => '2','155' => '3','156' => '2','157' => '2','158' => '2','159' => '2','160' => '2','161' => '2','162' => '2','163' => '2','164' => '2','165' => '2','166' => '2','167' => '2','168' => '2','169' => '2','170' => '2','171' => '2','172' => '2','173' => '2','174' => '2','175' => '2','176' => '2','177' => '2','178' => '2','179' => '2','180' => '2','181' => '2','182' => '2','183' => '2','184' => '2','185' => '2','186' => '2','187' => '2','188' => '2','189' => '2','190' => '2','191' => '2','192' => '2','193' => '2','194' => '2','195' => '2','196' => '2','197' => '2','198' => '2','199' => '2','200' => '2','201' => '2','202' => '2','203' => '2','204' => '2','205' => '2','206' => '2','207' => '2','208' => '2','209' => '2','210' => '2','211' => '2','212' => '2','213' => '2','214' => '2','215' => '2','216' => '2','217' => '2','218' => '2','219' => '2','220' => '2','221' => '2','222' => '2','223' => '2','224' => '2','225' => '2','226' => '2','227' => '2','228' => '2','229' => '2','230' => '2','231' => '2','232' => '2','233' => '2','234' => '2','235' => '2','236' => '2','237' => '2','238' => '2','239' => '2','240' => '2','241' => '2','242' => '2','243' => '2','244' => '2','245' => '2','246' => '2','247' => '2','248' => '2','249' => '2','250' => '2','251' => '2','252' => '2','253' => '2','254' => '2','255' => '2','256' => '2','257' => '2','258' => '2','259' => '2','260' => '2','261' => '2','262' => '2','263' => '2','264' => '2','265' => '2','266' => '2','267' => '2','268' => '2','269' => '2','270' => '3','271' => '2','272' => '2','273' => '2','274' => '2','275' => '2','276' => '2','277' => '2','278' => '2','279' => '2','280' => '2','281' => '2','282' => '2','283' => '2','284' => '2','285' => '4','286' => '2','287' => '2','288' => '4','289' => '2','290' => '2','291' => '4','292' => '2','293' => '2','294' => '2','295' => '2','296' => '2','297' => '4','298' => '2','299' => '2','300' => '2','301' => '2','302' => '2','303' => '2','304' => '2','305' => '2','306' => '2','307' => '2','308' => '2','309' => '2','310' => '2','311' => '2','312' => '2','313' => '2','314' => '2','315' => '3','316' => '2','317' => '2','318' => '2','319' => '2','320' => '2','321' => '2','322' => '2','323' => '2','324' => '2','325' => '2','326' => '2','327' => '2','328' => '2','329' => '2','330' => '2','331' => '2','332' => '2','333' => '2','334' => '2','335' => '2','336' => '2','337' => '2','338' => '2','339' => '2','340' => '2','341' => '2','342' => '2','343' => '2','344' => '2','345' => '2','346' => '4','347' => '2','348' => '4','349' => '2','350' => '4','351' => '2','352' => '2','353' => '3','354' => '4','355' => '3','356' => '3','357' => '2','358' => '2','359' => '2','360' => '2','361' => '2','362' => '2','363' => '2','364' => '2','365' => '2','366' => '2','367' => '2','368' => '2','369' => '2','370' => '2','371' => '2','372' => '3','373' => '2','374' => '2','375' => '2','376' => '2','377' => '2','378' => '2','379' => '2','380' => '2','381' => '2','382' => '2','383' => '2','384' => '2','385' => '2','386' => '4','387' => '2','388' => '2','389' => '2','390' => '2','391' => '2','392' => '2','393' => '2','394' => '2','395' => '2','396' => '2','397' => '2','398' => '2','399' => '2','400' => '2','401' => '2','402' => '2','403' => '2','404' => '2','405' => '4','406' => '2','407' => '2','408' => '2','409' => '4','410' => '2','411' => '2','412' => '2','413' => '2','414' => '2','415' => '2','416' => '2','417' => '2','418' => '4','419' => '2','420' => '2','421' => '2','422' => '2','423' => '2','424' => '2','425' => '2','426' => '2','427' => '2','428' => '2','429' => '4','430' => '2','431' => '2','432' => '3','433' => '3','434' => '2','435' => '2','436' => '2','437' => '2');
			
			while($vRow = mysql_fetch_assoc($vResult)):
			
			if($vRow[iATotal] <> $CheckAsset[$vRow[iNumber]])
            $text .= "$vRow[iNumber] => $vRow[iATotal], а должно быть ".$CheckAsset[$vRow[iNumber]]."<br />"; $errCount++;//ErrCheckInsertErr($vRow['id'], $vRow['iNumber'], $vRow['sName'], "sName", $text); $errCount++;

            endwhile;
            if (!empty($text) && $errCount > 0): ?>
            <div id="header_AssetSchemeCheck" onclick="RoollClick('AssetSchemeCheck');" class="rollDown">Проверка слепка (<? echo($errCount); ?>).</div>
            <div id="body_AssetSchemeCheck" style="display:none;margin:10px; margin-left:30px;" class="log_text">
               <? echo($text); ?>
            </div>
            <? endif; ?>


<?

/*Слепок группы данных на 11.12.2015*/
/*
array('1' => '2','2' => '2','3' => '2','4' => '2','5' => '2','6' => '2','7' => '2','8' => '2','9' => '2','10' => '2','11' => '2','12' => '2','13' => '2','14' => '2','15' => '2','16' => '2','17' => '2','18' => '2','19' => '2','20' => '2','21' => '2','22' => '2','23' => '2','24' => '2','25' => '2','26' => '2','27' => '2','28' => '2','29' => '2','30' => '2','31' => '2','32' => '2','33' => '2','34' => '2','35' => '2','36' => '2','37' => '2','38' => '2','39' => '2','40' => '2','41' => '2','42' => '2','43' => '4','44' => '4','45' => '3','46' => '2','47' => '3','48' => '2','49' => '4','50' => '2','51' => '2','52' => '2','53' => '2','54' => '2','55' => '2','56' => '2','57' => '2','58' => '2','59' => '2','60' => '2','61' => '2','62' => '4','63' => '2','64' => '2','65' => '4','66' => '2','67' => '2','68' => '2','69' => '4','70' => '2','71' => '2','72' => '2','73' => '2','74' => '2','75' => '2','76' => '4','77' => '4','78' => '2','79' => '2','80' => '2','81' => '2','82' => '2','83' => '4','84' => '2','85' => '2','86' => '2','87' => '2','88' => '2','89' => '2','90' => '2','91' => '2','92' => '2','93' => '2','94' => '2','95' => '2','96' => '2','97' => '2','98' => '2','99' => '2','100' => '2','101' => '2','102' => '2','103' => '2','104' => '2','105' => '2','106' => '2','107' => '2','108' => '2','109' => '2','110' => '2','111' => '2','112' => '2','113' => '2','114' => '2','115' => '2','116' => '2','117' => '2','118' => '2','119' => '2','120' => '2','121' => '2','122' => '2','123' => '2','124' => '2','125' => '2','126' => '2','127' => '2','128' => '2','129' => '2','130' => '2','131' => '2','132' => '2','133' => '2','134' => '2','135' => '2','136' => '2','137' => '2','138' => '4','139' => '4','140' => '4','141' => '2','142' => '2','143' => '2','144' => '2','145' => '3','146' => '2','147' => '4','148' => '4','149' => '2','150' => '2','151' => '3','152' => '2','153' => '3','154' => '2','155' => '3','156' => '2','157' => '2','158' => '2','159' => '2','160' => '2','161' => '2','162' => '2','163' => '2','164' => '2','165' => '2','166' => '2','167' => '2','168' => '2','169' => '2','170' => '2','171' => '2','172' => '2','173' => '2','174' => '2','175' => '2','176' => '2','177' => '2','178' => '2','179' => '2','180' => '2','181' => '2','182' => '2','183' => '2','184' => '2','185' => '2','186' => '2','187' => '2','188' => '2','189' => '2','190' => '2','191' => '2','192' => '2','193' => '2','194' => '2','195' => '2','196' => '2','197' => '2','198' => '2','199' => '2','200' => '2','201' => '2','202' => '2','203' => '2','204' => '2','205' => '2','206' => '2','207' => '2','208' => '2','209' => '2','210' => '2','211' => '2','212' => '2','213' => '2','214' => '2','215' => '2','216' => '2','217' => '2','218' => '2','219' => '2','220' => '2','221' => '2','222' => '2','223' => '2','224' => '2','225' => '2','226' => '2','227' => '2','228' => '2','229' => '2','230' => '2','231' => '2','232' => '2','233' => '2','234' => '2','235' => '2','236' => '2','237' => '2','238' => '2','239' => '2','240' => '2','241' => '2','242' => '2','243' => '2','244' => '2','245' => '2','246' => '2','247' => '2','248' => '2','249' => '2','250' => '2','251' => '2','252' => '2','253' => '2','254' => '2','255' => '2','256' => '2','257' => '2','258' => '2','259' => '2','260' => '2','261' => '2','262' => '2','263' => '2','264' => '2','265' => '2','266' => '2','267' => '2','268' => '2','269' => '2','270' => '3','271' => '2','272' => '2','273' => '2','274' => '2','275' => '2','276' => '2','277' => '2','278' => '2','279' => '2','280' => '2','281' => '2','282' => '2','283' => '2','284' => '2','285' => '4','286' => '2','287' => '2','288' => '4','289' => '2','290' => '2','291' => '4','292' => '2','293' => '2','294' => '2','295' => '2','296' => '2','297' => '4','298' => '2','299' => '2','300' => '2','301' => '2','302' => '2','303' => '2','304' => '2','305' => '2','306' => '2','307' => '2','308' => '2','309' => '2','310' => '2','311' => '2','312' => '2','313' => '2','314' => '2','315' => '3','316' => '2','317' => '2','318' => '2','319' => '2','320' => '2','321' => '2','322' => '2','323' => '2','324' => '2','325' => '2','326' => '2','327' => '2','328' => '2','329' => '2','330' => '2','331' => '2','332' => '2','333' => '2','334' => '2','335' => '2','336' => '2','337' => '2','338' => '2','339' => '2','340' => '2','341' => '2','342' => '2','343' => '2','344' => '2','345' => '2','346' => '4','347' => '2','348' => '4','349' => '2','350' => '4','351' => '2','352' => '2','353' => '3','354' => '4','355' => '3','356' => '3','357' => '2','358' => '2','359' => '2','360' => '2','361' => '2','362' => '2','363' => '2','364' => '2','365' => '2','366' => '2','367' => '2','368' => '2','369' => '2','370' => '2','371' => '2','372' => '3','373' => '2','374' => '2','375' => '2','376' => '2','377' => '2','378' => '2','379' => '2','380' => '2','381' => '2','382' => '2','383' => '2','384' => '2','385' => '2','386' => '4','387' => '2','388' => '2','389' => '2','390' => '2','391' => '2','392' => '2','393' => '2','394' => '2','395' => '2','396' => '2','397' => '2','398' => '2','399' => '2','400' => '2','401' => '2','402' => '2','403' => '2','404' => '2','405' => '4','406' => '2','407' => '2','408' => '2','409' => '4','410' => '2','411' => '2','412' => '2','413' => '2','414' => '2','415' => '2','416' => '2','417' => '2','418' => '4','419' => '2','420' => '2','421' => '2','422' => '2','423' => '2','424' => '2','425' => '2','426' => '2','427' => '2','428' => '2','429' => '4','430' => '2','431' => '2','432' => '3','433' => '3','434' => '2','435' => '2','436' => '2','437' => '2');
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

<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/WorkPlace.php";

	if(isset($_POST['find']) && strlen($_POST['find'])>3)
	{
		$sql ="SELECT `Nd_ok01694`.`sName`, `Nd_ok01694`.`sCode`, `Nd_ok01694`.`id`, (`Nd_Etks`.`sName`) AS `sfEtks`, `Nd_ok01694`.`sKch`, `Nd_ok01694`.`sRazr`, `Nd_ok01694`.`sOkz`, `Nd_ok01694`.`sKat`, `Nd_ok01694`.`sEtks`, `Nd_ok01694`.`sNoChild`, `Nd_ok01694`.`sNoWoman`, `Nd_ok01694`.`sBasePension` FROM `Nd_ok01694`, `Nd_Etks` WHERE (`Nd_ok01694`.`sCode` LIKE '%".DbConnect::ToBaseStr($_POST['find'])."%' OR `Nd_ok01694`.`sName` LIKE '%".DbConnect::ToBaseStr($_POST['find'])."%')  AND `Nd_ok01694`.`sEtks` = `Nd_Etks`.`iCode` ORDER BY `Nd_ok01694`.`iPrioritet`, `Nd_ok01694`.`sName`;";
		$result = DbConnect::GetSqlQuery($sql);	
		if (mysql_num_rows($result) > 0)
		{
			while($vRow = mysql_fetch_array($result))
			{
//Получение доп. инфы по выпускам ЕТКС
$sql = "SELECT `Nd_Etks`.`sName`, `Nd_Link_Ok01694_Etks`.`sRazdel`, `Nd_Link_Ok01694_Etks`.`sDolgnName` FROM `Nd_Link_Ok01694_Etks`, `Nd_Etks` WHERE `idOk01694` = ".$vRow[sCode]." AND `Nd_Link_Ok01694_Etks`.`idEtks` = `Nd_Etks`.`iCode` LIMIT 0, 30 ";
$resulte = DbConnect::GetSqlQuery($sql);
$tmpEtks = '';
while($vRowe = mysql_fetch_array($resulte))
{
	if(strlen(trim($tmpEtks)) > 0) $tmpEtks.='<br /><br />';
	$tmpEtks .= $vRowe[sName].', раздел: '.strtolower($vRowe[sRazdel]).' &#8212; '.$vRowe[sDolgnName].'.';
}
if(strlen($tmpEtks) > 0) $tmpEtks = '<tr><td class="comment gray" valign="top">&nbsp;</td><td class="comment gray" style="text-align:justify">&nbsp;</td></tr><tr><td class="comment lgray" valign="top">Упоминание</td><td class="comment dgray" style="text-align:justify">'.$tmpEtks.'</td></tr>';

//Получение доп. инфы по ограничениям труда женщин и подростков
$tmpNoChild = '';
$tmpPens = '';
if(strlen(trim($vRow[sBasePension])) > 0)
{
	$tmpPens .= '<tr><td class="comment gray" valign="top">&nbsp;</td><td class="comment gray" style="text-align:justify">&nbsp;</td></tr><tr><td class="comment lgray" valign="top">Досрочная пенсия</td><td class="comment dgray" style="text-align:justify">'.WorkPlace::GetFullNamePens($vRow[sBasePension]).'</td></tr>';
}
if(strlen(trim($vRow[sNoChild])) > 0)
{
	$tmpNoChild .= 'Применение труда лиц не достигших 18 лет - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 163, '.$vRow[sNoChild].').';
}
if(strlen(trim($vRow[sNoWoman])) > 0)
{
	if(strlen($tmpNoChild) > 0) {$tmpNoChild .= '<br /><br />';}
	$tmpNoChild .= 'Применение труда женщин - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 162, '.$vRow[sNoWoman].').';
}
if(strlen($tmpNoChild) > 0){$tmpNoChild = '<tr><td class="comment gray" valign="top">&nbsp;</td><td class="comment gray" style="text-align:justify">&nbsp;</td></tr><tr><td class="comment lgray" valign="top">Ограничение</td><td class="comment dgray" style="text-align:justify">'.$tmpNoChild.'</td></tr>';}

				if($vRow[sCode][0] == '1' || $vRow[sCode][0] == '3')
				{			
//Работуны
echo('<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:15px;padding:10px;" class="selectblock"><tr><td width="100"><h3>'.$vRow[sCode].'</h3></td><td><h3>'.$vRow[sName].'</h3></td></tr><tr><td class="comment lgray" valign="top">Профессия<br />в ОК 016 - 94</td><td class="comment dgray" style="text-align:justify">Диапазон тарифных разрядов: '.$vRow[sRazr].'<br />Контрольное число: '.$vRow[sKch].'<br />Код выпуска ЕТКС: '.$vRow[sEtks].'<br />Код по ОКЗ: '.$vRow[sOkz].'<br /><br />'.$vRow[sfEtks].'</td></tr>'.$tmpEtks.$tmpNoChild.$tmpPens.'</table>');
				}
				else
				{
//Служивые
if($vRow[sEtks] == -1){$vRow[sfEtks] = '';} else {$vRow[sfEtks] = '<br /><br />'.$vRow[sfEtks];}
echo('<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:15px;padding:10px;" class="selectblock"><tr><td width="100"><h3>'.$vRow[sCode].'</h3></td><td><h3>'.$vRow[sName].'</h3></td></tr><tr><td class="comment lgray" valign="top">Должность<br />в ОК 016 - 94</td><td class="comment dgray" style="text-align:justify">Контрольное число: '.$vRow[sKch].'<br />Код категории: '.$vRow[sKat].'<br />Код по ОКЗ: '.$vRow[sOkz].''.$vRow[sfEtks].'</td></tr>'.$tmpEtks.$tmpNoChild.$tmpPens.'</table>');
				}
			}
			
			//Дисклэймер
			echo('<div class="comment" style="padding=10px;border-top:1px solid #0099CC;margin-top:15px;">Обратите внимание: над справочником идет активная работа, а представленная информация может быть не полной.<br />
<a href="http://arm2009SUOT.reformal.ru" onclick="Reformal.widgetOpen();return false;" onmouseover="Reformal.widgetPreload();">Вы можете помочь в работе над справочником направив нам свои замечания и предложения.</a></div>');
		}
		else
		{
			echo('<br />Уточните запрос...');
		}
	}
	else
	{
		echo('<h3 style="margin-top:75px;margin-bottom:50px;">Мы ничего не смогли найти...<br />
Может быть стоит уточнить запрос?</h3>');
	}
//<br /><br /><span class="gray">Благодарим за помощь: antohag.</span>
?>
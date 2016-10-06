<?php
	include_once('UserControl/userControl.php');
	include_once('Util/String.php');
	include_once('aj.suot.php');
	include_once ('MainWork/GroupWork.php');
	include_once('ajax.php');

    UserControl::isUserValidExit();

	$idGroup = -1;

	if (isset($_GET['action']) && $_GET['action']=='add')
	{
		//Работа
		$idGroup = GroupWork::AddGroup();
		$aGroup = GroupWork::ReadGroupFull($idGroup);
		$aComiss = GroupWork::ReadComiss($idGroup);
	}

	if ($_GET['action'] == 'edit')
	{

		if (isset($_GET['id']))
		{
			$idGroup = $_GET['id'];
			if (!GroupWork::IsCanEditGroup($_GET['id']))
			{
				header ('Location: index.php');
				exit();
			}
			else
			{
				$aGroup = GroupWork::ReadGroupFull($_GET['id']);
				$aComiss = GroupWork::ReadComiss($_GET['id']);
			}
		}
	}



	if (isset($_POST['sName']))
	{
		GroupWork::SaveGroup($_GET['id'], $_POST['sName'], $_POST['select'], $_POST['sFullName'], $_POST['sPlace'], $_POST['sEmail'], $_POST['sNameDirector'], $_POST['sInn'], $_POST['sOgrn'], $_POST['sOkved'], $_POST['sOkpo'], $_POST['sOkogu'], $_POST['sOkato'], $_POST['sPredsName'], $_POST['sPredsPost'], $_POST['sPostDirector'], $_POST['sPhone'],$_POST['sPNumTenesy'],$_POST['sPNumHeavy'],$_POST['sPNumAir'],$_POST['sPNumLight'],$_POST['sPNumNoise'],$_POST['sPNumNoiseNoise'],$_POST['sPNumClimate'],$_POST['sExpEndDoc'],$_POST['sExpEndDate'],$_POST['iRmTotalCount'],$_POST['iWorkerTotal'],$_POST['iWorkerTotalWoman'],$_POST['iWorkerTotalYang'],$_POST['iWorkerTotalMedical'],$_POST['dStartDate'],$_POST['dEndDate'],$_POST['sDocName'],$_POST['sNTens'],$_POST['sNHeavy'],$_POST['sNAir'],$_POST['sNLight'],$_POST['sNNoise'],$_POST['sNClimate']);

		header ('Location: work_DataGroup.php?id='.$_GET['id']);
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>
<body><? include('Frame/frame_Top.php'); ?><? include_once('Frame/frame_PoupUp.php'); ?>
<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left"><h1 class="white" id="mainHead" tag="<?php echo $idGroup; ?>">Информация группы данных</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);"><table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td id="check1_hr" height="50" align="center" class="corner_act white" onclick="CheckPress(1);">Основное</td>
            <td id="check2_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(2);">Комиссия</td>
            <td id="check3_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(3);">Эксперты</td>
            <td id="check4_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(4);">Измерения</td>
            <td id="check5_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(5);">Дополнительно</td>
          </tr>
          <tr>
            <td id="check1_bd" height="15" class="corner"></td>
            <td id="check2_bd" height="15"></td>
            <td id="check3_bd" height="15"></td>
            <td id="check4_bd" height="15"></td>
            <td id="check5_bd" height="15"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>
<form action="work_MainInfo.php?id=<?php echo $idGroup; ?>" method="post" onsubmit="return IsFormValidate();">
<div id="Check_Bl_1" class="rollBody">
  <table width="715" border="0" cellspacing="0" cellpadding="0">
<?
$aNamesGr = GroupWork::FillWorkSpace();
$iGroups = GroupWork::GetMyGroupCount();
$tmpWorkSpaceRow = '';

if(count($aNamesGr) > 1 && $aGroup['idParent'] == UserControl::GetUserLoginId())
{
		//Есть доступные пространства, группа находится в нашем рабочем пространстве и мы можем оставить её там
		$tmpWorkSpaceRow = '<tr class="blockmargin"><td align="left" valign="middle" class="comment">Рабочее пространство</td>
		<td>&nbsp;</td>
		<td class="comment"><select name="select" id="select" class="input_field_micro input_field_background input_field_445" style="width:100%;" onchange="ChangeWorkSpace(); ChangeMessageChange();">';

		//Вносим доступные рабочие пространства
		for ($iCnt = 0 ; $iCnt < count($aNamesGr); $iCnt++)
		{
			if($aGroup[idParent] == $aNamesGr[$iCnt][0]){$tmpSelector = ' selected="selected"';}else{$tmpSelector = '';}
			$tmpWorkSpaceRow .= '<option value="'.$aNamesGr[$iCnt][0].'"'.$tmpSelector.'>'.$aNamesGr[$iCnt][1].'</option>';
		}

		$tmpWorkSpaceRow .= '</select></td>
		</tr>';
}
else
{
	//Нет доступного пространства или группа находится в чужом пространстве
	$tmpWorkSpaceRow = '<tr class="blockmargin"><td align="left" valign="middle" class="comment">Рабочее пространство</td>
	<td>&nbsp;</td>
	<td class="comment">'.UserControl::GetUserFieldValueFromId('sOrgName',$aGroup['idParent']).'<input id="select" name="select" type="hidden" value="'.$aGroup['idParent'].'" /></td>
	</tr>';
}

//Вывод
echo($tmpWorkSpaceRow);
?>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Название группы данных</td>
      <td width="30">&nbsp;</td>
      <td><input name="sName" type="text" class="input_field_micro input_field_background input_field_445" id="sName" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value="<?php if (isset($aGroup['sName'])) {echo $aGroup['sName']; } else { echo 'Новая группа данных';}?>" maxlength="255"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">&nbsp;</td>
      <td width="30">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Полное наименование</td>
      <td width="30">&nbsp;</td>
      <td><label for="textfield4"></label>
        <input name="sFullName" type="text" class="input_field_micro input_field_background input_field_445" id="sFullName" value="<?php echo $aGroup['sFullName']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" maxlength="255"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Место нахождения</td>
      <td width="30">&nbsp;</td>
      <td><label for="textfield2"></label>
        <input name="sPlace" type="text" class="input_field_micro input_field_background input_field_445" id="sPlace" value="<?php echo $aGroup['sPlace']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Контактный телефон</td>
      <td width="30">&nbsp;</td>
      <td><label for="textfield2"></label>
        <input name="sPhone" type="text" class="input_field_micro input_field_background input_field_445" id="sPhone" value="<?php echo $aGroup['sPhone']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Адрес электронной почты</td>
      <td width="30">&nbsp;</td>
      <td><input name="sEmail" type="text" class="input_field_micro input_field_background input_field_445" id="sEmail" value="<?php echo $aGroup['sEmail']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">ИНН</td>
      <td width="30">&nbsp;</td>
      <td><input name="sInn" type="text" class="input_field_micro input_field_background input_field_445" id="sInn" value="<?php echo $aGroup['sInn']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">ОГРН</td>
      <td width="30">&nbsp;</td>
      <td><input name="sOgrn" type="text" class="input_field_micro input_field_background input_field_445" id="sOgrn" value="<?php echo $aGroup['sOgrn']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">ОКВЭД</td>
      <td width="30">&nbsp;</td>
      <td><input name="sOkved" type="text" class="input_field_micro input_field_background input_field_445" id="sOkved" value="<?php echo $aGroup['sOkved']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">ОКПО</td>
      <td width="30">&nbsp;</td>
      <td><input name="sOkpo" type="text" class="input_field_micro input_field_background input_field_445" id="sOkpo" value="<?php echo $aGroup['sOkpo']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">ОКОГУ</td>
      <td width="30">&nbsp;</td>
      <td><input name="sOkogu" type="text" class="input_field_micro input_field_background input_field_445" id="sOkogu" value="<?php echo $aGroup['sOkogu']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">ОКТМО</td>
      <td width="30">&nbsp;</td>
      <td><input name="sOkato" type="text" class="input_field_micro input_field_background input_field_445" id="sOkato" value="<?php echo $aGroup['sOkato']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">&nbsp;</td>
      <td width="30">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Должность руководителя</td>
      <td width="30">&nbsp;</td>
      <td><input name="sPostDirector" type="text" class="input_field_micro input_field_background input_field_445" id="sPostDirector" value="<?php echo $aGroup['sPostDirector']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Ф.И.О. руководителя</td>
      <td width="30">&nbsp;</td>
      <td><input name="sNameDirector" type="text" class="input_field_micro input_field_background input_field_445" id="sNameDirector" value="<?php echo $aGroup['sNameDirector']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">&nbsp;</td>
      <td width="30">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Должность председателя комиссии</td>
      <td width="30">&nbsp;</td>
      <td><input name="sPredsPost" type="text" class="input_field_micro input_field_background input_field_445" id="sPredsPost" value="<?php echo $aGroup['sPredsPost']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Ф.И.О. председателя комиссии</td>
      <td width="30">&nbsp;</td>
      <td><input name="sPredsName" type="text" class="input_field_micro input_field_background input_field_445" id="sPredsName" value="<?php echo $aGroup['sPredsName']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">&nbsp;</td>
      <td width="30">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Общее количество рабочих мест</td>
      <td width="30">&nbsp;</td>
      <td><input name="iRmTotalCount" type="text" class="input_field_micro input_field_background input_field_445" id="iRmTotalCount" value="<?php echo $aGroup['iRmTotalCount']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Общее количество сотрудников</td>
      <td width="30">&nbsp;</td>
      <td><input name="iWorkerTotal" type="text" class="input_field_micro input_field_background input_field_445" id="iWorkerTotal" value="<?php echo $aGroup['iWorkerTotal']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">В том числе женщин</td>
      <td width="30">&nbsp;</td>
      <td><input name="iWorkerTotalWoman" type="text" class="input_field_micro input_field_background input_field_445" id="iWorkerTotalWoman" value="<?php echo $aGroup['iWorkerTotalWoman']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">В том числе подростков</td>
      <td width="30">&nbsp;</td>
      <td><input name="iWorkerTotalYang" type="text" class="input_field_micro input_field_background input_field_445" id="iWorkerTotalYang" value="<?php echo $aGroup['iWorkerTotalYang']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">В том числе инвалидов</td>
      <td width="30">&nbsp;</td>
      <td><input name="iWorkerTotalMedical" type="text" class="input_field_micro input_field_background input_field_445" id="iWorkerTotalMedical" value="<?php echo $aGroup['iWorkerTotalMedical']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
  </table>
</div>
<div id="Check_Bl_2" class="rollBody" style="display:none;">
<div class="nowBlock" id="AddonFunctions">Члены комиссии по проведению СОУТ.<br /><br /><span class="comment">Информация используется при оформлении отчета.</span></div>
<div class="block_micro block_left_round block_right_round block_add pointer" id="ComButtonAdd" onclick="ClickAddComiss()">Добавить члена комиссии</div>
<?php
if (count($aComiss) > 0)
{
	foreach ($aComiss as $aCom)
	{
		echo GetDivComiss($aCom[1], $aCom[2], $aCom[0], 'display: yes;');
	}
}
?>
</div>
<div id="Check_Bl_3" class="rollBody" style="display:none;">
<div class="nowBlock" id="AddonFunctions">Сведения об экспертах проводивших оценку и подготовивиших отчет СОУТ.<br /><br /><span class="comment">Источником данных является информация указанная в разделе "Данные организации проводящей СОУТ".<br />Информация используется при оформлении отчета и протоколов измерений.</span></div>
<div class="block_micro block_left_round block_right_round block_add pointer" id="InfoSOUTButtonAdd" onclick="PoupUpMessgeAjax('frame_PoupUp_AjaxSelectExpertData.php?target=<? echo($idGroup); ?>');">Добавить эксперта</div>
<?
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sSertNum, dSertDate, sPost, sReestrNum FROM Arm_groupStuff WHERE idGroup = '.$idGroup.' AND `bExpert` = 1;');
if (mysql_num_rows($vResult) > 0)
{
	while($vRow = mysql_fetch_array($vResult))
	{
		echo SuotWork::AddInfoStuffDiv($vRow['id'], $vRow['sName'], $vRow['sPost'], true);
	}
}
?>
</div>
<div id="Check_Bl_4" class="rollBody" style="display:none;">

Номера протоколов измерений

<table width="715" border="0" cellspacing="0" cellpadding="0">
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Напряженность трудового процесса</td>
    <td>&nbsp;</td>
    <td><input name="sPNumTenesy" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumTenesy" value="<?php echo $aGroup['sPNumTenesy']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Тяжесть трудового процесса</td>
    <td>&nbsp;</td>
    <td><input name="sPNumHeavy" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumHeavy" value="<?php echo $aGroup['sPNumHeavy']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Воздух рабочей зоны</td>
    <td>&nbsp;</td>
    <td><input name="sPNumAir" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumAir" value="<?php echo $aGroup['sPNumAir']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Световая среда</td>
    <td>&nbsp;</td>
    <td><input name="sPNumLight" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumLight" value="<?php echo $aGroup['sPNumLight']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
    <tr class="blockmargin">
      <td align="left" valign="middle" class="comment"> Виброакустика</td>
      <td>&nbsp;</td>
      <td><input name="sPNumNoise" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumNoise" value="<?php echo $aGroup['sPNumNoise']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
	</tr>
		<tr class="blockmargin">
			<td align="left" valign="middle" class="comment"> Шум</td>
			<td>&nbsp;</td>
			<td><input name="sPNumNoiseNoise" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumNoiseNoise" value="<?php echo $aGroup['sPNumNoiseNoise']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
		</tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Микроклимат</td>
      <td width="30">&nbsp;</td>
      <td><input name="sPNumClimate" type="text" class="input_field_micro input_field_background input_field_445" id="sPNumClimate" value="<?php echo $aGroup['sPNumClimate']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
</table>

<br />Примечания к протоколам измерений

<table width="715" border="0" cellspacing="0" cellpadding="0">
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Напряженность трудового процесса</td>
    <td>&nbsp;</td>
    <td><input name="sNTens" type="text" class="input_field_micro input_field_background input_field_445" id="sNTens" value="<?php echo $aGroup['sNTens']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Тяжесть трудового процесса</td>
    <td>&nbsp;</td>
    <td><input name="sNHeavy" type="text" class="input_field_micro input_field_background input_field_445" id="sNHeavy" value="<?php echo $aGroup['sNHeavy']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Воздух рабочей зоны</td>
    <td>&nbsp;</td>
    <td><input name="sNAir" type="text" class="input_field_micro input_field_background input_field_445" id="sNAir" value="<?php echo $aGroup['sNAir']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Световая среда</td>
    <td>&nbsp;</td>
    <td><input name="sNLight" type="text" class="input_field_micro input_field_background input_field_445" id="sNLight" value="<?php echo $aGroup['sNLight']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
    <tr class="blockmargin">
      <td align="left" valign="middle" class="comment"> Виброакустика</td>
      <td>&nbsp;</td>
      <td><input name="sNNoise" type="text" class="input_field_micro input_field_background input_field_445" id="sNNoise" value="<?php echo $aGroup['sNNoise']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
    <tr class="blockmargin">
      <td width="238" align="left" valign="middle" class="comment">Микроклимат</td>
      <td width="30">&nbsp;</td>
      <td><input name="sNClimate" type="text" class="input_field_micro input_field_background input_field_445" id="sNClimate" value="<?php echo $aGroup['sNClimate']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
    </tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr class="blockmargin"><td>&nbsp;</td></tr><tr class="blockmargin"><td height="1px" bgcolor="#0099CC"></td></tr><tr class="blockmargin"><td>&nbsp;</td></tr></table>


<div class="nowBlock" id="AddonFunctions">Сведения об участниках измерений, средствах измерения и аккредитации.<br /><br /><span class="comment">Источником данных является информация указанная в разделе "Данные организации проводящей СОУТ".<br />Информация используется при оформлении протоколов измерений.</span></div>
<div class="block_micro block_left_round block_right_round block_add pointer" id="InfoButtonAdd" onclick="PoupUpMessgeAjax('frame_PoupUp_AjaxSelectGroupSoutData.php?target=<? echo($idGroup); ?>');">Добавить сведения</div>
<?
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, dDateCreate, dDateFinish FROM Arm_groupAcredit WHERE idGroup = '.$idGroup);
if (mysql_num_rows($vResult) > 0)
{
	while($vRow = mysql_fetch_array($vResult))
	{
		echo SuotWork::AddInfoAcrDiv($vRow['id'], $vRow['sName'], $vRow['dDateFinish'], true);
	}
}
?>
<?
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sSertNum, dSertDate, sPost, sReestrNum FROM Arm_groupStuff WHERE idGroup = '.$idGroup.' AND `bExpert` = 0;');
if (mysql_num_rows($vResult) > 0)
{
	while($vRow = mysql_fetch_array($vResult))
	{
		echo SuotWork::AddInfoStuffDiv($vRow['id'], $vRow['sName'], $vRow['sPost'], true);
	}
}
?>
<?
$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), 'SELECT id, sName, sReestrNum, dCheckDate, sCheckNum FROM Arm_groupDevices WHERE idGroup = '.$idGroup);
if (mysql_num_rows($vResult) > 0)
{
	while($vRow = mysql_fetch_array($vResult))
	{
		echo SuotWork::AddInfoDevDiv($vRow['id'], $vRow['sName'], $vRow['sReestrNum'], $vRow['sCheckNum'], $vRow['dCheckDate'], true);
	}
}
?>
</div></div>
<div id="Check_Bl_5" class="rollBody" style="display:none;">

<table width="715" border="0" cellspacing="0" cellpadding="0">
  <tr class="blockmargin">
    <td width="238" align="left" valign="middle" class="comment">Номер заключения эксперта</td>
    <td width="30">&nbsp;</td>
    <td><input name="sExpEndDoc" type="text" class="input_field_micro input_field_background input_field_445" id="sExpEndDoc" value="<?php echo $aGroup['sExpEndDoc']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Дата заключения эксперта</td>
    <td>&nbsp;</td>
    <td><input name="sExpEndDate" type="text" class="input_field_micro input_field_background input_field_445" id="sExpEndDate" value="<?php echo $aGroup['sExpEndDate']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Наименование документа</td>
    <td>&nbsp;</td>
    <td><input name="sDocName" type="text" class="input_field_micro input_field_background input_field_445" id="sDocName" value="<?php echo $aGroup['sDocName']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Дата начала проведения специальной оценки условий труда</td>
    <td>&nbsp;</td>
    <td><input name="dStartDate" type="text" class="input_field_micro input_field_background input_field_445" id="dStartDate" value="<?php echo $aGroup['dStartDate']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">Дата окончания проведения специальной оценки условий труда</td>
    <td>&nbsp;</td>
    <td><input name="dEndDate" type="text" class="input_field_micro input_field_background input_field_445" id="dEndDate" value="<?php echo $aGroup['dEndDate']; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
  </tr>
  <tr class="blockmargin">
    <td align="left" valign="middle" class="comment">&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>

<? if ($aGroup['idParent'] == UserControl::GetUserLoginId())
{
echo('<table width="715" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td valign="top"><div class="button button_archive shawdow_min"></div></td><td><div class="button_text button_active shawdow_min" title="Скрыть группу данных из рабочего пространства" onclick="SetArchiveClick();">Направить группу данных в архив<br /><span class="comment">Скрыть группу данных из рабочего пространства</span></div></td></tr><tr><td height="35" valign="top">&nbsp;</td><td height="35">&nbsp;</td></tr><tr><td width="96" valign="top"><div class="button button_recycle shawdow_min"></div></td><td><div class="button_text button_active shawdow_min" title="Безвозвратно удалить группу данных" onclick="SetDeleteClick();">Удалить группу данных<br /><span class="comment">Безвозвратно удалить группу данных</span></div></td></tr></table>');
}
else
{
	echo('
	<table width="715" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td valign="top"><div class="button button_archive shawdow_min"></div></td><td><div class="button_text button_active shawdow_min" title="Скрыть группу данных из рабочего пространства" onclick="SetArchiveClick();">Направить группу данных в архив<br /><span class="comment">Скрыть группу данных из рабочего пространства</span></div></td></tr><tr><td height="35" valign="top">&nbsp;</td><td height="35">&nbsp;</td></tr></table>
	<div class="nowBlock" id="AddonFunctions">Эта группа данных находится в рабочем пространстве другого пользователя, и только он может удалить её.</div>');
}
?>
</div>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="blockmargin">
      <td height="1px" bgcolor="#0099CC"></td>
    </tr>
    <tr class="blockmargin">
      <td>&nbsp;</td>
    </tr>
    <tr class="blockmargin">
      <td><input name="button" type="submit" class="input_button" id="button2" value="Сохранить изменения" onclick="ChangeMessageSave();"/>
        <div id="ChangeMessageDisplay" class="comment" style="display:inline-block;margin-left:20px;"></div></td>
    </tr>
    </table>

</form>
        </td>
      </tr>
      </table>
    </td>
  </tr>
</table>
<?
/*Установка нижнего фрейма*/
include('Frame/frame_Bottom.php');
?>
<script>

//События связанные с ресайзом окна и подготовкой докумнта
$(document).ready(function(e) {
	$(document).tooltip({track: true, show: {effect: "fade",delay: 500}});
	ChangeWorkSpace();
	$('#sExpEndDate').datepicker({
	showOtherMonths: true,
	selectOtherMonths: true
	});
	$('#dStartDate').datepicker({
	showOtherMonths: true,
	selectOtherMonths: true
	});
	$('#dEndDate').datepicker({
	showOtherMonths: true,
	selectOtherMonths: true
	});
});

function ClickDelDevInfoAcr(object)
{
	tobject = $(object);
	var id = tobject.attr('tag');

	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'delInfoAcr', 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				//alert(id+":"+idGroup+":"+data);
				tobject.slideUp();
			}
		});


}

function ClickDelDevInfoStuff(object)
{
	tobject = $(object);
	var id = tobject.attr('tag');

	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'delInfoStuff', 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				//alert(id+":"+idGroup+":"+data);
				tobject.slideUp();
			}
		});

	tobject.slideUp();
}

function ClickDelDevInfoDev(object)
{
	tobject = $(object);
	var id = tobject.attr('tag');

	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'delInfoDevice', 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				//alert(id+":"+idGroup+":"+data);
				tobject.slideUp();
			}
		});

	tobject.slideUp();
}

function AddAcredit(id)
{
		var idGroup = $('#mainHead').attr('tag');

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'addInfoAcr','idGroup':idGroup, 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				//alert(id+":"+idGroup+":"+data);
				var newDiv = $(data);
				newDiv.insertAfter('#InfoButtonAdd');
				newDiv.slideToggle();
			}
		});
}
function AddExpert(id)
{
		var idGroup = $('#mainHead').attr('tag');

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'addInfoStuff','idGroup':idGroup, 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				var newDiv = $(data);
				newDiv.insertAfter('#InfoButtonAdd');
				newDiv.slideToggle();
			}
		});
}
function AddSoutExpert(id)
{
		var idGroup = $('#mainHead').attr('tag');

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'addInfoExpert','idGroup':idGroup, 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				var newDiv = $(data);
				newDiv.insertAfter('#InfoSOUTButtonAdd');
				newDiv.slideToggle();
			}
		});
}
function AddDevice(id)
{
		var idGroup = $('#mainHead').attr('tag');

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'action': 'addInfoDev','idGroup':idGroup, 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				var newDiv = $(data);
				newDiv.insertAfter('#InfoButtonAdd');
				newDiv.slideToggle();
			}
		});
}

function SetArchiveClick()
{
		var idGroup = $('#mainHead').attr('tag');

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'setArchive','idGroup':idGroup},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				window.location='http://arm2009.ru/work_Space.php';
			}
		});
}

function SetDeleteClick()
{
		var idGroup = $('#mainHead').attr('tag');

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'setDelete','idGroup':idGroup},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				window.location='http://arm2009.ru/work_Space.php';
			}
		});
}

function IsFormValidate()
{
		var sErrHeader = 'Недостаточно информации';
		var sErrReport = 'Название предприятия не может быть пустым';

		IsInputValidNotNull('#sName');

		if(bInputValidError)
		{
			SetInputValidFocusOnFirstErrorInput();
//			PoupUpMessge(sErrHeader, sErrReport);
			SetInputValidDefaultParams();
			return false;
		}
		else
		return true;
}

function ClickAddComiss()
{

	var aPoupupFields = [ 'sName', 'sPost'];
	var aPoupupFieldsScribe = [ 'ФИО', 'Должность'];
	var aPoupupFieldsDefoultValue = [ '', ''];
	var s = 'AddComiss($(\'#sName\').val(),$(\'#sPost\').val())';
	PoupUpMessgeCustomField('Добавление члена аттестационной комиссии','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);

//		var object = $('#mainHead')

}


function AddComiss(sName,sPost)
{
//	if (sName != '' && sPost != '')
//	{
		var idGroup = $('#mainHead').attr('tag');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'addComiss','sName':sName, 'sPost':sPost, 'idGroup':idGroup},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				if (data != null)
				{
					newAdd = $(data);
					newAdd.insertAfter('#ComButtonAdd');
					newAdd.slideDown();
				}
			}
		});
//	}
//	else
//	{
//		$('#poupup_layout').stop();
//		alert('Данные введены не верно!');//$$$
//	}
}


function ClickComiss(object)
{
	tobject = $(object);
	var id = tobject.attr('tag');
				$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'readComiss','id':id},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data)
			{
				if (data != null)
				{
					var aPoupupFields = [ 'sName', 'sPost'];
					var aPoupupFieldsScribe = [ 'ФИО', 'Должность'];
					var aPoupupFieldsDefoultValue = [ data[1], data[2]];
					var s = 'EditComiss('+id+',$(\'#sName\').val(),$(\'#sPost\').val())';
					var d = 'DelComiss('+id+')';
					PoupUpMessgeCustomField('Редактирование члена аттестационной комиссии','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue, d);
				}
			}
		});

}

function EditComiss(id, sName, sPost)
{
//	if (sName != '' && sPost != '')
//	{
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'editComiss','id':id, 'sName':sName,'sPost':sPost},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				$('[tag = '+id+']').html(sName+'<br /><span class="comment">'+sPost+'</span>');
			}
		});
//	}
//	else
//	{
//		Сообщение об ошибке
//		$('#poupup_layout').stop();
//		PoupUpMessge("Упс", "Член комиссии без имени или должности, нет боюсь это невозможно.");
//	}
}

function DelComiss(id)
{
			$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'delComiss','id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
			{
				$('[tag = '+id+']').slideUp('fast', function() {$('[tag = '+id+']').remove();});
			}
		});
}

function IsFormValidate()
{
		var sErrHeader = 'Недостаточно информации';
		var sErrReport = 'Название группы данных не может быть пустым';

		IsInputValidNotNull('#sName');

		if(bInputValidError)
		{
			SetInputValidFocusOnFirstErrorInput();
//			PoupUpMessge(sErrHeader, sErrReport);
			SetInputValidDefaultParams();
			return false;
		}
		else
		return true;
}

function ChangeWorkSpace()
{
	$('#InfoButtonAdd').attr('onclick', "PoupUpMessgeAjax('frame_PoupUp_AjaxSelectGroupSoutData.php?org="+$("#select").val()+"');");
}


</script>
</body>
</html>

<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
	UserControl::isUserValidExit();
	if(isset($_GET['archive']) && $_GET['archive'] == 'true')
	$bArchive = true; else $bArchive = false;
?>


<?
	if($_GET[pay]=='1')
	{
		switch ($_GET[result])
		{
			case 0:
				$_POST[sPoupupHeader] = 'Платеж успешно завершен';
				$_POST[sPoupupMessge] = 'Новый тариф будет установлен в ближайшее время.';

			break;

			case 1:
				$_POST[sPoupupHeader] = 'Упс';
				$_POST[sPoupupMessge] = 'Платеж не завершен.';
			break;

			case -1:
			break;
		}
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>

<body><? include('Frame/frame_Top.php'); ?><? include_once('Frame/frame_PoupUp.php'); ?>


<? include_once('Frame/frame_PoupUp.php'); ?>

<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left" class="white"><h1><? if($bArchive) echo('Архив групп данных'); else echo('Мои группы данных');?></h1></td>
    <td align="right" class="white">
      <input type="text" name="GroupFind" id="GroupFind" class="input_field_micro input_field_background input_find" style="width:200px;"/></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">

<? if(date('Y-m-d') < '2016-05-13'): //NOTE: Объявление со сроком установки ?>

			<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="nowBlock" style="margin-top:2em;margin-bottom:0px;">
				<tr>
					<td class="comment" style="text-align:justify">
						Уважаемые эксперты!<br>
Обратите внимание, начиная с версии 0.0.38 изменен порядок расчета оценок по шуму, и добавлен соответствующий протокол: "Протокол оценки шума", включающий расчет неопределенности в соответствии с 1 стратегией "на основе рабочей операции" - ГОСТ Р ИСО 9612-2013 "Национальный стандарт Российской федерации. Акустика. Измерения шума для оценки его воздействия на человека. Метод измерений на рабочих местах", утв. и введен в действие Приказом Росстандарта от 05.12.2013 № 2180-ст.
					</td>
				</tr>
			</table>

<? endif; ?>


      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
            <tr>
              <td><table width="715" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="96" valign="top"><div class="button button_createfolder shawdow_min"></div></td>
                  <td>
								<?
									$tmpActionCreate = "window.location.href = 'work_MainInfo.php?action=add';";
								?>
                <div class="button_text button_active button_hightlight shawdow_min" title="Создать новую группу данных" onclick="<? echo($tmpActionCreate); ?>">Создать новую группу данных СОУТ<br />
                <span class="comment">Создание новой группы данных специальной оценки условий труда</span></div></td>
                </tr>

              <tr class="blockmargin">
                  <td height="35" valign="top">&nbsp;</td>
                  <td height="35">&nbsp;</td>
              </tr>

               <tr>
                    <td width="96" valign="top"><div class="button <? if($bArchive) echo('button_folder '); else echo('button_archive '); ?>shawdow_min"></div></td>
                    <td>
                    <div class="button_text button_active shawdow_min" title="<? if($bArchive) echo('Текущие группы данных'); else echo('Архив'); ?>" onclick="window.location.href = 'work_Space.php?archive=<? if($bArchive) echo('false'); else echo('true'); ?>';"><? if($bArchive) echo('Перейти к текущим группам данных'); else echo('Перейти к архивным группам данных'); ?><br />
                    <span class="comment"><? if($bArchive) echo('Перейти к текущим группам данных'); else echo('Перейти к группам данных направленным в архив'); ?></span></div>
                    </td>
                </tr>

                <tr class="blockmargin">
                  <td height="35" valign="top">&nbsp;</td>
                  <td height="35">&nbsp;</td>
                </tr>
                <tr class="blockmargin">
                  <td height="1" bgcolor="#0099CC"></td>
                  <td height="1" bgcolor="#0099CC"></td>
                </tr>
                <tr class="blockmargin">
                  <td height="35">&nbsp;</td>
                  <td height="35">&nbsp;</td>
                </tr>
              </table>


<?php
if($bArchive)
$aGroups = GroupWork::FillGroupList('archive');
else
$aGroups = GroupWork::FillGroupList();

$selfSpace = 'button button_folder shawdow_min';
$otherSpace = 'button button_sharefolder shawdow_min';
$FirstRow = true;
if (count($aGroups) > 0)
{
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0">';
	foreach ($aGroups as $aGroup)
	{
		if(!$FirstRow){echo'<tr><td>&nbsp;</td><td>&nbsp;</td></tr>';}else{$FirstRow=false;}
		if (intval($aGroup[3]) == intval(UserControl::GetUserLoginId())) { $sSpace = $selfSpace;} else {$sSpace = $otherSpace;}
		echo '<td width="96" valign="top"><div class="'.$sSpace.'" title="'.$aGroup[2].'"></div></td><td><div class="button_text button_active shawdow_min" title="Перейти к работе с группой данных" onclick="window.location.href = \'work_DataGroup.php?id='.$aGroup[0].'\'">'.$aGroup[1].'<br /><span class="comment">'.$aGroup[2].'</span></div></td></tr>';
	}
}
else
{
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="nowBlock"><tr><td>Добро пожаловать в Ваше рабочее пространство!<br /><br />Здесь будут отображаться созданные Вами группы данных, или группы данных других пользователей в процессе совместной работы. Начните свою работу с создания своей первой группы данных.</td></tr></table><table width="715" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
}
?>
              </table></td>
            </tr>
      </table>
    </td>
  </tr>
</table>
<?
/*Установка нижнего фрейма*/
include('Frame/frame_Bottom.php');
?>

<script type="text/javascript">
$(document).ready(function(e) {

	$("#GroupFind").autocomplete({
	source: "aj.datagroup.php",
	minLength: 1,
	delay: 500,
	select: function( event, ui ) {
	window.location.href = 'work_DataGroup.php?id='+ui.item.id;
	return false;}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
	return $( "<li>" )
	.append( "<a class=\"comment\"><span class=\"gray\">" + item.sNameSpace + "</span><br />" + item.value + "</a>" )
	.appendTo( ul );
	};
});
</script>
</body>
</html>

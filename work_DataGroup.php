<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
    $version = '0.0.36';

	UserControl::isUserValidExit();
	if (isset($_GET['id']))
	{
		$idGroup = (int) $_GET['id'];
		if (!GroupWork::IsCanEditGroup($_GET['id']) && 7 != UserControl::GetUserLoginId())
		{
			header ('Location: index.php');
			exit();
		}
		else
		{
			$aGroup = GroupWork::ReadGroupFull($_GET['id']);
			$idGroup = $_GET['id'];
		}
	}
	else
	{
		header ('Location: index.php');
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>АРМ 2009 Специальная оценка условий труда - программное обеспечение для организаций проводящих специальную оценку труда, экспертов и испытательных лабораторий.</title>
<meta name="keywords" content="программное,обеспечение,автоматизация,ПО,специальная,оценка,труда,аттестация,рабочих,мест,испытательная,лаборатория,нормативный,документ,обучение,эксперт,специалист,комиссия">
<meta name="description" content="АРМ 2009 | Специальная оценка труда — программное обеспечение для испытательных лабораторий и организаций проводящих специальную оценку условий труда">
<meta name="robots" content="index, follow">
<meta name="author" content="Консалтинговый центр Труд">
<meta name="copyright" content="Все права принадлежат Консалтинговому центру Труд">
<link href="css_base.css" rel="stylesheet" type="text/css" />
<link href="css_datagroup.css" rel="stylesheet" type="text/css" />
<link href="css/arm2009style/jquery-ui-1.10.4.custom.min.css?v=<? echo('$verion'); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="JS/jquery-ui-1.10.4.custom.min.js"></script>
<script language="javascript" type="text/javascript" src="JS/InputValidate.js?v=<? echo($version); ?>"></script>
<script language="javascript" type="text/javascript" src="JS/ui.js?v=<? echo($version); ?>"></script>
<script language="javascript" type="text/javascript" src="JS/progress.js?v=<? echo($version); ?>"></script>
<script language="javascript" type="text/javascript" src="JS/DataGroup.js?v=<? echo($version); ?>"></script>
<script language="javascript" type="text/javascript" src="JS/ChangeMessage.js?v=<? echo($version); ?>"></script>
</head>

<body>
<div id="ProgressFrame" class="modal_progress"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><img src="Grph/progres/progress.gif"/></td></tr></table></div>
<div id="ProgressFrameHiLevel" class="modal_progress" style="z-index:10000;"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><img src="Grph/progres/progress.gif"/></td></tr></table></div>
<? include_once('Frame/frame_PoupUp.php'); ?>

<div id="caption_frame"><? include('Frame/frame_Top.php'); ?>
	<table width="95%" border="0" align="center" cellpadding="20" cellspacing="0">
		<tr><td align="left" valign="middle" class="white" id="nameHeader" tag="<?php echo $idGroup; ?>"><?php echo $aGroup['sName']; ?></td>
		<td align="right" valign="middle" style="padding-top:25px;">
		<div class="button16b button16b_info shawdow_min" title="Редактирование информации группы данных" onclick="<?php echo 'window.location.href = \'work_MainInfo.php?action=edit&id='.$idGroup.'\''; ?>"></div>
		<div id="poupup_all_layout_base" class="button16b button16b_all shawdow_min" onclick="poupup_all_layout();"></div>
		<div class="button16b button16b_akot shawdow_min" title="Экспорт данных для ФГИС СОУТ" onclick="PoupUpMessgeAjax('Frame/frame_PoupUp_Akot_Export.php?grid=<? echo($idGroup); ?>');"></div>
		<div class="button16b button16b_print shawdow_min" title="Формирование документов" onclick="PoupUpMessgeAjax('Frame/frame_PoupUp_AjaxPrint.php');"></div></td>
		</tr></table></div>

<div id="poupup_all_layout" class="top_poupup_layout shawdow_max">
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0" class="background_w">
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="SetAllCreateDate();">Даты&nbsp;измерений&nbsp;и&nbsp;составления&nbsp;карт</td>
</tr>
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="SetAllNums();">Автоматическая&nbsp;нумерация&nbsp;рабчоих&nbsp;мест</td>
</tr>
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="SetAllCreateAction();">Рекомендации&nbsp;и&nbsp;подбор&nbsp;работников</td>
</tr>
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="progressAll_show();PoupUpMessgeAjax('frame_PoupUp_SizSert.php?idgr=<? echo($idGroup); ?>');">Расстановка&nbsp;сертификатов&nbsp;СИЗ</td>
</tr>
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="SetAllCreateWarranty();">Гарантии&nbsp;и&nbsp;компенсации</td>
</tr>
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="SetAllAsset();">Перерасчет&nbsp;оценок</td>
</tr>
<tr>
<td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="progressAll_show();PoupUpMessgeAjax('frame_PoupUp_ErrCheck.php?idgr=<? echo($idGroup); ?>');">Анализ&nbsp;ошибок</td>
</tr>
</table>
</div>

<div id="MainBlock" style="display:block;width:100%;min-height:300px;margin-bottom:0px;">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="40" valign="top" bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
<div id="rm_control" class="control_panel">
  <div class="button16 button16_folder shawdow_min" title="Добавить подразделение" onclick="ClickAddFolder()"></div>
  <div id="buttonAddRm" class="button16 button16_rm shawdow_min" style="display:none" title="Добавить рабочее место" onclick="ClickAddRm()"></div>
  <div class="button16 button16_delete shawdow_min" title="Удалить" style="float:right; margin-right:0px;" onclick="ClickDel()"></div>
</div>
    </td>
    <td height="40" valign="top" bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
<div id="rm_control_tab" class="control_panel" style="padding:0px;">
  <table width="100%" height="100%" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="20%" align="left" valign="middle" class="comment button_raz" id="rm_control_tab_0" title="Основная данные рабочего места или подразделения" onclick="changeTab(0)">Основные<br />
      данные
</td>
      <td width="20%" align="left" valign="middle" class="comment button_raz" id="rm_control_tab_1" title="Результаты исследования вредных факторов" onclick="changeTab(1)">Исследование<br />факторов</td>
      <td width="20%" align="left" valign="middle" class="comment button_raz" title="Оценка эффективности СИЗ" id="rm_control_tab_2" onclick="changeTab(2)">Эффективность<br />СИЗ</td>
      <td width="20%" align="left" valign="middle" class="comment button_raz" id="rm_control_tab_3" onclick="changeTab(3)">Гарантии и<br />компенсации</td>
      <td width="20%" align="left" valign="middle" class="comment button_raz" id="rm_control_tab_4" onclick="changeTab(4)">Рекомендации и<br />
        подбор работников</td>
    </tr>
  </table>
</div>
	</td>
  </tr>
  <tr>
    <td width="400" valign="top" bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
<div id="rm_navigation" style="display:block;height:100%;overflow:auto;min-width:300px;">

<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0" style="display:none;" id="comment" ><tr><td align="center" valign="middle"><div class="nowBlock comment" >Для начала работы сформируйте<br />
структуру рабочих мест.</div></tr></table>

</div>
    </td>
    <td valign="top" bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
<div id="info_navigation">

<table width="50%" height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><p><img src="Grph/sLogo_bg.png" width="400" height="80" /></p></tr></table>

</div>
	</td>
	</td>
  </tr>
</table>
</div>

<div id="down_frame"><? /*Установка нижнего фрейма*/ include('Frame/frame_Bottom_DataGroup.php'); ?></div>

<script type="text/javascript">
//События связанные с ресайзом окна и подготовкой докумнта
$(document).ready(function(e) {

	openGroup($("#nameHeader").attr('tag'));
	resize_window();
	$(document).tooltip({track: true, show: {
    effect: "fade",
    delay: 500
  }});

});

window.onresize = function(event) {resize_window();};
function resize_window()
{
		$('#MainBlock').css('height', $(window).height()-$("#caption_frame").height()-$("#down_frame").height()-20);
		/*Приведение в порядок прогрессов*/
		progressResize();
}

function poupup_all_layout()
{
	$('#poupup_all_layout').css('left',($('#poupup_all_layout_base').offset().left-$('#poupup_all_layout').width()+$('#poupup_all_layout_base').width()+35)+'px');
	$('#poupup_all_layout').css('top',($('#poupup_all_layout_base').offset().top+$('#poupup_all_layout_base').height()+5)+'px');
	$("#poupup_all_layout").slideToggle();
	$("#poupup_all_layout").mouseleave(function() {$("#poupup_all_layout").slideUp();});
	$("#top_poupup_group_layout").slideUp();
	$("#top_poupup_layout").slideUp();
}

</script>
</body>
</html>

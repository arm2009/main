<?
include_once('UserControl/userControl.php');
include_once('UserControl/userTariff.php');
include_once('Util/String.php');
$sWarning = '';
$iDays = (int)UserTariff::GetTariffDays();
$sTariffName = UserTariff::GetUserTariffNameRus();
$sOrgName = UserControl::GetUserFieldValue('sOrgName');
if ($sOrgName == '')
{
	$sOrgName = "Управление&nbsp;данными&nbsp;организации&nbsp;проводящей&nbsp;СОУТ";
}
else
{
	$sOrgName = str_replace(' ', '&nbsp;', $sOrgName);
	$sOrgName = str_replace('\\', '', $sOrgName);
}
/*
if(strripos($_SERVER['REQUEST_URI'],'work_DataGroup.php'))
$addmenu = '<td id="optionsgrpbtn" align="left" class="button_mainmenu button_mainmenu_mini button_mainmenu_setting comment" title="Управление личными данными" onclick="top_poupup_group_layout();">Группа&nbsp;данныx</td>';
*/

if (UserControl::isUserValid())
{
echo '<div id="top_frame"><table width="100%" border="0" align="center" cellpadding="10" cellspacing="0" class="background_w"><tr><td align="left" class="button_mainmenu button_mainmenu_link comment" title="Переход на главную страницу" onclick="window.location.href = \'work_Space.php\';">Мои группы данных</td>'.$addmenu.'<td id="optionsbtn" align="left" class="button_mainmenu button_mainmenu_mini button_mainmenu_setting comment" onclick="top_poupup_layout();">Управление&nbsp;личными&nbsp;данными</td><td align="right" class="button_mainmenu button_mainmenu_mini button_mainmenu_out comment" title="Выход" onclick="window.location.href = \'index.php?do=loguot\';">Выход</td></tr></table></div>';
//Определение типа браузера
if(!stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome')){echo('<div class="global_message global_message_error" onclick="window.open(\'https://www.google.ru/intl/ru/chrome/browser/\', \'_blank\');window.focus();">Упс, используемый Вами браузер может работать с АРМ 2009 не совсем корректно,<br />для удобной и безопасной работы мы рекомендуем использовать Google Chrome<br /><span class="comment">для перехода на страницу установки щелкнете здесь</span></div>');}
//Сообщение о завершении тарифа
if (UserTariff::GetTariffName() != 'Base')
{
	if ( $iDays < 11)
	{
		$sDays = StringWork::Days($iDays);
		if($iDays > 0)
		echo('<div class="global_message global_message_error" onclick="window.location.href = \'user_TariffInfo.php\';" title="Переход на страницу выбора тарифа">Упс, используемый Вами тариф заканчивает свой срок действия через '.$sDays.'.</div>');
		if($iDays == 0)
		echo('<div class="global_message global_message_error" onclick="window.location.href = \'user_TariffInfo.php\';" title="Переход на страницу выбора тарифа">Упс, используемый Вами тариф заканчивает своё действие сегодня.</div>');
		if($iDays < 0)
		UserTariff::CheckTariffEnd();
	}
}

}
?>
<script>
$(document).ready(function(e) {
	$('#top_frame').tooltip({track: true, show: {
    effect: "fade",
    delay: 500
  }});

});
function top_poupup_layout()
{
	$('#top_poupup_layout').css('left',($('#optionsbtn').offset().left-$('#top_poupup_layout').width()+$('#optionsbtn').width()+91)+'px');
	$('#top_poupup_layout').css('top',($('#optionsbtn').offset().top+$('#optionsbtn').height()+20)+'px');
	$("#top_poupup_layout").slideToggle();
	$("#top_poupup_layout").mouseleave(function() {$("#top_poupup_layout").slideUp();});
	$("#top_poupup_group_layout").slideUp();
	$("#poupup_all_layout").slideUp();

}
function top_poupup_group_layout()
{
	$('#top_poupup_group_layout').css('left',($('#optionsgrpbtn').offset().left-$('#top_poupup_group_layout').width()+$('#optionsgrpbtn').width()+91)+'px');
	$('#top_poupup_group_layout').css('top',($('#optionsgrpbtn').offset().top+$('#optionsgrpbtn').height()+20)+'px');
	$("#top_poupup_group_layout").slideToggle();
	$("#top_poupup_group_layout").mouseleave(function() {$("#top_poupup_group_layout").slideUp();});
	$("#top_poupup_layout").slideUp();
	$("#poupup_all_layout").slideUp();

}
window.onresize = function(event) {
	if($("#top_poupup_layout").is(":visible"))
	{
		$('#top_poupup_layout').css('left',($('#optionsbtn').offset().left-$('#top_poupup_layout').width()+$('#optionsbtn').width()+91)+'px');
		$('#top_poupup_layout').css('top',($('#optionsbtn').offset().top+$('#optionsbtn').height()+20)+'px');
		$('#top_poupup_group_layout').css('left',($('#optionsgrpbtn').offset().left-$('#top_poupup_group_layout').width()+$('#optionsgrpbtn').width()+91)+'px');
		$('#top_poupup_group_layout').css('top',($('#optionsgrpbtn').offset().top+$('#optionsgrpbtn').height()+20)+'px');
	}
};
</script>
<div id="top_poupup_layout" class="top_poupup_layout shawdow_max">
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0" class="background_w">
    <tr>
        <td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="window.location.href = 'user_SuotOrg.php';">Данные&nbsp;организации&nbsp;проводящей&nbsp;СОУТ</td>
    </tr>
    <tr>
        <td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="window.location.href = 'user_Calendar.php';">Производственный&nbsp;календарь</td>
    </tr>
    <tr>
        <td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="window.location.href = 'user_TariffInfo.php';">Совместная&nbsp;работа</td>
    </tr>
    <tr>
        <td align="left" class="button_mainmenu button_mainmenu_setting comment" onclick="window.location.href = 'user_EditData.php';">Учетная&nbsp;запись</td>
    </tr>
</table>
</div>
<div id="top_poupup_group_layout" class="top_poupup_layout shawdow_max">
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0" class="background_w">
    <tr>
        <td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="window.location.href = 'user_SuotOrg.php';">Информация группы данных</td>
    </tr>
    <tr>
        <td align="left" class="button_mainmenu button_mainmenu_setting comment button_mainmenu_subline" onclick="window.location.href = 'user_TariffInfo.php';">Формирование документов</td>
    </tr>
  </table>
</div>
<noscript><div class="global_message global_message_error" onclick="window.open('http://www.java.com/ru/download/chrome.jsp?locale=ru', '_blank');window.focus();">Упс, в вашем браузере отсутствует очень нужный для АРМ 2009 компонент JavaScript, включите его в настройках браузера или установите<br /><span class="comment">для перехода на страницу установки щелкнете здесь</span></div></noscript>

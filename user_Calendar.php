<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
	include_once "MainWork/WorkCalc.php";
	UserControl::isUserValidExit();
	
	//Задание дат
	//dBegin='+$('#dBeginDate').val()+'&dEnd='+$('#dEndDate').val();
	if(isset($_GET['dBegin']) && isset($_GET['dEnd']))
	{
		$DateEnd = date('Y-m-d 00:00:00', strtotime($_GET['dEnd']));
		$DateBegin = date('Y-m-d 00:00:00', strtotime($_GET['dBegin']));
	}
	else
	{
		$DateText = date('Y_m_d_');
		$DateEnd = date('Y-m-d 00:00:00', strtotime("+1 Months"));
		$DateBegin = date('Y-m-d 00:00:00', strtotime("-1 Months"));
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
    <td align="left" class="white"><h1>Производственный календарь 
<? echo('c '.StringWork::StrToDateFormatLite($DateBegin).' по '.StringWork::StrToDateFormatLite($DateEnd)); ?></h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
            <tr>
              <td><table width="715" border="0" align="center" cellpadding="0" cellspacing="0">               

                <tr>
                    <td width="96" valign="top"><div class="button button_createfolder shawdow_min"></div></td>
                    <td>              
                    <div class="button_text button_active button_hightlight shawdow_min" title="Новое событие" onclick="addEvent();">Новое событие<br />
                    <span class="comment">Новое событие</span></div>                
                    </td>
                </tr>
                
                <tr class="blockmargin">
                <td height="35" valign="top">&nbsp;</td>
                <td height="35">&nbsp;</td>
                </tr>
                
                <tr>
                    <td width="96" valign="top"><div class="button button_find shawdow_min"></div></td>
                    <td>              
                    <div class="button_text button_active shawdow_min" title="Диапазон дат" onclick="DiapDate();">Диапазон дат<br />
                    <span class="comment">Диапазон дат</span></div>                
                    </td>
                </tr>

                
                <tr class="blockmargin">
                <td height="35" valign="top">&nbsp;</td>
                <td height="35">&nbsp;</td>
                </tr>
                <tr>
                    <td width="96" valign="top"><div class="button button_print shawdow_min"></div></td>
                    <td>              
                    <div class="button_text button_active shawdow_min" title="Сформировать журнал событий" onclick="printDoc();">Сформировать журнал событий<br />
                    <span class="comment">Сформировать журнал событий</span></div>                
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
$aArray = GroupWork::FillWorkSpace();

foreach($aArray as $aArr)
{
	$aArrayIds[] = $aArr[0];				
}
$idWorkSpaces = implode(',',$aArrayIds);

$aGroups = WorkCalc::Get_Event_List($DateBegin, $DateEnd, $idWorkSpaces);

$selfSpace = 'button button_folder shawdow_min';
$otherSpace = 'button button_sharefolder shawdow_min';

$FirstRow = true;
if (mysql_num_rows($aGroups) > 0)
{
	echo '<table width="100%" border="0" cellpadding="10" cellspacing="0"><tr class="table_header">
		<td width="96" class="comment">Дата</td>
		<td class="comment" colspan="2">Событие</td></tr>
';
	while($row = mysql_fetch_array($aGroups))
	{
		if($rowStyle == 'table_odd') $rowStyle = 'table_even'; else $rowStyle = 'table_odd';
		echo('
		<tr class="'.$rowStyle.'">
		<td width="96" class="comment" valign="top">');
		
		if($row['dDateStart'] == $row['dDateEnd'])
		echo(StringWork::StrToDateFormatLite($row['dDateStart']).'<br />');
		else
		echo('c '.StringWork::StrToDateFormatLite($row['dDateStart']).'<br />по '.StringWork::StrToDateFormatLite($row['dDateEnd']).'<br />');
		echo('</td>
		<td valign="top">
		<strong>'.$row['sName'].'</strong><br />'. $row['sInfo'].'
		</td>
		<td>
			<div class="button8 button8_remove" title="Редактировать источник" onclick="DeletePoint('.$row['id'].')"></div><br />
			<div class="button8 button8_edit" title="Редактировать источник" onclick="EditPoint('.$row['id'].')"></div>
		</td>
		');
		
//		echo($row[0].' / '. $row['idParent'].' / '. $row['idWorkGroup'].' / '. $row['sName'].' / '. $row['sInfo'].' / '. $row['sSerial'].' / '. $row['dDateStart'].' / '. $row['dDateEnd'].'<br />');
		echo('</tr>');
	}
}
else
{
	echo '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="nowBlock"><tr><td>Добро пожаловать в Ваш рабочий журнал!<br /><br />Здесь будут отображаться события вашей организации, или организаций других пользователей в процессе совместной работы. Начните свою работу с создания своего первого события.</td></tr></table><table width="715" border="0" align="center" cellpadding="0" cellspacing="0"><tr>';
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

});
function DiapDate() {
	var aPoupupFields = ['dBeginDate', 'dEndDate'];
	var aPoupupFieldsScribe = ['C','По'];
	var aPoupupFieldsDefoultValue = [<? echo("'".StringWork::StrToDateFormatLite($DateBegin)."','".StringWork::StrToDateFormatLite($DateEnd))."'"; ?>];
	var s = 'DiapDateSet()';
	PoupUpMessgeCustomField('Диапазон дат:','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}
function DiapDateSet ()
{
	window.location.href = 'user_Calendar.php?dBegin='+$('#dBeginDate').val()+'&dEnd='+$('#dEndDate').val();
}
function addEvent ()
{
	var aPoupupFields = ['sTitle','tEvent','dBeginDate', 'dEndDate'];
	var aPoupupFieldsScribe = ['Заголовок','Описание','Дата начала','Дата окончания'];
	var aPoupupFieldsDefoultValue = ['','',<? echo("'".StringWork::StrToDateFormatLite(date('Y-m-d 00:00:00', strtotime("now")))."','".StringWork::StrToDateFormatLite(date('Y-m-d 00:00:00', strtotime("now"))))."'"; ?>];
	var s = 'addEventGo()';
	PoupUpMessgeCustomField('Новое событие:','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}
function addEventGo ()
{
	var idWorkGroup = -1;
	var sName = $('#sTitle').val();
	var sInfo = $('#tEvent').val();
	var sSerial = '';
	var dDateStart = $('#dBeginDate').val();
	var dDateEnd = $('#dEndDate').val();
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'ajax.php',//url адрес файла обработчика
	//$_POST['idWorkGroup'],$_POST['sName'],$_POST['sInfo'],$_POST['sSerial'],$_POST['dDateStart'],$_POST['dDateEnd']
	data:{'action': 'add_event', 'idWorkGroup':idWorkGroup, 'sName':sName, 'sInfo':sInfo, 'sSerial':sSerial, 'dDateStart':dDateStart, 'dDateEnd':dDateEnd},//параметры запроса
	response:'text',
	success:function (data) 
	{ 
		location.reload();
	},
		error: function (xhr, ajaxOptions, thrownError) {
		alert(xhr.responseText);
		location.reload();
	}
	});
}
function DeletePoint(inId)
{
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'ajax.php',//url адрес файла обработчика
	data:{'action': 'Remove_Event', 'idEvent':inId},//параметры запроса
	response:'text',
	success:function (data) 
	{ 
		location.reload();
	},
		error: function (xhr, ajaxOptions, thrownError) {
		alert(xhr.responseText);
		location.reload();
	}
	});
}
function EditPoint(inId)
{
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'ajax.php',//url адрес файла обработчика
	data:{'action': 'get_event', 'idEvent':inId},//параметры запроса
	dataType: 'json',
	response:'text',
	success:function (data) 
	{ 
		if (data != null)
		{
			jQuery.each(data, function(key, value) 
			{
				var aPoupupFields = ['sTitle','tEvent','dBeginDate', 'dEndDate'];
				var aPoupupFieldsScribe = ['Заголовок','Описание','Дата начала','Дата окончания'];
				var aPoupupFieldsDefoultValue = [this[3],this[4],this[6],this[7]];
				var s = 'updateEventGo('+inId+')';
				PoupUpMessgeCustomField('Новое событие:','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
			});
		}
	},
		error: function (xhr, ajaxOptions, thrownError) {
		alert(xhr.responseText);
		location.reload();
	}
	});
}
function updateEventGo (inId)
{
	var idWorkGroup = -1;
	var sName = $('#sTitle').val();
	var sInfo = $('#tEvent').val();
	var sSerial = '';
	var dDateStart = $('#dBeginDate').val();
	var dDateEnd = $('#dEndDate').val();
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'ajax.php',//url адрес файла обработчика
	data:{'action': 'edit_event', 'id':inId,'idWorkGroup':idWorkGroup, 'sName':sName, 'sInfo':sInfo, 'sSerial':sSerial, 'dDateStart':dDateStart, 'dDateEnd':dDateEnd},//параметры запроса
	response:'text',
	success:function (data) 
	{ 
		location.reload();
	},
		error: function (xhr, ajaxOptions, thrownError) {
		alert(xhr.responseText);
		location.reload();
	}
	});
}
function printDoc()
{
	window.open('aj.CreateCalendar.php?dBegin=<? echo(urlencode($DateBegin)); ?>&dEnd=<? echo(urlencode($DateEnd)); ?>', '_blank');
	//window.location.href = 'aj.CreateCalendar.php?dBegin=<? echo(urlencode($DateBegin)); ?>&dEnd=<? echo(urlencode($DateEnd)); ?>';
}
</script>
</body>
</html>
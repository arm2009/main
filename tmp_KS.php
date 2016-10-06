<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
	
	
	//SELECT `Nd_ok01694`.`id`, `Nd_ok01694`.`sCode`, `Nd_ok01694`.`sName`, `Nd_Etks`.`sName`, `Nd_Link_Ok01694_Etks`.`sRazdel` FROM `Nd_ok01694`, `Nd_Link_Ok01694_Etks`, `Nd_Etks` WHERE `Nd_ok01694`.`sCode`= `Nd_Link_Ok01694_Etks`.`idOk01694` AND `Nd_Etks`.`iCode`= `Nd_Link_Ok01694_Etks`.`idEtks` AND `Nd_ok01694`.`sCode` = 10003
	
	//Обработка результата
	//Слабак попался
	if(isset($_GET[cancel]))
	{
	}
	
	//Сильный тип принял решение
	if(isset($_GET[ok]))
	{
		//Отметка в базу
		$sql = "UPDATE `kctrud_arm2009`.`tmp_Etks_Razd` SET `iEnd` = '".UserControl::GetUserLoginId()."' WHERE `tmp_Etks_Razd`.`id` = ".$_GET[num].";";
		DbConnect::GetSqlQuery($sql);
		
		//Получение новых значений
		$sql = "SELECT `tmp_Etks_Razd`.* FROM `tmp_Etks_Razd` WHERE `tmp_Etks_Razd`.`id` = ".$_GET[num].";";
		$result = DbConnect::GetSqlQuery($sql);
		if (mysql_num_rows($result) > 0)
		{		
			while($vRow = mysql_fetch_array($result))
			{
				$tmpRow = $vRow;
			}
		}
		
		//Перебор кодов ОК
		$arrOk = explode('_', $_GET[ok]);
		foreach ($arrOk as $value)
		{
			$sql = "INSERT INTO `kctrud_arm2009`.`Nd_Link_Ok01694_Etks` (`id`, `idOk01694`, `idEtks`, `sDolgnName`, `sRazdel`) VALUES (NULL, '".$value."', '".$tmpRow[sKS]."', '".$tmpRow[sDolgnName]."', '".$tmpRow[sRazdel]."');";
			DbConnect::GetSqlQuery($sql);
		}
	}	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include('Frame/header_all.php'); ?>
<style type="text/css">
#apDiv1 {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 1;
	background-color:#FFF;
	background-position:center;
	background-repeat:no-repeat;
	background-image:url(Grph/bkg/workers.jpg);
}

.structure_workers{display:block;padding:25px;background-position:left;text-align:left;border-left:#0099CC 3px solid;font-size:18px;margin-top:25px;background-image:url(Grph/user/user16l.png);background-image:url(Grph/bkg/pattern_texture_b.jpg);color:#FFF;}
.structure_workers_inside{display:block;padding:25px;background-repeat:no-repeat;background-position:left;text-align:left;text-align:justify;border:#0099CC 1px solid;}
</style>
</head>
<body>
<? include('Frame/frame_Top.php'); ?>
<? include_once('Frame/frame_PoupUp.php'); ?>
<div id="ProgressFrame" class="modal_progress"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><img src="Grph/progres/progress.gif"/></td></tr></table></div>
<div id="ProgressFrameHiLevel" class="modal_progress" style="z-index:10000;"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><img src="Grph/progres/progress.gif"/></td></tr></table></div>
<div id="apDiv1" class="shawdow_max"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:800px;min-height:600px;">
  <tr>
    <td align="center" valign="middle">

<div style="background-image:url(Grph/bkg/pattern_texture_w.jpg); padding:50px;" class="shawdow_max" id="mainform">
  <table width="75%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="48%" valign="top">

<?
	//LoadNew
	$sql = "SELECT `tmp_Etks_Razd`.`id`, `tmp_Etks_Razd`.`sDolgnName`, `tmp_Etks_Razd`.`sRazdel`, `Nd_Etks`.`sName` FROM `tmp_Etks_Razd`, `Nd_Etks` WHERE `tmp_Etks_Razd`.`iBegin` = 0 AND `tmp_Etks_Razd`.`sKS` = `Nd_Etks`.`iCode` ORDER BY `tmp_Etks_Razd`.`sKS` DESC LIMIT 0, 1;";
	$result = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($result) > 0)
	{		
		while($vRow = mysql_fetch_array($result))
		{
			$qRow = $vRow;			
			$sql = "UPDATE `kctrud_arm2009`.`tmp_Etks_Razd` SET `iBegin` = '1' WHERE `tmp_Etks_Razd`.`id` = ".$vRow[id].";";
			DbConnect::GetSqlQuery($sql);
		}
	}
	else
	{
		echo('Чувак, мы сделали это они закончились!');
	}
?>

      
      <p><span class="comment gray">Название должности или профессии:</span><br /><? echo($qRow[sDolgnName]); ?></p>
      <p><span class="comment gray">Раздел справочника:</span><br /><? echo($qRow[sRazdel]); ?></p>
      <p><span class="comment gray">Справочник:</span><br /><? echo($qRow[sName]); ?></p>
      
        </td>
      <td valign="top" class="v_devide_b">&nbsp;</td>
      <td width="48%" valign="top">
      
<table border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td><table border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<td width="96"><div class="button button_find shawdow_max"></div></td>
<td valign="middle"><div class="comment">Поиск по должности, профессии, коду ОК 016-94<br />
<input name="findOK" type="text" class="input_field_micro input_field_445 input_field_background" id="findOK" value="<? if(isset($qRow[sDolgnName])){echo($qRow[sDolgnName]);} ?>" onkeypress="return runScript(event)"/>
</div></td>
</tr>
</table></td>
</tr>
</table>

<div id="findResult" style="margin-top:25px;">
<div id="Result">
</div></div>
<div id="sendResult" style="text-align:center;"><input name="button2" type="submit" class="input_button" id="button" value="Вот оно!" onClick="LetsGo()"/> <input name="button2" type="submit" class="input_button" id="button" value="Хер знает... =(" onClick="LetsFuck()"/></div>
      
      </td>
    </tr>
  </table>
  
  <table width="715" border="0" cellspacing="0" cellpadding="0" style="margin-top:50px;border-top:1px solid #0099CC;">
  <tr>
    <td>Личный зачет: <table width="100%" border="0" cellspacing="0" cellpadding="10">
<?
	$sql = "SELECT `iEnd` FROM `tmp_Etks_Razd` WHERE `iEnd` <> 0 AND `iBegin` <> 0 AND `iEnd` <> -1 ORDER BY `iEnd`;";
	$result = DbConnect::GetSqlQuery($sql);
	$total = mysql_num_rows($result);

	$sql = "SELECT DISTINCT (iEnd) FROM `tmp_Etks_Razd` WHERE `iEnd` <> 0 AND `iBegin` <> 0 AND `iEnd` <> -1 ORDER BY `iEnd`;";
	$result = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($result) > 0)
	{		
		while($vRow = mysql_fetch_array($result))
		{
			if($vRow[iEnd] != -1)
			$username = UserControl::GetUserFieldValueFromId('sName1',$vRow[iEnd]);
			else
			$username = 'ХЗ';
			
			$sql = "SELECT `id` FROM `tmp_Etks_Razd` WHERE `iEnd` = ".$vRow[iEnd].";";
			$resultTMP = DbConnect::GetSqlQuery($sql);
			$resultTMP = mysql_num_rows($resultTMP);
			echo('
			  <tr>
				<td width="100" class="comment">'.$username.' - '.$resultTMP.'</td>
				<td style="background-image:url(Grph/trash/horsebkg.png);"><img src="Grph/trash/horse.png" width="24" height="24" style="margin-left:'.(round($resultTMP / $total* 100)).'%;"/></td>
			  </tr>
			');
		}
	}
?>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="10">
      </table></td>
    <td width="200" align="center" valign="middle"><img src="http://www.e-drinks.net/components/com_jshopping/files/img_products/thumb_Jack_Daniel_s_Bl_4ba6383dcc7ce.jpg" width="70" height="100" /></td>
  </tr>
</table>

</div>

    </td>
  </tr>
</table>
</div>
</body>
</html>
<script>
$(document).ready(function(e) {
	
	//Туллтипы
	$(document).tooltip({track: true, show: {effect: "fade",delay: 500}});

	//Автозаполнение
	$("#findOK").autocomplete({
		source: "aj.ok01694.php",           
		minLength: 3,
		delay: 500,		  
		select: function( event, ui ) {
		$("#findOK").val(ui.item.value);
		GetResult();
		return false;}
	})
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( "<a class=\"comment\" title=\"" + item.etks + "\"><span class=\"gray\">" + item.code + "</span> " + item.value + "</a>" )
		.appendTo( ul );
	};
	
	<? if(isset($qRow[sDolgnName])){echo('GetResult();');} ?>
});
function runScript(e)
{
	if (e.keyCode == 13) {
		GetResult();
        return false;
    }
}
function GetResult()
{
	$('#findResult').slideUp();
	$('#findOK').autocomplete('close');
	progressHi_show('#apDiv1');
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'tmp_aj.KS.php',//url адрес файла обработчика
	data:{'find': $("#findOK").val()},//параметры запроса
	//dataType: 'json',
	response:'text',
	success:function (data) 
	{ 
		$('#Result').html(data);
		$('#findResult').slideDown('slow', function() {
			progressHi_hide();
		});
	}
	});
	
	return true;
}
function LetsGo()
{
	var tmpstring = '';
	$("input:checkbox:checked").each(function(index, element) {
		if(tmpstring.length > 0){tmpstring = tmpstring + '_';}
        tmpstring = tmpstring+$(element).val();
    });
	if(tmpstring.length > 0)
	window.location.href = '?num=<? echo($qRow[id]); ?>&ok='+tmpstring;
	else
	alert('Будь человеком выбери хоть одно, или признайся что тебе это не под силу...');
}
function LetsFuck()
{
	window.location.href = '?num=<? echo($qRow[id]); ?>&cancel=true';
}
</script>
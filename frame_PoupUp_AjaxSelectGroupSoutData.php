<?
	include_once('LowLevel/dbConnect.php');
	
	if(isset($_GET[target]))
	{
		$sql = "SELECT `idParent` FROM `Arm_group` WHERE `id` = ".$_GET[target].";";
		$sSOUTORGid = DbConnect::GetSqlCell($sql);
	}
	if(isset($_GET[org]))
	{
		$sSOUTORGid = $_GET[org];
	}
?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Сведения об организации проводящей СОУТ</td>
      </tr>
      <tr>
        <td><div id="SelectGroupSoutData" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:500px;" class="comment">

<?
	$isEmty = true;
	
	$sql = "SELECT * FROM `Arm_acredit` WHERE `idParent` = ".$sSOUTORGid." ORDER BY `sName`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		$isEmty = false;
		echo('<div id="header_accreditation" onclick="RoollClick(\'accreditation\');" class="rollDown">Аккредитация</div><div id="body_accreditation" style="display:none;margin:10px; margin-left:30px;">');
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label><input type="checkbox" name="accreditation_gr" value="'.$vRow[id].'" id="accreditation_gr_'.$vRow[id].'" tag="'.$vRow[id].'" />'.$vRow[sName].'</label><br />');
		}
		echo('</div>');
	}

	$sql = "SELECT * FROM `Arm_stuff` WHERE `idParent` = ".$sSOUTORGid." ORDER BY `sName`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		$isEmty = false;
		echo('<div id="header_expert" onclick="RoollClick(\'expert\');" class="rollDown">Эксперты и работники</div><div id="body_expert" style="display:none;margin:10px; margin-left:30px;">');
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label><input type="checkbox" name="expert_gr" value="'.$vRow[id].'" id="expert_gr_'.$vRow[id].'" tag="'.$vRow[id].'" />'.$vRow[sName].'</label><br />');
		}
		echo('</div>');
	}
	
	$sql = "SELECT * FROM `Arm_devices` WHERE `idParent` = ".$sSOUTORGid." ORDER BY `sName`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		$isEmty = false;
		$dNowDate = strtotime(date("d.m.Y"));		
		echo('<div id="header_sredstva" onclick="RoollClick(\'sredstva\');" class="rollDown">Средства измерения</div><div id="body_sredstva" style="display:none;margin:10px; margin-left:30px;">');
		while($vRow = mysql_fetch_array($vResult))
		{
			$dNextDate = strtotime($vRow[dCheckDate]);
			if($dNowDate > $dNextDate) $sRedStyle = 'class="red" title="Срок поверки истек '.StringWork::StrToDateFormatFull($vRow[dCheckDate]).'"'; else $sRedStyle = '';
			
			echo('<label '.$sRedStyle.'><input type="checkbox" name="sredstva_gr" value="'.$vRow[id].'" id="sredstva_gr_'.$vRow[id].'" tag="'.$vRow[id].'"/>'.$vRow[sName].'</label><br />');
		}
		echo('</div>');
	}
	
	if($isEmty)
	{
		echo('Информация о аккредитации, экспертах и средствах измерениях организации проводящей СУОТ отсутсвует.<br /><br />Источником данных является информация указанная в разделе "Данные организации проводящей СОУТ".');
	}
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
<td align="right"><div id="PoupUpButton">
<input type="submit" class="input_button" id="buttonOk" value="Выбрать" onclick="factors_ClickOk(); progressInfo_hide(); return PoupUpMessgeClose();"/>
<input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="progressInfo_hide(); return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<script>
//Отображение недостающих факторов
function factors_show_all(sType)
{
	$.ajax({url: "aj.gn1313.php?sType="+sType}).done(function(data, type) {
		$("#body_factors_"+sType).html(data);
	});
}
//Исполнение окна
function factors_ClickOk()
{
	if ($("input:checkbox[name ='accreditation_gr']:checked").length > 0)
	{
		$("input:checkbox[name ='accreditation_gr']:checked").each(function(key, value) 
		{
//			alert($(value).attr('value'));
			AddAcredit($(value).attr('tag'));
		});
	}
	
	if ($("input:checkbox[name ='expert_gr']:checked").length > 0)
	{
		$("input:checkbox[name ='expert_gr']:checked").each(function(key, value) 
		{
//			alert($(value).attr('value'));
			AddExpert($(value).attr('tag'));
		});
	}
	
	if ($("input:checkbox[name ='sredstva_gr']:checked").length > 0)
	{
		$("input:checkbox[name ='sredstva_gr']:checked").each(function(key, value) 
		{
//			alert($(value).attr('value'));
			AddDevice($(value).attr('tag'));
		});
	}
}
</script>
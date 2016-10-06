<?
	include_once('LowLevel/dbConnect.php');
?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Классификатор производственных факторов</td>
      </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:500px;" class="comment">

<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Nd_factors` WHERE `idParent` = 0 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<div id="header_factors_'. $vRow[id].'" onclick="RoollClick(\'factors_'. $vRow[id].'\');" class="rollDown" title="'.$vRow[tScribe].'">'.$vRow[sPP].' '.$vRow[sName].'</div><div id="body_factors_'. $vRow[id].'" style="display:none;margin:10px; margin-left:30px;">');
			if($vRow[sPP] == '1.2.' || $vRow[sPP] == '2.')
			{
				//Химия
				if($vRow[sPP] == '1.2.')
				$sql = "SELECT `id`, `sName` FROM `Nd_gn1313` WHERE `bFirstSelect` > 0 AND `sFeat` LIKE '%Ф%' AND Nd_gn1313.gnversion = '1' ORDER BY `sName`;";
				else
				$sql = "SELECT `id`, `sName` FROM `Nd_gn1313` WHERE `bFirstSelect` > 0 AND `sFeat` NOT LIKE 'Ф' AND Nd_gn1313.gnversion = '1' ORDER BY `sName`;";
				
				$fResult = DbConnect::GetSqlQuery($sql);
				if (mysql_num_rows($fResult) > 0)
				{
					while($fRow = mysql_fetch_array($fResult))
					{
						echo('<label><input type="checkbox" name="factors_gn" value="'.$fRow[id].'" id="factors_gn_'.$fRow[id].'" />'.$fRow[sName].'</label><br />');
					}
				}
				
				echo('<div class="falselink" onclick="factors_show_all(\''.$vRow[id].'\');" style="margin-top:15px;margin-bottom:15px;">Не нашли нужное вещество среди часто используемых? Показать весь справочник...</div>');
			}
			else
			{
				//Физика
				$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Nd_factors` WHERE `idParent` = ".$vRow[id]." ORDER BY `sPP`;";
				$fResult = DbConnect::GetSqlQuery($sql);
				if (mysql_num_rows($fResult) > 0)
				{
					while($fRow = mysql_fetch_array($fResult))
					{
						if(strlen($fRow[tScribe]) > 0){
							if(strpos($fRow[tScribe],"АРМ 2009")>-1){$addonclass = ' class="red"';}else{$addonclass = '';}
						}else{$addonclass = '';}
						$fRow[sPP] = str_replace('999', '', $fRow[sPP]);
						echo('<label title="'.$fRow[tScribe].'"'.$addonclass.'><input type="checkbox" name="factors_class" value="'.$fRow[id].'" id="factors_class_'.$fRow[id].'"/>'.$fRow[sPP].' '.$fRow[sName].'</label><br />');
					}
				}
			}
			echo('</div>');
		}
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

progress_hide();

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
	if ($("input:checkbox[name ='factors_class']:checked").length > 0)
	{
		$("input:checkbox[name ='factors_class']:checked").each(function(key, value) 
		{
//			alert($(value).attr('value'));
			AddFactor(<? echo($_GET[pointid]); ?> ,$(value).attr('value'), 'class');
		});
	}
	
	if ($("input:checkbox[name ='factors_gn']:checked").length > 0)
	{
		$("input:checkbox[name ='factors_gn']:checked").each(function(key, value) 
		{
//			alert($(value).attr('value'));
			AddFactor(<? echo($_GET[pointid]); ?> ,$(value).attr('value'), 'chem');
		});
	}
}
</script>
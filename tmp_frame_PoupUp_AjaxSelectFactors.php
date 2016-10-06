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

<div id="header_factors_CLIM" onclick="RoollClick('factors_CLIM');" class="rollDown">1.1 Микроклимат</div>
<div id="body_factors_CLIM" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 1 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" onclick="" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>
<div id="header_factors_APFD" onclick="RoollClick('factors_APFD');" class="rollDown">1.2 Аэрозоли приемущественно фиброгенного действия</div>
<div id="body_factors_APFD" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName` FROM `Arm_gn1313` WHERE `bFirstSelect` = 1 AND `sFeat` LIKE '%Ф%' ORDER BY `sName`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label><input type="checkbox" name="factors_gn" value="'.$vRow[id].'" id="factors_gn'.$vRow[id].'" />'.$vRow[sName].'</label><br />');
		}
	}
?>
<div class="falselink" onclick="factors_show_all('APFD');">Не нашли нужное вещество среди часто используемых? Показать весь справочник...</div>
</div>
<div id="header_factors_VIBRO" onclick="RoollClick('factors_VIBRO');" class="rollDown">1.3 Виброакустические факторы</div>
<div id="body_factors_VIBRO" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 10 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>
<div id="header_factors_LIGHT" onclick="RoollClick('factors_LIGHT');" class="rollDown">1.4 Световая среда</div>
<div id="body_factors_LIGHT" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 17 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>
<div id="header_factors_IONFREE" onclick="RoollClick('factors_IONFREE');" class="rollDown">1.5 Неионезирующие излучения</div>
<div id="body_factors_IONFREE" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 21 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>
<div id="header_factors_ION" onclick="RoollClick('factors_ION');" class="rollDown">1.6 Ионизирующие излучения</div>
<div id="body_factors_ION" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 28 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>

<div id="header_factors_CHEM" onclick="RoollClick('factors_CHEM');" class="rollDown">2 Химический фактор</div>
<div id="body_factors_CHEM" style="display:none;margin:10px; margin-left:30px;">

<?
	$sql = "SELECT `id`, `sName` FROM `Arm_gn1313` WHERE `bFirstSelect` = 1 AND `sFeat` NOT LIKE '%Ф%' ORDER BY `sName`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label><input type="checkbox" name="factors_gn" value="'.$vRow[id].'" id="factors_gn'.$vRow[id].'" />'.$vRow[sName].'</label><br />');
		}
	}
?>
<div class="falselink" onclick="factors_show_all('CHEM');">Не нашли нужное вещество среди часто используемых? Показать весь справочник...</div>
</div>
<div id="header_factors_BIO" onclick="RoollClick('factors_BIO');" class="rollDown">3 Биологический фактор</div>
<div id="body_factors_BIO" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 33 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>
<div id="header_factors_HARD" onclick="RoollClick('factors_HARD');" class="rollDown">4 Тяжесть трудового процесса</div>
<div id="body_factors_HARD" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 37 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>
<div id="header_factors_PSYCHO" onclick="RoollClick('factors_PSYCHO');" class="rollDown">5 Напряженность трудового процесса</div>
<div id="body_factors_PSYCHO" style="display:none;margin:10px; margin-left:30px;">
<?
	$sql = "SELECT `id`, `sName`, `sPP`, `tScribe` FROM `Arm_factors` WHERE `idParent` = 46 ORDER BY `sPP`;";
	$vResult = DbConnect::GetSqlQuery($sql);
	if (mysql_num_rows($vResult) > 0)
	{
		while($vRow = mysql_fetch_array($vResult))
		{
			echo('<label title="'.$vRow[tScribe].'"><input type="checkbox" name="factors_class" value="'.$vRow[id].'" id="factors_class_'.$vRow[id].'"/>'.$vRow[sPP].' '.$vRow[sName].'</label><br />');
		}
	}
?>
</div>


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
<input type="submit" class="input_button" id="buttonOk" value="Выбрать" onclick="ClickOk(); progressInfo_hide(); return PoupUpMessgeClose();"/>
<input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="progressInfo_hide(); return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<script>


function factors_show_all(sType)
{
//	progressHi_show('#print_PF_div');
//	$("#body_factors_"+sType).slideUp();
	$.ajax({url: "aj.gn1313.php?sType="+sType}).done(function(data, type) {
		$("#body_factors_"+sType).html(data);
//		progressHi_hide();
//		$("#body_factors_"+sType).slideDown();
	});
}

function ClickOk()
{
	
	if ($("input:checkbox[name ='factors_class']:checked").length > 0)
	{
		$("input:checkbox[name ='factors_class']:checked").each(function( key, value ) 
		{
			AddFactor($(value).attr('value'), 'class');
		});
	}
	
	if ($("input:checkbox[name ='factors_gn']:checked").length > 0)
	{
		$("input:checkbox[name ='factors_gn']:checked").each(function( key, value ) 
		{
			AddFactor($(value).attr('value'), 'chem');
		});
	}
}
</script>
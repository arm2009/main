<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Источник данных для импорта</td>
      </tr>
      <tr>
        <td><div id="import_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:500px;" class="comment">

<?
	$aGroups = GroupWork::FillGroupList();
	
if (count($aGroups) > 0)
{
	foreach ($aGroups as $aGroup)
	{
		echo('<div id="header_rm_'.$aGroup[0].'" onclick="RoollClick(\'rm_'.$aGroup[0].'\');" class="rollDown">'.$aGroup[1].'</div><div id="body_rm_'.$aGroup[0].'" style="display:none;margin:10px; margin-left:30px;">');
		
		$sql = "SELECT `id`, `iNumber`, `idGroup`, `sName` FROM `Arm_workplace` WHERE `idGroup` = ".$aGroup[0]." AND `idParent` <> -1 ORDER BY `iNumber`";
		$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
		
		if (mysql_num_rows($vResult) > 0)
		{
			while($vRow = mysql_fetch_array($vResult))
			{
				echo('<label><input type="checkbox" name="rm" value="'.$vRow[id].'" id="rm_'.$vRow[id].'" />'.$vRow[iNumber].'. '.$vRow[sName].'</label><br />');
			}
		}
		
		echo('</div>');
	}
}
else
{
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
$(document).ready(function() {
	if(ilastImportRm > -1){
	$("#rm_"+ilastImportRm).attr('checked', 'checked');
	$("#rm_"+ilastImportRm).parent().parent().show();
	$("#rm_"+ilastImportRm).focus();
	}
});

//Исполнение окна
function factors_ClickOk()
{
	if ($("input:checkbox[name ='rm']:checked").length > 0)
	{
		$("input:checkbox[name ='rm']:checked").each(function(key, value) 
		{
			ilastImportRm = $(value).attr('value');
			<?
			switch($_GET[sType])
			{
				case 'SIZ':
				echo("ImportSiz($(value).attr('value'));");
				break;
				case 'Actions':
				echo("ImportActions($(value).attr('value'));");
				break;
				case 'Warranty':
				echo("ImportWaranty($(value).attr('value'));");
				break;
			}
			?>			
		});
	}
}
</script>
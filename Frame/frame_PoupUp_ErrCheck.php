<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
    $idGroup = (int) $_GET['idgr'];
?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Результат анализа</td>
        </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:300px;" class="comment">

            <?
                $sql = "SELECT Arm_workplace.iNumber FROM Arm_workplace WHERE Arm_workplace.idGroup = $idGroup AND Arm_workplace.sName IS NULL OR Arm_workplace.sName = '' ORDER BY Arm_workplace.iNumber;";
                $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
            
                if (mysql_num_rows($vResult) > 0): ?>
            <div id="header_nameerr" onclick="RoollClick('nameerr');" class="rollDown">Данные рабочих мест</div>
            <div id="body_nameerr" style="display:none;margin:10px; margin-left:30px;" class="log_text">
                <? endif; while($vRow = mysql_fetch_assoc($vResult)): ?>
                
                [<? echo($vRow['iNumber']); ?>] - Отсутствует наименование<br>    
                $iTotalTime = $vRow[fWorkDay];
                
                <? endwhile; if (mysql_num_rows($vResult) > 0): ?>
                </div>
                <? endif; ?>

            
                [123] - Отсутствует наименование<br>
                [123] - Отсутствует код<br>
                
            
            <div id="header_ident" onclick="RoollClick('ident');" class="rollDown">Идентификация факторов</div>
            <div id="body_ident" style="display:none;margin:10px; margin-left:30px;" class="log_text">
                [123] - Отсутсвуют зоны / оборудование материалы<br>
                [123] - Отсутствуют факторы<br>
                [123] - Время привышает продолжительность рабочего дня<br>
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
<td align="right"><input type="submit" class="input_button" id="buttonClose"value="Закрыть" onclick="return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
    include_once "MainWork/WorkFactors.php";
    $idGroup = (int) $_GET['idgr'];

    //Есть ответ
    if(isset($_POST['Sert_Count']))
    {
        for($i = 0; $i < $_POST['Sert_Count']; $i++)
        {
            if(true)//!empty($_POST["Sert_$i"]))
            {
                $sql = "UPDATE Arm_Siz LEFT JOIN Arm_workplace ON Arm_workplace.id = Arm_Siz.rmId SET Arm_Siz.Sert = '".$_POST["Sert_$i"]."' WHERE Arm_workplace.idGroup = $idGroup AND Arm_Siz.SizName = '".htmlspecialchars_decode($_POST["Siz_$i"])."';";
                $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
            }
        }
        exit("[$_POST[Sert_Count]]<br>");
    }
?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Указание сертификатов СИЗ</td>
        </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:300px;" class="comment">
<form method="post" action="frame_PoupUp_SizSert.php" id="FormSizSert">
    <table>
            <?
            //Запрашиваем все данные по сертификатам
            $sql = "SELECT DISTINCT(Arm_Siz.SizName), Arm_Siz.Sert FROM Arm_Siz LEFT JOIN Arm_workplace ON Arm_workplace.id = Arm_Siz.rmId WHERE Arm_workplace.idGroup = $idGroup ORDER BY Arm_Siz.SizName";
            $vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), $sql);
            $iNum = 0;
            while($vRow = mysql_fetch_assoc($vResult)): ?>
        <tr>
            <td class="comment SpecialTableRow">
                <? echo($vRow['SizName']); ?>
            </td>
            <td valign="top" class="SpecialTableRow">
                <input type="text" value="<? echo($vRow['Sert']); ?>" class="input_field input_field_background input_field_micro" id="Sert_<? echo($iNum); ?>" name="Sert_<? echo($iNum); ?>">
                <input type="hidden" value="<? echo(htmlspecialchars($vRow['SizName'])); ?>" id="Siz_<? echo($iNum); ?>" name="Siz_<? echo($iNum); ?>">
            </td>
        </tr>

            <? $iNum++; endwhile; ?>
    </table>
    <input type="hidden" value="<? echo($iNum); ?>" id="Sert_Count" name="Sert_Count">
</form>
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
<td align="right"><input type="submit" class="input_button" id="buttonClose"value="Сохранить" onclick="submitSertificatSubmitForm();"/> <input type="submit" class="input_button" id="buttonClose"value="Закрыть" onclick="return PoupUpMessgeClose();"/></td>
</tr>
</table>
<?
function ErrCheckInsertErr($RmId, $RmNum, $RmName, $RmErrText, $allErrorText)
{
    if(!empty($allErrorText)) $allErrorText .= '<br>';
    $allErrorText .= "<strong>[$RmNum / $RmName]</strong> $RmErrText";
    return $allErrorText;
}
?>
<script>
$(document).ready(function(e) {

    progressAll_hide();

});
function submitSertificatSubmitForm ()
{
    progressAll_show();
    //$("#FormSizSert").ajaxSubmit({url: 'server.php', type: 'post'})
    $.ajax({
       type: "POST",
       url: "frame_PoupUp_SizSert.php?idgr=<? echo($idGroup); ?>",
       data: $("#FormSizSert").serialize(), // serializes the form's elements.
       success: function(data)
       {
           //alert(data); // show response from the php script.
           PoupUpMessgeClose();
           progressAll_hide();
       }
     });
}
</script>

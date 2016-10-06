<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/WorkPlace.php";

    UserControl::isUserValidExit();

	if (isset($_POST['id']))
	{

		$aWorkPlace = WorkPlace::ReadWorkPlace($_POST['id']);
	}
?>
<table width="95%" border="0" cellpadding="20" cellspacing="0">
  <tr>
    <td class="table_odd">
<div class="datablock" style="width:500px;">Название подразделения<br /><input type="text" name="sNumRM" id="sNameRM" class="datablock_input" value="<?php echo $aWorkPlace[3]; ?>"/></div>    
  </tr>
  <tr class="blockmargin">
    <td><input name="button" type="submit" class="input_button" id="button" value="Сохранить изменения"  onClick="ClickSaveRm()"/></td>
  </tr>
</table>
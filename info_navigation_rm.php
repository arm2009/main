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

<table width="100%" border="0" cellspacing="0" cellpadding="20">
<tr>
<td class="table_odd">
    <div class="datablock" style="width:150px;">Номер рабочего места<br /><input type="text" name="sNumRM" id="sNumRM" class="datablock_input" value="<?php echo $aWorkPlace[2]; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></div>
    <div class="datablock" style="width:150px;">аналогичных р.м.<br /><input type="text" name="sNumAnalog" id="sNumAnalog" class="datablock_input" value="<?php echo $aWorkPlace[7]; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" title="Номера рабочих мест через запятую, например: 1А,&nbsp;3А,&nbsp;5А"/></div>
    <div class="datablock" style="width:150px;">Дата составления карты<br /><input type="text" name="sNameDate" id="sNameDate" class="datablock_input" value="<?php echo $aWorkPlace[14]." ".$aWorkPlace[5]; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></div>
    <div class="datablock" style="width:150px;">Смена, ч.<br /><input type="text" name="fWorkDay" id="fWorkDay" class="datablock_input" value="<?php echo str_replace('.', ',', $aWorkPlace[15]); ?>" onchange="ChangeMessageChange();OnChangeWorkDay();" onkeypress="ChangeMessageChange();"/></div>
</td>
</tr>
<tr>
<td class="table_even">
    <div class="datablock" style="width:500px;">Наименование профессии / должности<br /><input type="text" name="sNameRM" id="sNameRM" class="datablock_input" value="<?php echo $aWorkPlace[3]; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></div>
    <div class="datablock" style="width:150px;"><a href="KS.php" target="_blank" title="Открыть справочник ОК 016-94, ЕТКС, КС в новом окне">код по классификатору</a><br /><input type="text" name="sNameRM2" id="sNameRM2" class="datablock_input" value="<?php echo $aWorkPlace[4].$aWorkPlace[5]; ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></div><br />
    <div class="datablock" style="width:684px;">Выпуск ЕТКС, ЕКС<br />
      <textarea name="sETKS" rows="3" class="datablock_input" id="sETKS" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"><?php echo $aWorkPlace[8]; ?></textarea>
    </div></td>
</tr>
<tr>
<td class="table_odd">
    <div class="datablock" style="width:150px;">Работников<br /><input type="text" name="sCount" id="sCount" class="datablock_input" onchange="ChangeMessageChange(); OnChangeNum();" value="<?php echo $aWorkPlace[9]; ?>" onkeypress="ChangeMessageChange();"/></div>
    <div class="datablock" style="width:150px;">женщин<br /><input type="text" name="sCountWoman" id="sCountWoman" class="datablock_input" onchange="ChangeMessageChange(); OnChangeNum();" value="<?php echo $aWorkPlace[10]; ?>" onkeypress="ChangeMessageChange();"/></div>
    <div class="datablock" style="width:150px;">в возрасте до 18 лет<br /><input type="text" name="sCountYouth" id="sCountYouth" class="datablock_input" onchange="ChangeMessageChange(); OnChangeNum();" value="<?php echo $aWorkPlace[11]; ?>" onkeypress="ChangeMessageChange();"/></div>
    <div class="datablock" style="width:150px;">инвалидов<br /><input type="text" name="sCountDisabled" id="sCountDisabled" class="datablock_input" onchange="ChangeMessageChange(); OnChangeNum();" value="<?php echo $aWorkPlace[12]; ?>" onkeypress="ChangeMessageChange();"/></div>
</td>
</tr>
<tr>
<td class="table_even">
	<div class="datablock" style="width:500px;">СНИЛС работников:<br /><textarea name="sSnils" rows="3" class="datablock_input" id="sSnils" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"><?php echo $aWorkPlace[13]; ?></textarea></div>
</td>
</tr>
<tr>
<td>
	<input name="button" type="submit" class="input_button" id="button" value="Сохранить изменения" onClick="ClickSaveRm(); ChangeMessageSave();"/><div id="ChangeMessageDisplay" class="comment" style="display:inline-block;margin-left:20px;"></div></td>
</tr>
</table>

<script>
function OnChangeNum()
{
	var iNum = parseInt($('#sCount').val());
	var iNumWomen = parseInt($('#sCountWoman').val());
	var iNum18 = parseInt($('#sCountYouth').val());
	var iNumDisabled = parseInt($('#sCountDisabled').val());

	if (iNumWomen > iNum) {$('#sCountWoman').val(iNum.toString());}
	if (iNum18 > iNum) {$('#sCountYouth').val(iNum.toString());}
	if (iNumDisabled > iNum) {$('#sCountDisabled').val(iNum.toString());}
	
	
}

function OnChangeWorkDay()
{
	$('#fWorkDay').val($('#fWorkDay').val().replace('.',','));
}
</script>
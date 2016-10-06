<? 
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/WorkPlace.php";
	if(isset($_POST[id]))
	{
		$sql = "SELECT * FROM Arm_workplace WHERE id=".$_POST[id].";";
		$aWorkPlace = DbConnect::GetSqlRow($sql);
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="10">
<tr>
<td class="table_odd"><table width="90%" border="0" cellspacing="0" cellpadding="0">
  <tr>
      <td width="200"><div class="datablock" style="width:100%;">
        Повышенная оплата труда<br />
  <label>
  <input type="radio" name="iCompSurcharge" value="1" id="RadioGroup1_0"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompSurcharge] == 1){echo(' checked="checked"');} ?>/>
    Да</label>
  <br />
  <label>
  <input type="radio" name="iCompSurcharge" value="0" id="RadioGroup1_1"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompSurcharge] == 0){echo(' checked="checked"');} ?>/>
    Нет</label>
  <br />
  </div>
      </td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBaseSurcharge" type="text" class="datablock_input" id="sCompBaseSurcharge" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value="<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBaseSurcharge]); ?>" />
        </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
<label><input type="radio" name="sCompFactSurcharge" value="1" id="RadioGroup2_0"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactSurcharge] == 1){echo(' checked="checked"');} ?>/>Да</label><br />
<label><input type="radio" name="sCompFactSurcharge" value="0" id="RadioGroup2_1"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactSurcharge] == 0){echo(' checked="checked"');} ?>/>Нет</label><br />
      </div></td>
      </tr>
</table>
</td>
</tr>
<tr>
  <td class="table_even"><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200"><div class="datablock" style="width:100%;"><span class="datablock" style="width:100%;">Дополнительный отпуск</span><br />
          <label>
            <input type="radio" name="iCompVacation" value="1" id="RadioGroup1_2" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompVacation] == 1){echo(' checked="checked"');} ?>/>
            Да</label>
          <br />
          <label>
            <input type="radio" name="iCompVacation" value="0" id="RadioGroup1_3" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompVacation] == 0){echo(' checked="checked"');} ?>/>
            Нет</label>
          <br />
      </div></td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBaseVacation" type="text" class="datablock_input" id="sCompBaseVacation" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value="<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBaseVacation]); ?>" />
      </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
        <label>
          <input type="radio" name="sCompFactVacation" value="1" id="RadioGroup2_2"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactVacation] == 1){echo(' checked="checked"');} ?>/>
          Да</label>
        <br />
        <label>
          <input type="radio" name="sCompFactVacation" value="0" id="RadioGroup2_3"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactVacation] == 0){echo(' checked="checked"');} ?>/>
          Нет</label>
        <br />
      </div></td>
      </tr>
  </table></td>
</tr>
<tr>
  <td class="table_odd"><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200"><div class="datablock" style="width:100%;"><span class="datablock" style="width:100%;">Сокращенное рабочее время</span><br />
          <label>
            <input type="radio" name="iCompShortWorkDay" value="1" id="RadioGroup1_4" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompShortWorkDay] == 1){echo(' checked="checked"');} ?>/>
            Да</label>
          <br />
          <label>
            <input type="radio" name="iCompShortWorkDay" value="0" id="RadioGroup1_5" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompShortWorkDay] == 0){echo(' checked="checked"');} ?>/>
            Нет</label>
          <br />
      </div></td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBaseShortWorkDay" type="text" class="datablock_input" id="sCompBaseShortWorkDay" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value="<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBaseShortWorkDay]); ?>" />
      </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
        <label>
          <input type="radio" name="sCompFactShortWorkDay" value="1" id="RadioGroup2_4"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactShortWorkDay] == 1){echo(' checked="checked"');} ?>/>
          Да</label>
        <br />
        <label>
          <input type="radio" name="sCompFactShortWorkDay" value="0" id="RadioGroup2_5"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactShortWorkDay] == 0){echo(' checked="checked"');} ?>/>
          Нет</label>
        <br />
      </div></td>
      </tr>
  </table></td>
</tr>
<tr>
  <td class="table_even"><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200"><div class="datablock" style="width:100%;"> Молоко<br />
          <label>
            <input type="radio" name="iCompMilk" value="1" id="RadioGroup1_6" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompMilk] == 1){echo(' checked="checked"');} ?>/>
            Да</label>
          <br />
          <label>
            <input type="radio" name="iCompMilk" value="0" id="RadioGroup1_7" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompMilk] == 0){echo(' checked="checked"');} ?>/>
            Нет</label>
          <br />
      </div></td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBaseMilk" type="text" class="datablock_input" id="sCompBaseMilk" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value="<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBaseMilk]); ?>" />
      </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
        <label>
          <input type="radio" name="sCompFactMilk" value="1" id="RadioGroup2_6"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactMilk] == 1){echo(' checked="checked"');} ?>/>
          Да</label>
        <br />
        <label>
          <input type="radio" name="sCompFactMilk" value="0" id="RadioGroup2_7"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactMilk] == 0){echo(' checked="checked"');} ?>/>
          Нет</label>
        <br />
      </div></td>
      </tr>
  </table></td>
</tr>
<tr>
  <td class="table_odd"><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200"><div class="datablock" style="width:100%;"> Профилактическое питание<br />
          <label>
            <input type="radio" name="iCompFood" value="1" id="RadioGroup1_8" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompFood] == 1){echo(' checked="checked"');} ?>/>
            Да</label>
          <br />
          <label>
            <input type="radio" name="iCompFood" value="0" id="RadioGroup1_9" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompFood] == 0){echo(' checked="checked"');} ?>/>
            Нет</label>
          <br />
      </div></td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBaseFood" type="text" class="datablock_input" id="sCompBaseFood" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value="<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBaseFood]); ?>" />
      </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
        <label>
          <input type="radio" name="sCompFactFood" value="1" id="RadioGroup2_8"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactFood] == 1){echo(' checked="checked"');} ?>/>
          Да</label>
        <br />
        <label>
          <input type="radio" name="sCompFactFood" value="0" id="RadioGroup2_9"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactFood] == 0){echo(' checked="checked"');} ?>/>
          Нет</label>
        <br />
      </div></td>
      </tr>
  </table></td>
</tr>
<tr>
  <td class="table_even"><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200"><div class="datablock" style="width:100%;"> Досрочная пенсия<br />
          <label>
            <input type="radio" name="iCompPension" value="1" id="RadioGroup1_10" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompPension] == 1){echo(' checked="checked"');} ?>/>
            Да</label>
          <br />
          <label>
            <input type="radio" name="iCompPension" value="0" id="RadioGroup1_11" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompPension] == 0){echo(' checked="checked"');} ?>/>
            Нет</label>
          <br />
      </div></td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBasePension" type="text" class="datablock_input" id="sCompBasePension" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value='<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBasePension]); ?>' />
      </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
        <label>
          <input type="radio" name="sCompFactPension" value="1" id="RadioGroup2_10"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactPension] == 1){echo(' checked="checked"');} ?>/>
          Да</label>
        <br />
        <label>
          <input type="radio" name="sCompFactPension" value="0" id="RadioGroup2_11"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactPension] == 0){echo(' checked="checked"');} ?>/>
          Нет</label>
        <br />
      </div></td>
      </tr>
  </table></td>
</tr>
<tr>
  <td class="table_odd"><table width="90%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="200"><div class="datablock" style="width:100%;"> Медицинские осмотры<br />
          <label>
            <input type="radio" name="iCompPhysical" value="1" id="RadioGroup1_12" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompPhysical] == 1){echo(' checked="checked"');} ?>/>
            Да</label>
          <br />
          <label>
            <input type="radio" name="iCompPhysical" value="0" id="RadioGroup1_13" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iCompPhysical] == 0){echo(' checked="checked"');} ?>/>
            Нет</label>
          <br />
      </div></td>
      <td><div class="datablock" style="min-width:275px; width:90%;">Основание<br />
          <input name="sCompBasePhysical" type="text" class="datablock_input" id="sCompBasePhysical" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" value='<?php echo StringWork::CheckNullStrFull($aWorkPlace[sCompBasePhysical]); ?>' />
      </div></td>
      <td width="200"><div class="datablock" style="width:100%;"> Фактическое наличие<br />
        <label>
          <input type="radio" name="sCompFactPhysical" value="1" id="RadioGroup2_12"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactPhysical] == 1){echo(' checked="checked"');} ?>/>
          Да</label>
        <br />
        <label>
          <input type="radio" name="sCompFactPhysical" value="0" id="RadioGroup2_13"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[sCompFactPhysical] == 0){echo(' checked="checked"');} ?>/>
          Нет</label>
        <br />
      </div></td>
      </tr>
  </table></td>
</tr>
<tr>
  <td>
<input name="button" type="submit" class="input_button" id="button" value="Сохранить изменения" onClick="ClickSaveWarranty(); ChangeMessageSave();"/><input name="button2" type="submit" class="input_button input_button_book" id="button2" value=" " onClick="PoupUpMessgeAjax('frame_PoupUp_AjaxSelectWarranty.php');" title="Справочник"/><input name="button" type="submit" class="input_button input_button_master" id="button" value=" " onclick="FastWarranty();" title="Автоматическое установление доплат, доп. отпуска и сокращенной продолжительности рабочего дня в соответствии с ТК РФ."/><input name="button" type="submit" class="input_button input_button_import" id="button" value=" " onclick="PoupUpMessgeAjax('frame_PoupUp_AjaxImport.php?sType=Warranty');" title="Импорт данных из других рабочих мест..."/>
    <div id="ChangeMessageDisplay" class="comment" style="display:inline-block;margin-left:20px;"></div></td>
</tr>
</table>

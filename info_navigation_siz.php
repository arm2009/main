<? 
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/WorkPlace.php";
	
	UserControl::isUserValidExit();
	
	if(isset($_POST[id]))
	{
		$sql = 'SELECT * FROM Arm_workplace WHERE id='.$_POST[id].';';
		$aWorkPlace = DbConnect::GetSqlRow($sql);
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="20" height="100%">
  <tr>
    <td height="5" class="table_odd"><table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><div class="datablock" style="width:450px;">Основание для выдачи средств индивидуальной защиты (СИЗ)<br />
      <textarea name="sSIZbase" rows="3" class="datablock_input" id="sSIZbase" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"><?php echo $aWorkPlace[sSIZbase]; ?></textarea>
    </div></td>
    <td valign="top"><div class="datablock" style="width:150px;">Дата проведения оценки<br />
      <input type="text" name="dSizDate" id="dSizDate" class="datablock_input" value="<?php echo StringWork::StrToDateFormatLite($aWorkPlace[dSizDate]); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/>
    </div></td>
    </tr>
</table></td>
  </tr>
  <tr>
    <td style="border-bottom:#0099cc solid 1px; border-top:#0099cc solid 1px;">
<div id="siz_navigation" style="display:block;text-align:center;" null="true">
</div>
    </td>
  </tr>
  <tr>
    <td height="5" class="table_odd"><table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="32"><input name="iSIZCard" type="checkbox" id="iSIZCard" value="1"<? if($aWorkPlace[iSIZCard] == 1){echo(' checked="checked"');} ?> onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
        <td style="font-size:14px;">&#8212; наличие заполненной в установленном порядке карточки учета СИЗ</td>
      </tr>
    </table>
      <br />
<div class="datablock" style="width:200px;vertical-align:top;">Оценка по обеспеченности<br />
      <label>
        <input type="radio" name="iSIZOFact" value="1" id="RadioGroup1_10"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOFact] == 1){echo(' checked="checked"');} ?>/>
        Соответствует</label>
      <br />
      <label>
        <input type="radio" name="iSIZOFact" value="0" id="RadioGroup1_11"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOFact] == 0){echo(' checked="checked"');} ?>/>
        Не соответствует</label>
      <br />
  </div>
      <div class="datablock" style="width:200px;"> по защищенности<br />
        <label>
          <input type="radio" name="iSIZOProtect" value="1" id="RadioGroup1_12"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOProtect] == 1){echo(' checked="checked"');} ?>/>
          Защищено</label>
        <br />
        <label>
          <input type="radio" name="iSIZOProtect" value="0" id="RadioGroup1_13"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOProtect] == 0){echo(' checked="checked"');} ?>/>
          Не защищено</label>
        <br />
        <label>
          <input type="radio" name="iSIZOProtect" value="2" id="RadioGroup1_16"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOProtect] == 2){echo(' checked="checked"');} ?>/>
          Не оценивалось</label>
        <br />
    </div>
      <div class="datablock" style="width:200px;"> по эффективности<br />
        <label>
          <input type="radio" name="iSIZOEffect" value="1" id="RadioGroup1_14"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOEffect] == 1){echo(' checked="checked"');} ?>/>
          Эффективны</label>
        <br />
        <label>
          <input type="radio" name="iSIZOEffect" value="0" id="RadioGroup1_15"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOEffect] == 0){echo(' checked="checked"');} ?>/>
          Не эффективны</label>
        <br />
        <label>
          <input type="radio" name="iSIZOEffect" value="2" id="RadioGroup1_17"  onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"<? if($aWorkPlace[iSIZOEffect] == 2){echo(' checked="checked"');} ?>/>
          Не оценивалось</label>
        <br />
    </div></td>
  </tr>
  <tr>
    <td height="5"><input name="button" type="submit" class="input_button input_button_import" id="button" value=" " onclick="PoupUpMessgeAjax('frame_PoupUp_AjaxImport.php?sType=SIZ');" title="Импорт данных из других рабочих мест..."/><input name="button" type="submit" class="input_button" id="button" value="Сохранить изменения" onclick="ClickSaveSIZ(); ChangeMessageSave();"/>
      <div id="ChangeMessageDisplay" class="comment" style="display:inline-block;margin-left:20px;"></div></td>
  </tr>
</table>

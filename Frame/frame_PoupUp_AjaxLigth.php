<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Освещенность рабочей поверхности при искусственном освещении</td>
        </tr>
      <tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:450px;" class="comment">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<input name="" type="text" class="input_field input_field_background" id="" value=""/>
    </td>
    <td>
<input name="" type="text" class="input_field input_field_background" id="" value=""/>
	</td>
  </tr>
</table>


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
<td align="right"><div id="PoupUpButton"><input type="submit" class="input_button" id="buttonOk" value="Сформировать" onclick="StartPrint();"/><input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<script>
function StartPrint()
{
	var bErr = true;
	var sDV = $("input:radio[name ='print_DV']:checked").val();
	if(sDV == 'diap')
	{
		IsInputValidPrintDiapazonString('#sPrintDV');
		if(bInputValidError)
		{
			SetInputValidFocusOnFirstErrorInput();
			SetInputValidDefaultParams();
			bErr = false;
		}
	}
	
	if($("input:checkbox[name ='print_PF']:checked").length == 0)
	{
		$('#print_PF_div').addClass('input_wrong');
		bErr = false;
	}
	else
	{
		$('#print_PF_div').removeClass('input_wrong');
	}
	
	if(bErr)
	{
		
		var sPrint_diap = '';
		if($("input:radio[name ='print_DV']:checked").val() == 'all') sPrint_diap = 'all'; else sPrint_diap = $('#sPrintDV').val();
		var sPrint_doc = '';
		$("input:checkbox[name ='print_PF']:checked").each(function(key, value) 
		{
			sPrint_doc += '_'+$(value).val();
		});
			
		window.open('work_CreateDoc.php?first='+GetGroupId()+'&second='+sPrint_diap+'&third='+sPrint_doc, '_blank');
//		window.focus();
		PoupUpMessgeClose();
	}
}

function AjaxPrintPressReturn(e)
{
	if (e.keyCode == 13) {
		StartPrint();
	}
}
</script>
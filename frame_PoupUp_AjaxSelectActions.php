<?
	include_once('LowLevel/dbConnect.php');

?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table id="main_table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Справочник рекомендаций</td>
      </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:500px;" class="comment">

		<div id="header_main_162" onclick="LoadDiv('main_162');RoollClick('main_162');" class="rollDown" title="ПРАВИТЕЛЬСТВО РОССИЙСКОЙ ФЕДЕРАЦИИ ПОСТАНОВЛЕНИЕ от 25 февраля 2000 года N 162 Об утверждении перечня тяжелых работ и работ с вредными или опасными условиями труда, при выполнении которых запрещается применение труда женщин"><strong>Ограничение труда женщин</strong></div>       
        <div id="body_main_162" style="display:none;margin:10px; margin-left:30px;"></div>
		

		<div id="header_main_163" onclick="LoadDiv('main_163');RoollClick('main_163');" class="rollDown" title="ПРАВИТЕЛЬСТВО РОССИЙСКОЙ ФЕДЕРАЦИИ ПОСТАНОВЛЕНИЕ от 25 февраля 2000 года N 163 Об утверждении перечня тяжелых работ и работ с вредными или опасными условиями труда, при выполнении которых запрещается применение труда лиц моложе восемнадцати лет (с изменениями на 20 июня 2011 года)"><strong>Ограничение труда лиц моложе 18 лет</strong></div>       
	        <div id="body_main_163" style="display:none;margin:10px; margin-left:30px;"></div>
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
<input type="submit" class="input_button" id="buttonOk" value="Выбрать" onclick="Actions_ClickOk(); progressInfo_hide(); return PoupUpMessgeClose();"/>
<input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="progressInfo_hide(); return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<script>

function LoadDiv(object_id)
{
	
	//$('#12').html('<label><input type="checkbox" name="factors_gn" value="12" id="162" />22</label><br />');
	if (object_id == 'main_162')
	{
		
		if ($('#body_main_162').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_actions.php',//url адрес файла обработчика
		data:{'sHeader': '162'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_162').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
	
	if (object_id == 'main_163')
	{
		
		if ($('#body_main_163').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_actions.php',//url адрес файла обработчика
		data:{'sHeader': '163'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_163').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
	

}

function Actions_ClickOk()
{
		if ($("input:checkbox[name ='162']:checked").length > 0)
	{
		$("input:checkbox[name ='162']:checked").each(function(key, value) 
		{
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj_actions.php',//url адрес файла обработчика
			data:{'prikaz': '162', 'punkt': $(value).attr('value')},//параметры запроса
//		dataType: 'json',
			response:'text',
			success:function (data) 
			{
				if (data != null)
				{
					var textAct = 'Применение труда женщин - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 162, ';
					textAct = textAct + data+').';
					AddAction('<? echo($_GET[idRm]); ?>', textAct, '', '', '', 1);
				}
			}
			});
		});
	}
	
	if ($("input:checkbox[name ='163']:checked").length > 0)
	{
		$("input:checkbox[name ='163']:checked").each(function(key, value) 
		{
			
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj_actions.php',//url адрес файла обработчика
			data:{'prikaz': '163', 'punkt': $(value).attr('value')},//параметры запроса
//		dataType: 'json',
			response:'text',
			success:function (data) 
			{
				if (data != null)
				{
					var textAct = 'Применение труда лиц не достигших 18 лет - запрещено (Постановление Правительства Российской Федерации от 25 февраля 2000 года N 163, ';
					textAct = textAct + data+').';
					AddAction('<? echo($_GET[idRm]); ?>', textAct, '', '', '', 1);
				}
			}
			});
		});
	}
}
</script>
<?
	include_once('LowLevel/dbConnect.php');

?>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table id="main_table" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Справочник гарантий и компенсаций</td>
      </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:500px;" class="comment">

		<div id="header_main_ps1" onclick="LoadDiv('main_ps1');RoollClick('main_ps1');" class="rollDown" title="Список N 1 производств, работ, профессий, должностей и показателей на подземных работах, на работах с особо вредными и особо тяжелыми условиями труда, занятость в которых дает право на пенсию по возрасту (по старости) на льготных условиях. КАБИНЕТ МИНИСТРОВ СССР ПОСТАНОВЛЕНИЕ от 26 января 1991 года N 10 Об утверждении Списков производств, работ, профессий, должностей и показателей, дающих право на льготное пенсионное обеспечение"><strong>Льготное пенсионное обеспечение Список № 1</strong></div>       
        <div id="body_main_ps1" style="display:none;margin:10px; margin-left:30px;"></div>
		

		<div id="header_main_ps2" onclick="LoadDiv('main_ps2');RoollClick('main_ps2');" class="rollDown" title="Список N 2 производств, работ, профессий, должностей и показателей на подземных работах, на работах с особо вредными и особо тяжелыми условиями труда, занятость в которых дает право на пенсию по возрасту (по старости) на льготных условиях. КАБИНЕТ МИНИСТРОВ СССР ПОСТАНОВЛЕНИЕ от 26 января 1991 года N 10 Об утверждении Списков производств, работ, профессий, должностей и показателей, дающих право на льготное пенсионное обеспечение"><strong>Льготное пенсионное обеспечение Список № 2</strong></div>       
        <div id="body_main_ps2" style="display:none;margin:10px; margin-left:30px;"></div>
        
        <div id="header_main_psFz" onclick="LoadDiv('main_psFz');RoollClick('main_psFz');" class="rollDown" title="РОССИЙСКАЯ ФЕДЕРАЦИЯ ФЕДЕРАЛЬНЫЙ ЗАКОН О трудовых пенсиях в Российской Федерации (с изменениями на 28 декабря 2013 года)"><strong>Федеральный Закон "О трудовых пенсиях в Российской Федерации"</strong></div>       
        <div id="body_main_psFz" style="display:none;margin:10px; margin-left:30px;"></div>
        
        <div id="header_main_med1" onclick="LoadDiv('main_med1');RoollClick('main_med1');" class="rollDown" title='Приказ Министерства Здравоохранения и Социального развития Российской Федерации от 12 апреля 2011 года N 302н "Об утверждении перечней вредных и (или) опасных производственных факторов и работ, при выполнении которых проводятся предварительные и периодические медицинские осмотры (обследования), и Порядка проведения предварительных и периодических медицинских осмотров (обследований) работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда"'><strong>Приложение №1 к приказу Минздравсоцразвития Российской Федерации от 12 апреля 2011 года N 302н</strong></div>       
        <div id="body_main_med1" style="display:none;margin:10px; margin-left:30px;"></div>
        
        <div id="header_main_med2" onclick="LoadDiv('main_med2');RoollClick('main_med2');" class="rollDown" title='Приказ Министерства Здравоохранения и Социального развития Российской Федерации от 12 апреля 2011 года N 302н "Об утверждении перечней вредных и (или) опасных производственных факторов и работ, при выполнении которых проводятся предварительные и периодические медицинские осмотры (обследования), и Порядка проведения предварительных и периодических медицинских осмотров (обследований) работников, занятых на тяжелых работах и на работах с вредными и (или) опасными условиями труда"'><strong>Приложение №2 к приказу Минздравсоцразвития Российской Федерации от 12 апреля 2011 года N 302н</strong></div>
        <div id="body_main_med2" style="display:none;margin:10px; margin-left:30px;"></div>
          </div>
          
       
          </td>
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
	if (object_id == 'main_ps1')
	{
		
		if ($('#body_main_ps1').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_warranty.php',//url адрес файла обработчика
		data:{'sHeader': 'ps1'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_ps1').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
	
	if (object_id == 'main_ps2')
	{
		
		if ($('#body_main_ps2').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_warranty.php',//url адрес файла обработчика
		data:{'sHeader': 'ps2'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_ps2').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
	
	if (object_id == 'main_psFz')
	{
		
		if ($('#body_main_psFz').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_warranty.php',//url адрес файла обработчика
		data:{'sHeader': 'psFz'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_psFz').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
	
	if (object_id == 'main_med1')
	{
		
		if ($('#body_main_med1').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_warranty.php',//url адрес файла обработчика
		data:{'sHeader': 'med1'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_med1').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
	
	if (object_id == 'main_med2')
	{
		
		if ($('#body_main_med2').html() == '')
		{
			progress_show('#main_table');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_warranty.php',//url адрес файла обработчика
		data:{'sHeader': 'med2'},//параметры запроса
//		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					$('#body_main_med2').html(data);
					progress_hide();
				}
			}
		});
		
		}
	}
}

function Actions_ClickOk()
{
	
	var pens1 = '';
	 
	
	if ($("input:checkbox[name ='pens1']:checked").length > 0)
	{
		$("input:checkbox[name ='pens1']:checked").each(function(key, value) 
		{
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj_warranty.php',//url адрес файла обработчика
			data:{'prikaz': 'pens', 'id': $(value).attr('value')},//параметры запроса
//		dataType: 'json',
			response:'text',
			success:function (data) 
			{
				if (data != null)
				{
					pens1 = data;
					$("#RadioGroup1_10").attr('checked', 'checked');
					$('#sCompBasePension').val(data);
					
				}
			}
			});
		});
	}
	
	if ($("input:checkbox[name ='pensFz']:checked").length > 0)
	{
		$("input:checkbox[name ='pensFz']:checked").each(function(key, value) 
		{
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj_warranty.php',//url адрес файла обработчика
			data:{'prikaz': 'pensFz', 'id': $(value).attr('value')},//параметры запроса
//		dataType: 'json',
			response:'text',
			success:function (data) 
			{
				if (data != null)
				{
					alert(pens1);
					//Проверка, есть ли что-то еще в возвращаемой пенсии или заменить.
					$("#RadioGroup1_10").attr('checked', 'checked');
					if (pens1 == '')
					{
						$('#sCompBasePension').val(data);
					}
					else
					{
						
						$('#sCompBasePension').val(pens1+data);
					}
				}
			}
			});
		});
	}
	
	if ($("input:checkbox[name ='med1']:checked").length > 0 || $("input:checkbox[name ='med2']:checked").length > 0)
	{
		 var idMed1 = '';
		 var idMed2 = '';
		
		$("input:checkbox[name ='med1']:checked").each(function(key, value) 
		{
			idMed1 = idMed1 + $(value).attr('value') + ',';
			//alert('!');
		});
		if (idMed1.length > 0) {idMed1 = idMed1.substr(0, idMed1.length - 1);}
		
		$("input:checkbox[name ='med2']:checked").each(function(key, value) 
		{
			idMed2 = idMed2 + $(value).attr('value') + ',';
		});
		if (idMed2.length > 0) {idMed2 = idMed2.substr(0, idMed2.length - 1);}
		
		$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj_warranty.php',//url адрес файла обработчика
			data:{'prikaz': 'med', 'idMed1': idMed1, 'idMed2': idMed2},//параметры запроса
//		dataType: 'json',
			response:'text',
			success:function (data) 
			{
				if (data != null)
				{
					
					$("#RadioGroup1_12").attr('checked', 'checked');
					$('#sCompBasePhysical').val(data);
				}
			}
			});
		
		//$('#sCompBasePhysical').val(idMed1+'  '+idMed2);
	}
}
</script>
<? 
	//Библиотека уведомлений
	//Вызывает модальный диалог с сообщением и заголовком

	//Устанавливается внутри <body></body>:		include_once('Frame/frame_PoupUp.php');

	//Инициируется:
	//JS:				PoupUpMessge(sPoupupHeader, sPoupupMessge);
	//POST:				$_POST[sPoupupHeader], $_POST[sPoupupMessge];
	//GET:				$_GET[sPoupupHeader], $_GET[sPoupupMessge];
?>
<div id="poupup_layout" style="display:none;"><div id="poupup_message"></div></div>
<script>
var bPoupUpAccept = false;
var sPoupUpReqestString = "";
var sPoupUpdelete = true;

//Дополнительные параметры сохранения
var sPoupUpAddId = "";
var sPoupUpAddOk = "";
var sPoupUpAddEtks = "";

var sDefaultPoupup = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="left"><h2 style="margin-top:0;"><div id="PoupUpHeader">Заголовок</div></h2></td></tr><tr><td align="left"><div id="PoupUpMessage">Сообщение</div></td></tr><tr class="blockmargin"><td>&nbsp;</td></tr><tr class="blockmargin"><td height="1px" bgcolor="#0099CC"></td></tr><tr class="blockmargin"><td>&nbsp;</td></tr><tr><td align="right"><div id="PoupUpButton"><input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/></div></td></tr></table>';

function PoupUpMessge(sPoupupHeader, sPoupupMessge)
{
	$("#poupup_message").html(sDefaultPoupup);
	$("#PoupUpHeader").html(sPoupupHeader);
	$("#PoupUpMessage").html(sPoupupMessge);
	$("#PoupUpButton").html('<input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/>');
	PoupUpMessgeShow();
}
function PoupUpReqest(sPoupupHeader, sPoupupMessge,sFunctionAfterSubmit)
{
	$("#poupup_message").html(sDefaultPoupup);
	$("#PoupUpHeader").html(sPoupupHeader);
	$("#PoupUpMessage").html(sPoupupMessge);
	$("#PoupUpButton").html('<input type="submit" class="input_button" id="buttonOk" value="Да" onclick="bPoupUpAccept = true;sPoupUpReqestString = $(\'#sPoupUpReqestString\').val();eval('+sFunctionAfterSubmit+');return PoupUpMessgeClose();"/><input type="submit" class="input_button" id="buttonClose" value="Нет" onclick="return PoupUpMessgeClose();"/>');
	PoupUpMessgeShow();
}
function PoupUpReqestString(sPoupupHeader, sPoupupMessge, sFunctionAfterSubmit, sDefaultStringValue)
{
	$("#poupup_message").html(sDefaultPoupup);
	$("#PoupUpHeader").html(sPoupupHeader);
	$("#PoupUpMessage").html(sPoupupMessge);
	$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<input name="sPoupUpReqestString" type="text" class="input_field input_field_background input_field_715" style="margin-top:20px;" id="sPoupUpReqestString" value="'+sDefaultStringValue+'" onkeypress="PoupUpMessgePressReturn(event);"/>');
	$("#PoupUpButton").html('<input type="submit" class="input_button" id="buttonOk" value="Ок" onclick="bPoupUpAccept = true;sPoupUpReqestString = $(\'#sPoupUpReqestString\').val();eval('+sFunctionAfterSubmit+');return PoupUpMessgeClose();"/><input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/>');
	PoupUpMessgeShow();
}
function PoupUpMessgeCustomField(sPoupupHeader, sPoupupMessge, sFunctionAfterSubmit, aPoupupFields, aPoupupFieldsScribe, aPoupupFieldsDefoultValue, sFunctionAfterDelete, sAddParams)
{
	$("#poupup_message").html(sDefaultPoupup);
	//Заголовок и тема
	$("#PoupUpHeader").html(sPoupupHeader);
	if(sPoupupMessge.length > 0)$("#PoupUpMessage").html('<div>'+sPoupupMessge+'</div>'); else $("#PoupUpMessage").html('');
	
	//Забой полей
	for (var i = 0; i < aPoupupFields.length; i++)
	{
		if(aPoupupFields[i].indexOf('microclim') > -1 || aPoupupFields[i].indexOf('HeavyRP') > -1 || aPoupupFields[i].indexOf('PointType') > -1 || aPoupupFields[i].indexOf('Light') > -1 || aPoupupFields[i].indexOf('vCategory') > -1 || aPoupupFields[i].indexOf('nCategory') > -1 || aPoupupFields[i].indexOf('DeviceCheck') > -1)
		{
			//Спецполе поверка
			if(aPoupupFields[i].indexOf('dDeviceCheckDate') > -1)
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><input name="'+aPoupupFields[i]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i]+'" value="'+aPoupupFieldsDefoultValue[i]+'" style="width:370px;"/></td><td align="right"><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i+1]+'</span><br /><input name="'+aPoupupFields[i+1]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i+1]+'" value="'+aPoupupFieldsDefoultValue[i+1]+'" style="width:270px;"/></td></tr></table>');
			//Спецполе свет
			if(aPoupupFields[i].indexOf('fFactLight') > -1)
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><input name="'+aPoupupFields[i]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i]+'" value="'+aPoupupFieldsDefoultValue[i]+'" style="width:370px;"/></td><td align="right"><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i+1]+'</span><br /><input name="'+aPoupupFields[i+1]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i+1]+'" value="'+aPoupupFieldsDefoultValue[i+1]+'" style="width:270px;"/></td></tr></table>');
			if(aPoupupFields[i].indexOf('sAddLightPolygon') > -1)
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><input name="'+aPoupupFields[i]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i]+'" value="'+aPoupupFieldsDefoultValue[i]+'" style="width:320px;"/></td><td align="right"><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i+1]+'</span><br /><input name="'+aPoupupFields[i+1]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i+1]+'" value="'+aPoupupFieldsDefoultValue[i+1]+'" style="width:320px;"/></td></tr></table>');
			if(aPoupupFields[i].indexOf('sAddLightDark') > -1)
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><input name="'+aPoupupFields[i]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i]+'" value="'+aPoupupFieldsDefoultValue[i]+'" style="width:320px;"/></td><td align="right"><span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i+1]+'</span><br /><input name="'+aPoupupFields[i+1]+'" type="text" class="input_field input_field_background" id="'+aPoupupFields[i+1]+'" value="'+aPoupupFieldsDefoultValue[i+1]+'" style="width:320px;"/></td></tr></table>');
			//Спецполе микроклимат
			if(aPoupupFields[i].indexOf('microclim') > -1)
			{
				var tmpS0 = '';
				var tmpS1 = '';
				var tmpS2 = '';
				var tmpS3 = '';
				var tmpS4 = '';
				switch (aPoupupFieldsDefoultValue[i])
				{
					case '0':
					tmpS0 = ' selected="selected"';
					break;
					case '1':
					tmpS1 = ' selected="selected"';
					break;
					case '2':
					tmpS2 = ' selected="selected"';
					break;
					case '3':
					tmpS3 = ' selected="selected"';
					break;
					case '4':
					tmpS4 = ' selected="selected"';
					break;
				}
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><select name="'+aPoupupFields[i]+'" id="'+aPoupupFields[i]+'" class="input_field input_field_background input_field_715" style="width:100%;"><option value="0"'+tmpS0+'>Ia - Работы сидя</option><option value="1"'+tmpS1+'>Iб - Работы не только сидя, но и стоя или связанные с ходьбой</option><option value="2"'+tmpS2+'>IIа - Работы с ходьбой и перемещением изделий до 1 кг.</option><option value="3"'+tmpS3+'>IIб - Работы с ходьбой и перемещением изделий до 10 кг.</option><option value="4"'+tmpS4+'>III - Работы с постоянными передвижениями или переноской тяжестей более 10 кг.</option></select><br />');
			}
			//Спецполе тип вибрации
			if(aPoupupFields[i].indexOf('vCategory') > -1)
			{
				var tmpS0 = '';
				var tmpS1 = '';
				var tmpS2 = '';
				var tmpS3 = '';
				var tmpS4 = '';
				switch (aPoupupFieldsDefoultValue[i])
				{
					case '0':
					tmpS0 = ' selected="selected"';
					break;
					case '1':
					tmpS1 = ' selected="selected"';
					break;
					case '2':
					tmpS2 = ' selected="selected"';
					break;
					case '3':
					tmpS3 = ' selected="selected"';
					break;
					case '4':
					tmpS4 = ' selected="selected"';
					break;
				}
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><select name="'+aPoupupFields[i]+'" id="'+aPoupupFields[i]+'" class="input_field input_field_background input_field_715" style="width:100%;"><option value="0"'+tmpS0+'>1 - транспортная вибрация</option><option value="1"'+tmpS1+'>2 - транспортно-технологическая вибрация</option><option value="2"'+tmpS2+'>3 - технологическая вибрация типа "а"</option><option value="3"'+tmpS3+'>3 - технологическая вибрация типа "б"</option><option value="4"'+tmpS4+'>3 - технологическая вибрация типа "в"</option></select><br />');
			}
			//Спецполе тип шума
			if(aPoupupFields[i].indexOf('nCategory') > -1)
			{
				var tmpS0 = '';
				var tmpS1 = '';
				var tmpS2 = '';
				var tmpS3 = '';
				var tmpS4 = '';
				var tmpS5 = '';
				var tmpS6 = '';
				var tmpS7 = '';
				switch (aPoupupFieldsDefoultValue[i])
				{
					case '0':
					tmpS0 = ' selected="selected"';
					break;
					case '1':
					tmpS1 = ' selected="selected"';
					break;
					case '2':
					tmpS2 = ' selected="selected"';
					break;
					case '3':
					tmpS3 = ' selected="selected"';
					break;
					case '4':
					tmpS4 = ' selected="selected"';
					break;
					case '5':
					tmpS5 = ' selected="selected"';
					break;
					case '6':
					tmpS6 = ' selected="selected"';
					break;
					case '7':
					tmpS7 = ' selected="selected"';
					break;
				}
			$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><select name="'+aPoupupFields[i]+'" id="'+aPoupupFields[i]+'" class="input_field input_field_background input_field_715" style="width:100%;"><option value="0"'+tmpS0+'>Широкополосный постоянный</option><option value="1"'+tmpS1+'>Тональный постоянный</option><option value="2"'+tmpS2+'>Широкополосный колеблющийся</option><option value="3"'+tmpS3+'>Широкополосный прерывистый</option><option value="4"'+tmpS4+'>Широкополосный импульсный</option><option value="5"'+tmpS5+'>Тональный колеблющийся</option><option value="6"'+tmpS6+'>Тональный прерывистый</option><option value="7"'+tmpS7+'>Тональный импульсный</option></select><br />');
			}

			//Спецполе Поза
			if(aPoupupFields[i].indexOf('HeavyRP') > -1)
			{
				var tmpS0 = '';
				var tmpS1 = '';
				var tmpS2 = '';
				var tmpS3 = '';
				switch (aPoupupFieldsDefoultValue[i])
				{
					case '0':
					tmpS0 = ' selected="selected"';
					break;
					case '1':
					tmpS1 = ' selected="selected"';
					break;
					case '2':
					tmpS2 = ' selected="selected"';
					break;
					case '3':
					tmpS3 = ' selected="selected"';
					break;
				}
				$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><select name="'+aPoupupFields[i]+'" id="'+aPoupupFields[i]+'" class="input_field input_field_background input_field_715" style="width:100%;"><option value="0"'+tmpS0+'>Свободное удобное</option><option value="1"'+tmpS1+'>Неудобное до 25%, стоя до 60% смены</option><option value="2"'+tmpS2+'>Неудобное до 50%, стоя до 80%, вынужденное до 25%, сидя без перерывов от 60 до 80% смены</option><option value="3"'+tmpS3+'>Неудобное более 50%, стоя более 80%, вынужденное более 25%, сидя без перерывов более 80% смены</option></select><br />');
			}
			//Спецполе Тип точки измерения
			if(aPoupupFields[i].indexOf('PointType') > -1)
			{
				var tmpS0 = '';
				var tmpS1 = '';
				var tmpS2 = '';
				switch (aPoupupFieldsDefoultValue[i])
				{
					case '0':
					tmpS0 = ' selected="selected"';
					break;
					case '1':
					tmpS1 = ' selected="selected"';
					break;
					case '2':
					tmpS2 = ' selected="selected"';
					break;
				}
				$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><select name="'+aPoupupFields[i]+'" id="'+aPoupupFields[i]+'" class="input_field input_field_background input_field_715" style="width:100%;"><option value="0"'+tmpS0+'>Рабочая зона</option><option value="1"'+tmpS1+'>Используемое оборудование</option><option value="2"'+tmpS2+'>Используемые материалы и сырье</option></select><br />');
			}
		}
		else
		{
			switch(aPoupupFields[i][0])
			{
				case 'i':
					if(aPoupupFieldsDefoultValue[i] == 0) tmpC = ''; else tmpC = ' checked="checked"';
					$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<br /><br /><table border="0" cellspacing="0" cellpadding="0"><tr><td width="32"><input name="'+aPoupupFields[i]+'" type="checkbox" id="'+aPoupupFields[i]+'" value="1" '+tmpC+'/></td><td style="font-size:14px;">&#8212; '+aPoupupFieldsScribe[i]+'</td></tr></table>');
				break;
				case 't':
					$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><textarea name="'+aPoupupFields[i]+'" rows="3" class="input_field input_field_background input_field_715" id="'+aPoupupFields[i]+'">'+aPoupupFieldsDefoultValue[i]+'</textarea>');
				break;
				default:
					$("#PoupUpMessage").html($("#PoupUpMessage").html() + '<span class="comment" style="margin-top:20px;">'+aPoupupFieldsScribe[i]+'</span><br /><input name="'+aPoupupFields[i]+'" type="text" class="input_field input_field_background input_field_715" id="'+aPoupupFields[i]+'" value="'+aPoupupFieldsDefoultValue[i]+'"/>');
				break;				
			}
		}
	}
	
	//Добавление феничек по типу
	for (var i = 0; i < aPoupupFields.length; i++)
	{
		//Отклик на нажатие для всех кроме текста
		switch(aPoupupFields[i][0])
		{
			case 't': break;
			default: $("#"+aPoupupFields[i]).attr('onkeypress','PoupUpMessgePressReturn(event)'); break;
		}
		
		//Дата для дат
		if(aPoupupFields[i][0] == 'd')
		{
			$("#"+aPoupupFields[i]).datepicker({
      			showOtherMonths: true,
     			selectOtherMonths: true,
			changeMonth: true,
			changeYear: true
    		});
		}
		
		//Выбор названий точек измерений
		if(aPoupupFields[i][0] == 'p')
		{
			$("#"+aPoupupFields[i]).autocomplete({
				source: "aj.point.php?iGrId="+GetGroupId(),           
				minLength: 1,
				delay: 500});
		}
		
		//Выбор факторов
		if(aPoupupFields[i].indexOf('sFactName') > -1)
		{
			$("#"+aPoupupFields[i]).bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
				$( this ).autocomplete( "instance" ).menu.active ) {
				event.preventDefault();
				}
				})
				.autocomplete({
				source: function( request, response ) {
				$.getJSON( "aj.factors.php", {
				term: extractLast( request.term )
				}, response );
				},
				search: function() {
				// custom minLength
				var term = extractLast( this.value );
				if ( term.length < 2 ) {
				return false;
				}
				},
				focus: function() {
				// prevent value inserted on focus
				return false;
				},
				select: function( event, ui ) {
				var terms = split( this.value );
				// remove the current input
				terms.pop();
				// add the selected item
				terms.push( ui.item.value );
				// add placeholder to get the comma-and-space at the end
				terms.push( "" );
				this.value = terms.join( ", " );
				return false;
				}
				});
		}
		
		//Справочник ОК016-94
		if(aPoupupFields[i].indexOf('OK01694') > -1)
		{
			$("#"+aPoupupFields[i]).autocomplete({
				source: "aj.ok01694.php",           
				minLength: 1,
				delay: 500,		  
				select: function( event, ui ) {
				sPoupUpAddId = ui.item.id;
				sPoupUpAddOk = ui.item.code;
				sPoupUpAddEtks = ui.item.etks;
				$(this).val(ui.item.value);		
				return false;}
			})
				.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li>" )
				.append( "<a class=\"comment\" title=\"" + item.etks + "\"><span class=\"gray\">" + item.code + "</span> " + item.value + "</a>" )
				.appendTo( ul );
			};
		}
	}
	
	//Добавление кнопки удалить
	if(typeof(sFunctionAfterDelete)==='undefined') sFunctionAfterDelete = '';
	if(sFunctionAfterDelete.length > 0)
	{
		$("#PoupUpButton").html('<input type="submit" class="input_button" id="buttonDelete" value="Удалить" onclick="eval('+sFunctionAfterDelete+');return PoupUpMessgeClose();" style="float:left;"/><input type="submit" class="input_button" id="buttonOk" value="Ок" onclick="bPoupUpAccept = true;eval('+sFunctionAfterSubmit+');return PoupUpMessgeClose();"/><input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/>');
	}
	else
	{	
		$("#PoupUpButton").html('<input type="submit" class="input_button" id="buttonOk" value="Ок" onclick="bPoupUpAccept = true;eval('+sFunctionAfterSubmit+');return PoupUpMessgeClose();"/><input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/>');
	}
	
	PoupUpMessgeShow();
}
function split( val ) {
	return val.split( /,\s*/ );
}
function extractLast( term ) {
	return split( term ).pop();
}
function PoupUpMessgeCustomButton(sPoupupHeader, sPoupupMessge, sPoupupButton)
{
	$("#poupup_message").html(sDefaultPoupup);
	$("#PoupUpHeader").html(sPoupupHeader);
	$("#PoupUpMessage").html(sPoupupMessge);
	$("#PoupUpButton").html(sPoupupButton);
	PoupUpMessgeShow();
}
function PoupUpMessgeAjax(sAjaxUrl)
{
	$.ajax({
	url: sAjaxUrl,
	success: function(data) {
		$("#poupup_message").html(data);
		PoupUpMessgeShow();
	}});
}
function PoupUpMessgeShow()
{	
	$('#poupup_layout').stop();
	bPoupUpAccept = false;
	sPoupUpReqestString = "";
	sPoupUpdelete = true;
	sPoupUpAddId = "";
	sPoupUpAddOk = "";
	sPoupUpAddEtks = "";	
	$('#poupup_layout').fadeIn();
	$('#poupup_message').find('input, textarea').filter(':visible:first').focus();
}

function PoupUpMessgePressReturn(e)
{
	if (e.keyCode == 13) {
		$("#buttonOk").click();
	}
}

function PoupUpMessgeClose()
{
	$('#poupup_layout').fadeOut(); 
	return false;
}
<? if(isset($_GET[sPoupupMessge])){echo("$(document).ready(PoupUpMessge('".$_GET[sPoupupHeader]."','".$_GET[sPoupupMessge]."'));");} ?>
<? if(isset($_POST[sPoupupMessge])){echo("$(document).ready(PoupUpMessge('".$_POST[sPoupupHeader]."','".$_POST[sPoupupMessge]."'));");} ?>
</script>
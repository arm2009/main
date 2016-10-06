<?php
	include_once('UserControl/userControl.php');
	include_once('Util/String.php');
	include_once('aj.suot.php');
    UserControl::isUserValidExit();

	if (isset($_POST['sOgrn']) || isset($_POST['sName']) || isset($_POST['sAdres']) || isset($_POST['sPhone']) || isset($_POST['sEmail']) || isset($_POST['sInn']) || isset($_POST['sOrgRegNum']) || isset($_POST['sOrgDate']))
	{	
		UserControl::ChangeUserData('sOrgInn', $_POST['sInn']);
		UserControl::ChangeUserData('sOrgAdress', $_POST['sEmail']);
		UserControl::ChangeUserData('sOrgPhone', $_POST['sPhone']);
		UserControl::ChangeUserData('sOrgPlace', $_POST['sAdres']);
		UserControl::ChangeUserData('sOrgName', $_POST['sName']);
		UserControl::ChangeUserData('sOrgOgrn', $_POST['sOgrn']);
		UserControl::ChangeUserData('sOrgRegNum', $_POST['sRegNum']);
		UserControl::ChangeUserData('sOrgDate', $_POST['dDate']);
		UserControl::ChangeUserData('sFirstFacePost', $_POST['sFirstFacePost']);
		UserControl::ChangeUserData('sFirstFaceName', $_POST['sFirstFaceName']);
		UserControl::ChangeUserData('sSecondFacePost', $_POST['sSecondFacePost']);
		UserControl::ChangeUserData('sSecondFaceName', $_POST['sSecondFaceName']);
		
//		header ('Location: work_Space.php');
//		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>
<body onload="PageInitialization();"><? include('Frame/frame_Top.php'); ?><? include_once('Frame/frame_PoupUp.php'); ?>
<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left"><h1 class="white">Управление данными организации проводящей СОУТ</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td id="check1_hr" height="50" align="center" class="corner_act white" onclick="CheckPress(1);">Основные данные</td>
              <td id="check2_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(2);">Аккредитация</td>
              <td id="check3_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(3);">Кадровый состав</td>
              <td id="check4_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(4);">Средства измерения</td>
            </tr>
            <tr>
              <td id="check1_bd" height="15" class="corner"></td>
              <td id="check2_bd" height="15"></td>
              <td id="check3_bd" height="15"></td>
              <td id="check4_bd" height="15"></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><div id="Check_Bl_1" class="rollBody"><form action="" method="post" onsubmit="return IsFormValidate();">
            <table width="715" border="0" cellspacing="0" cellpadding="0">
              <tr class="blockmargin">
                <td width="238" align="left" valign="middle" class="comment">Полное наименование</td>
                <td width="30">&nbsp;</td>
                <td><label for="textfield8"></label>
                  <input name="sName" type="text" class="input_field_micro input_field_background input_field_445" id="sName" value="<?php echo UserControl::GetUserFieldValue('sOrgName'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();" maxlength="255"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Место нахождения</td>
                <td>&nbsp;</td>
                <td><label for="textfield9"></label>
                  <input name="sAdres" type="text" class="input_field_micro input_field_background input_field_445" id="sAdres" value="<?php echo UserControl::GetUserFieldValue('sOrgPlace'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Контактный телефон</td>
                <td>&nbsp;</td>
                <td><label for="textfield9"></label>
                  <input name="sPhone" type="text" class="input_field_micro input_field_background input_field_445" id="sPhone" value="<?php echo UserControl::GetUserFieldValue('sOrgPhone'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Адрес электронной почты</td>
                <td>&nbsp;</td>
                <td><input name="sEmail" type="text" class="input_field_micro input_field_background input_field_445" id="sEmail" value="<?php echo UserControl::GetUserFieldValue('sOrgAdress'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">ИНН</td>
                <td>&nbsp;</td>
                <td><input name="sInn" type="text" class="input_field_micro input_field_background input_field_445" id="sInn" value="<?php echo UserControl::GetUserFieldValue('sOrgInn'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">ОГРН</td>
                <td>&nbsp;</td>
                <td><input name="sOgrn" type="text" class="input_field_micro input_field_background input_field_445" id="sOgrn" value="<?php echo UserControl::GetUserFieldValue('sOrgOgrn'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Регистрационный номер  в реестре</td>
                <td>&nbsp;</td>
                <td><input name="sRegNum" type="text" class="input_field_micro input_field_background input_field_445" id="sRegNum" value="<?php echo UserControl::GetUserFieldValue('sOrgRegNum'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Дата внесения в реестр</td>
                <td>&nbsp;</td>
                <td><input name="dDate" type="text" class="input_field_micro input_field_background input_field_445" id="dDate" value="<?php echo UserControl::GetUserFieldValue('sOrgDate'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Ф.И.О. Руководителя организации</td>
                <td>&nbsp;</td>
                <td><input name="sFirstFaceName" type="text" class="input_field_micro input_field_background input_field_445" id="sFirstFaceName" value="<?php echo UserControl::GetUserFieldValue('sFirstFaceName'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Должность руководителя организации</td>
                <td>&nbsp;</td>
                <td><input name="sFirstFacePost" type="text" class="input_field_micro input_field_background input_field_445" id="sFirstFacePost" value="<?php echo UserControl::GetUserFieldValue('sFirstFacePost'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Ф.И.О. Руководителя лаборатории</td>
                <td>&nbsp;</td>
                <td><input name="sSecondFaceName" type="text" class="input_field_micro input_field_background input_field_445" id="sSecondFaceName" value="<?php echo UserControl::GetUserFieldValue('sSecondFaceName'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
              <tr class="blockmargin">
                <td align="left" valign="middle" class="comment">Должность руководителя лаборатории</td>
                <td>&nbsp;</td>
                <td><input name="sSecondFacePost" type="text" class="input_field_micro input_field_background input_field_445" id="sSecondFacePost" value="<?php echo UserControl::GetUserFieldValue('sSecondFacePost'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
              </tr>
            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr class="blockmargin">
              <td>&nbsp;</td>
            </tr>
            <tr class="blockmargin">
              <td height="1px" bgcolor="#0099CC"></td>
              </tr>
            <tr class="blockmargin">
              <td>&nbsp;</td>
              </tr>
            <tr class="blockmargin">
              <td><input name="button" type="submit" class="input_button" id="button2" value="Сохранить изменения" onclick="ChangeMessageSave();"/><div id="ChangeMessageDisplay" class="comment" style="display:inline-block;margin-left:20px;"></div></td>
            </tr>
            </table></form></div></td>
        </tr>
        <tr>
          <td>
<div id="Check_Bl_2" class="rollBody" style="display:none;">
<div class="block block_left_round block_right_round block_add pointer" id="AcrButtonAdd" onclick="ClickAddAcredit();">Добавить сведения о аккредитации</div>
<?php echo SuotWork::GetDivsAccredit() ?>
</div></td>
        </tr>
        <tr>
          <td>
<div id="Check_Bl_3" class="rollBody" style="display:none;">          
<div class="block block_left_round block_right_round block_add pointer" id="StuffButtonAdd" onclick="ClickAddStuff()">Добавить сведения о эксперте, работнике</div>
<?php echo SuotWork::GetDivsStuff() ?>
</div></td>
        </tr>
        <tr>
          <td>
<div id="Check_Bl_4" class="rollBody" style="display:none;">
<div class="block block_left_round block_right_round block_add pointer" id="DeviceButtonAdd" onclick="ClickAddDevice()">Добавить сведения о средствах измерения</div>
<?php echo SuotWork::GetDivsDevice() ?>
</div></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<? 
/*Установка нижнего фрейма*/
include('Frame/frame_Bottom.php');
?>
<script>
function IsFormValidate()
{
		var sErrHeader = 'Недостаточно информации';
		var sErrReport = 'Для изменения данных необходимо заполнить все поля формы';
		
		IsInputValidNotNull('#sName');
		IsInputValidNotNull('#sAdres');
		IsInputValidNotNull('#sPhone');
		IsInputValidNotNull('#sInn');
		IsInputValidNotNull('#sOgrn');
		
		IsInputValidNotNull('#sFirstFaceName');
		IsInputValidNotNull('#sFirstFacePost');
		
		IsInputValidEmail('#sEmail');	
		
		if(bInputValidError)
		{
			SetInputValidFocusOnFirstErrorInput();
//			PoupUpMessge(sErrHeader, sErrReport);
			SetInputValidDefaultParams();
			
			return false;
		}
		else
		return true;
}

//Контейнер объектов
var object;

/*Место для скриптов*/
function PageInitialization()
{
	$('#dDate').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true
    });
}

function ClickAddDevice()
{
	var aPoupupFields = [ 'sDeviceName', 'sReestrNum', 'dDeviceCheckDate', 'sDeviceCheckNum', 'sFactoryNum', 'sFactName','sMethodName'];
	var aPoupupFieldsScribe = [ 'Наименование средства измерений', 'Номер в государственном реестре средств измерений', 'Дата окончания срока поверки средства измерений', 'Номер свидетельства о поверке','Заводской номер', 'Измеряемые факторы', 'Применяемая методика измерения / руководство по эксплуатации, при наличии:'];
	var aPoupupFieldsDefoultValue = [ '', '', '', '', '', '', ''];
	var s = 'AddDevice($(\'#sDeviceName\').val(),$(\'#sReestrNum\').val(),$(\'#dDeviceCheckDate\').val(),$(\'#sDeviceCheckNum\').val(),$(\'#sFactoryNum\').val(),$(\'#sFactName\').val(),$(\'#sMethodName\').val())';
	PoupUpMessgeCustomField('Сведения о средстве измерения','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}

function ClickAddStuff()
{
	var aPoupupFields = [ 'sStuffName', 'sStuffPost', 'sStuffSertNum', 'dStuffSertDate', 'sStuffReestrNum'];
	var aPoupupFieldsScribe = [ 'Фамилия, имя, отчество', 'Должность', 'При наличии &#8212; Номер сертификата эксперта', 'При наличии &#8212; Дата выдачи сертификата эксперта', 'При наличии &#8212; Регистрационный номер в реестре экспертов' ];
	var aPoupupFieldsDefoultValue = [ '', '', '', '', '' ];
	var s = 'AddStuff($(\'#sStuffName\').val(),$(\'#sStuffSertNum\').val(),$(\'#dStuffSertDate\').val(),$(\'#sStuffReestrNum\').val(),$(\'#sStuffPost\').val())';
	PoupUpMessgeCustomField('Сведения о эксперте, работнике','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}

function AddStuff(sName, sSertNum, dSertDate, sReestrNum, sStuffPost)
{
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'sStuffName':sName, 'sStuffSertNum':sSertNum,'dStuffSertDate':dSertDate,'sStuffPost':sStuffPost,'sStuffReestrNum':sReestrNum,'action':'addStuff'},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					if (data != 'double')
					{
						newAdd = $(data);
						newAdd.insertAfter('#StuffButtonAdd');
						newAdd.slideDown();
					}
					else
					{
						PoupUpMessgeCustomField('Упс. Такая запись уже существует', 'Проверьте верность указанных данных и повторите попытку', '');
					}
				}
			}
		});
}

function ClickAddAcredit()
{
	var aPoupupFields = [ 'sAcrName', 'dAcrDateCreate', 'dAcrDateFinish'];
	var aPoupupFieldsScribe = [ 'Регистрационный номер аттестата аккредитации испытательной лаборатории (центра)', 'Дата выдачи аттестата аккредитации', 'Дата истечения срока действия аттестата аккредитации' ];
	var aPoupupFieldsDefoultValue = [ '', '', '' ];
	var s = 'AddAcredit($(\'#sAcrName\').val(),$(\'#dAcrDateCreate\').val(),$(\'#dAcrDateFinish\').val())';
	PoupUpMessgeCustomField('Сведения о аккредитации','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}

//Нажатие на кнопку редактирования устройства
function ClickEditDevice(tobject)
{
	var id = tobject.getAttribute('tag');
	object = tobject;
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'id':id,'action':'deviceOpen'},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				var aPoupupFields = [ 'sDeviceName', 'sDeviceReestrNum', 'dDeviceCheckDate', 'sDeviceCheckNum', 'sFactoryNum', 'sFactName', 'sMethodName'];
				var aPoupupFieldsScribe = [ 'Наименование средства измерений', 'Номер в государственном реестре средств измерений', 'Дата окончания срока поверки средства измерений', 'Номер свидетельства о поверке','Заводской номер', 'Измеряемые факторы', 'Применяемая методика измерения / руководство по эксплуатации, при наличии:'];
				var aPoupupFieldsDefoultValue = [data.sName, data.sReestrNum, data.dCheckDate, data.sCheckNum, data.sFactoryNum, data.sFactName, data.sMethodName];
				var s = 'EditDevice($(\'#sDeviceName\').val(),$(\'#sDeviceReestrNum\').val(),$(\'#dDeviceCheckDate\').val(),$(\'#sDeviceCheckNum\').val(),$(\'#sFactoryNum\').val(),$(\'#sFactName\').val(),$(\'#sMethodName\').val())';
				var d = 'DelDevice()';
				PoupUpMessgeCustomField('Сведения о средстве измерения','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue, d);
			}
	});
}

//Нажатие на кнопку редактирования персонала
function ClickEditStuff(tobject)
{
	var id = tobject.getAttribute('tag');
	object = tobject;
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'id':id,'action':'stuffOpen'},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				var aPoupupFields = [ 'sStuffName', 'sStuffPost', 'sStuffSertNum', 'dStuffSertDate', 'sStuffReestrNum'];
				var aPoupupFieldsScribe = [ 'Фамилия, имя, отчество', 'Должность', 'При наличии &#8212; Номер сертификата эксперта', 'При наличии &#8212; Дата выдачи сертификата эксперта', 'При наличии &#8212; Регистрационный номер в реестре экспертов' ];
				var aPoupupFieldsDefoultValue = [ data.sName, data.sPost, data.sSertNum, data.dSertDate, data.sReestrNum];
				var s = 'EditStuff($(\'#sStuffName\').val(),$(\'#sStuffSertNum\').val(),$(\'#dStuffSertDate\').val(),$(\'#sStuffReestrNum\').val(),$(\'#sStuffPost\').val())';
				var d = 'DelStuff()';
				PoupUpMessgeCustomField('Сведения о эксперте, работнике','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue,d);
			}
	});
}

//Нажатие на кнопку редактирования акредитации
function ClickEditAcredit(tobject)
{
	var id = tobject.getAttribute('tag');
	object = tobject;
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'id':id,'action':'arcOpen'},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data) 
			{
				var aPoupupFields = [ 'sAcrName', 'dAcrDateCreate', 'dAcrDateFinish'];
				var aPoupupFieldsScribe = [ 'Регистрационный номер аттестата аккредитации испытательной лаборатории (центра)', 'Дата выдачи аттестата аккредитации', 'Дата истечения срока действия аттестата аккредитации' ];
				var aPoupupFieldsDefoultValue = [ data.sName, data.dDateCreate, data.dDateFinish ];
				var s = 'EditAcredit($(\'#sAcrName\').val(),$(\'#dAcrDateCreate\').val(),$(\'#dAcrDateFinish\').val())';
				var d = 'DelAcredit()';
				PoupUpMessgeCustomField('Сведения о аккредитации','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue,d);
			}
	});
}

function DelStuff()
{
	DelDiv('stuffDel');
}

function DelDevice()
{
	DelDiv('deviceDel');
}

function DelAcredit()
{
	DelDiv('acrDel');
}


function DelDiv(sCommand)
{
			var id = object.getAttribute('tag');
			
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj.suot.php',//url адрес файла обработчика
			data:{'action':sCommand,'id':id},//параметры запроса
			//dataType: 'json',
			response:'text',
			success:function (data) 
				{
						tobject = $(object);
						tobject.slideUp();
				}
			});

}

function EditDevice(sName, sReestrNum, dCheckDate, sCheckNum, sFactoryNum, sFactName, sMethodName)
{
//	alert(0);
		if (bPoupUpAccept)
		{
			var id = object.getAttribute('tag');
			
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj.suot.php',//url адрес файла обработчика
			data:{'sDeviceName':sName, 'sDeviceReestrNum':sReestrNum,'dDeviceCheckDate':dCheckDate,'sDeviceCheckNum':sCheckNum,'action':'editDevice','id':id, 'sFactoryNum':sFactoryNum, 'sFactName':sFactName, 'sMethodName':sMethodName},//параметры запроса
			//dataType: 'json',
			response:'text',
			success:function (data) 
				{
						tobject = $(object);
						tobject.replaceWith(data);
				}
			});
		}
}

function EditStuff(sName, sSertNum, dSertDate, sReestrNum, sStuffPost)
{
		if (bPoupUpAccept)
		{
			var id = object.getAttribute('tag');
			
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj.suot.php',//url адрес файла обработчика
			data:{'sStuffName':sName, 'sStuffSertNum':sSertNum,'dStuffSertDate':dSertDate,'sStuffPost':sStuffPost,'sStuffReestrNum':sReestrNum,'action':'editStuff','id':id},//параметры запроса
			//dataType: 'json',
			response:'text',
			success:function (data) 
				{
						tobject = $(object);
						tobject.html(data);
				}
			});
		}
}

function EditAcredit(sActName, dAcrDateCreate, dAcrDateFinish)
{
		if (bPoupUpAccept)
		{
			var id = object.getAttribute('tag');
			
			$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'aj.suot.php',//url адрес файла обработчика
			data:{'sAcrName':sActName, 'sAcrDateCreate':dAcrDateCreate,'sAcrDateFinish':dAcrDateFinish,'action':'editArc','id':id},//параметры запроса
			//dataType: 'json',
			response:'text',
			success:function (data) 
				{
						tobject = $(object);
						tobject.html(data);
				}
			});
		}
}

function AddDevice(sName, sReestrNum, dCheckDate, sCheckNum, sFactoryNum, sFactName, sMethodName)
{
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'sDeviceName':sName, 'sDeviceReestrNum':sReestrNum,'dDeviceCheckDate':dCheckDate,'sDeviceCheckNum':sCheckNum,'action':'addDevice','sFactoryNum':sFactoryNum,'sFactName':sFactName, 'sMethodName':sMethodName},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					if (data != 'double')
					{
						newAdd = $(data);
						newAdd.insertAfter('#DeviceButtonAdd');
						newAdd.slideDown();
					}
					else
					{
						PoupUpMessgeCustomField('Упс. Такая запись уже существует', 'Проверьте верность указанных данных и повторите попытку', '');
					}
				}
			}
		});
}



function AddAcredit(sName, dDateStart, dDateFinish)
{
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.suot.php',//url адрес файла обработчика
		data:{'sAcrName':sName, 'sAcrDateCreate':dDateStart,'sAcrDateFinish':dDateFinish,'action':'addArc'},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					if (data != 'double')
					{
						newAdd = $(data);
						newAdd.insertAfter('#AcrButtonAdd');
						newAdd.slideDown();
					}
					else
					{
						PoupUpMessgeCustomField('Упс. Такая запись уже существует', 'Проверьте верность указанных данных и повторите попытку', '');
					}
				}
			}
		});
}
</script>
</body>
</html>
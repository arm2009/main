/*
	//Библиотека проверки данных в полях ввода
	//Проверяет введенные в поля значения по заданным алгоритмам, выделяет поля содержащие ошибки
	
	Устанавливается внутри <head></head>:		<script language="javascript" type="text/javascript" src="JS/InputValidate.js"></script>
	
	//Инициируется:
	//JS:				Последовательным вызовом необходимых команд
*/

function SetInputValidDefaultParams()				//Сброс параметров
{
	bInputValidError = false;
	sInputFirstErrorName = '';
}

var bInputValidError = false;						//Наличие идентифицированных ошибок с последнего сброса параметров
var sInputFirstErrorName = '';						//Имя первого элемента при проверке которого обнаруженна ошибка

function IsInputValidNotNull(sInputName)			//Проверка указанного элемента на пустое значение
{
	if($(sInputName).val().length > 0)
	{
		SetInputValidRight(sInputName);
		return true;
	}
	else
	{
		SetInputValidWrong(sInputName);
		return false;
	}
}

function IsInputValidEmail(sInputName)				//Проверка указанного элемента на указание почты
{
	var reg = /^[a-z0-9][a-z0-9\-._]*[a-z0-9]@[a-z0-9][a-z\-._]*[a-z0-9]\.(biz|com|edu|gov|info|int|mil|name|net|org|aero|asia|cat|coop|jobs|mobi|museum|pro|tel|travel|arpa|eco|xxx|[a-z]{2})$/i;
	if(reg.test($(sInputName).val()))
	{
		SetInputValidRight(sInputName);
		return true;
	}
	else
	{
		SetInputValidWrong(sInputName);
		return false;
	}
}

function IsInputValidEmailValue(sValue)				//Проверка указанного элемента на указание почты
{
	var reg = /^[a-z0-9][a-z0-9\-._]*[a-z0-9]@[a-z0-9][a-z\-._]*[a-z0-9]\.(biz|com|edu|gov|info|int|mil|name|net|org|aero|asia|cat|coop|jobs|mobi|museum|pro|tel|travel|arpa|eco|xxx|[a-z]{2})$/i;
	if(reg.test(sValue))
	{
		//SetInputValidRight(sInputName);
		return true;
	}
	else
	{
		//SetInputValidWrong(sInputName);
		return false;
	}
}

function IsInputValidPassword(sInputName1, sInputName2)			//Проверка указанных элементов на соответсвие с друг другом, с последующим сбросом в случае неудачи
{
	if($(sInputName1).val() == $(sInputName2).val() && $(sInputName1).val().length > 0)
	{
		SetInputValidRight(sInputName1);
		SetInputValidRight(sInputName2);
		return true;
	}
	else
	{
		SetInputValidWrong(sInputName1);
		SetInputValidWrong(sInputName2);
		$(sInputName1).val('');
		$(sInputName2).val('');
		return false;
	}
}

function IsInputValidPrintDiapazonString(sInputName)		//Проверка верности указания диапазона рабочих мест
{
	var berr = false;
	var sTmp = $(sInputName).val();
	if(sTmp.length == 0) berr = true;
	sTmp = sTmp.replace(/\ /g,'');
	if(sTmp.indexOf(',-') >= 0) berr = true;
	if(sTmp.indexOf('-,') >= 0) berr = true;
	sTmp = sTmp.replace(/\,/g,'');
	sTmp = sTmp.replace(/\–/g,'');
	sTmp = sTmp.replace(/\—/g,'');
	sTmp = sTmp.replace(/\-/g,'');
	sTmp = sTmp.replace(/[0-9]+/g,'');
	if(sTmp.length > 0 || berr)
	{
		SetInputValidWrong(sInputName);	
		return false;
	}
	else
	{
		SetInputValidRight(sInputName);
		return true;
	}
}

function SetInputValidFocusOnFirstErrorInput()		//Установка фокуса в первый элемент при проверке которого обнаруженна ошибка
{
	$(sInputFirstErrorName).focus();
}

function SetInputValidWrong(sInputName) 			//Установка элемента в положение ошибка
{
		if(sInputFirstErrorName.length == 0) sInputFirstErrorName = sInputName;
		bInputValidError = true;
		$(sInputName).removeClass('input_field_background');
		$(sInputName).addClass('input_wrong');
}
function SetInputValidRight(sInputName)				//Снятие положения ошибка с элемента
{
	$(sInputName).addClass('input_field_background');
	$(sInputName).removeClass('input_wrong');
}
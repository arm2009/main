var arrayFactors = new Array();
var arrayFactorsChem = new Array();

var maxId = 0;

var ilastImportOrg = -1;
var ilastImportRm = -1;

//Щелчек Ins
var lastactions = -1;
$(document).keyup(function(e){
	if	(e.keyCode == 45)
	{
		switch (lastactions)
		{
			case 0:
				ClickAddFolder();
			break;
			case 1:
				ClickAddRm()
			break;
		}
	}
});

function checkRmCount()
{
	if ($(".structure_rm").length == 0 && $(".structure_folder").length == 0) { $("#comment").slideDown(); }
}

function GetGroupId()
{
	return $("#nameHeader").attr('tag');
}

function divNewFactor (sName,sId,inIdPoint,sVar1,sPdu1,sAsset,sDateControl,idFactor,idFactorGroup,sVar2,sVar3,sVar4,sVar5,sPdu2,sPdu3,sPdu4,sPdu5,sAddonAsset)
{
	var sEditButton = '<div class="button8 button8_edit" title="Редактировать источник" onclick="ClickFactor('+sId+');">';
	switch (idFactorGroup)
	{
		//Отлавливаем и облагораживаем химию
		case '8':
		case '31':
			sPdu1 = sPdu1 +' / '+sPdu2;
			sVar1 = sVar1 +' / '+sVar2;
			sPdu1 = sPdu1.replace('-1', '-');
		break;
		default:
			//Отлавливаем и облагораживаем физику
			switch (idFactor)
			{
				case '13':
					sVar1 = sVar1 + '/' + sVar2 + '/' + sVar3;
				break;

				case '16':
				case '54':
				case '26':
					sPdu1 = sPdu1+' / '+sPdu2+' / '+sPdu3;
					sVar1 = sVar1+' / '+sVar2+' / '+sVar3;
				break;
				case '42':
					sPdu1 = 'до 36000/22000<br />до 70000/42000<br />до 100000/60000';
					sVar1 = sVar1+'<br />'+sVar2+'<br />'+sVar3;
				break;
				case '39':
					sPdu1 = 'до 5000/3000<br />до 25000/15000<br />до 46000/28000';
					sVar1 = sVar1+'<br />'+sVar2+'<br />'+sVar3;
				break;
				case '40':
					sPdu1 = 'до 30/10<br />до 15/7<br />до 870/350<br />до 435/175';
					sVar1 = sVar1+'<br />'+sVar2+'<br />'+sVar3+'<br />'+sVar4;
				break;
				case '41':
					sPdu1 = 'до 40000<br />до 20000';
					sVar1 = sVar1+'<br />'+sVar2;
				break;
				case '44':
					sPdu1 = '100';
				break;
				case '45':
					sPdu1 = 'до 8<br />до 2.5';
					sVar1 = sVar1+'<br />'+sVar2;
				break;
				case '22':
				case '45':
				case '18':
					sPdu1 = sPdu1+' / '+sPdu2;
					sVar1 = sVar1+' / '+sVar2;
				break;
				case '5':
					sPdu1 = '15 - 75';
				break;
				case '30':
				case '35':
				case '36':
				case '63':
				case '64':
					sEditButton = '';
				break;
				case '2':
					switch (sPdu1)
					{
						case '0':
							sPdu1 = '20.0 - 25.0';
						break;
						case '1':
							sPdu1 = '19.0 - 24.0';
						break;
						case '2':
							sPdu1 = '17.0 - 23.0';
						break;
						case '3':
							sPdu1 = '15.0 - 22.0';
						break;
						case '4':
							sPdu1 = '13.0 - 21.0';
						break;
					}
				break;
				case '6':
					switch (sPdu1)
					{
						case '0':
							sPdu1 = '0.1';
						break;
						case '1':
							sPdu1 = '0.2';
						break;
						case '2':
							sPdu1 = '0.3';
						break;
						case '3':
							sPdu1 = '0.4';
						break;
						case '4':
							sPdu1 = '0.4';
						break;
					}
				break;
				case '56':
					switch (sPdu1)
					{
						case '0':
							sPdu1 = '26.5';
						break;
						case '1':
							sPdu1 = '25.9';
						break;
						case '2':
							sPdu1 = '25.2';
						break;
						case '3':
							sPdu1 = '24.0';
						break;
						case '4':
							sPdu1 = '21.9';
						break;
					}
				break;
				case '43':
					sPdu1 = 'до 25% в неудобной<br />до 60% стоя';
					switch(sVar1)
					{
						case '0':
							sVar1 = 'Свободная<br />удобная';
						break;
						case '1':
							sVar1 = 'Неудобное<br />или стоя';
						break;
						case '2':
							sVar1 = 'Неудобное<br />или стоя';
						break;
						case '3':
							sVar1 = 'Неудобное<br />или стоя';
						break;
					}

				break;
				case '48':
					sPdu1 = '175';
				break;
				case '49':
					sPdu1 = '10';
				break;
				case '52':
					sPdu1 = '50';
				break;
				case '53':
					sPdu1 = '20';
				break;
				case '65':
					sPdu1 = '6';
				break;
				case '66':
					sPdu1 = '80';
				break;
				case '51':
				case '50':
				case '47':
				case '19':
				case '20':
					sEditButton = '';
					sPdu1 = '&#8212;';
					sVar1 = '';
				break;
			}
		break;
	}
	var warningstyle = '';
	var warningstyleg = '';
	if(sAsset.indexOf('3')>-1 || sAsset.indexOf('4')>-1) {warningstyle = 'structure_factors structure_factors_factor_warning';warningstyleg='w';} else {warningstyle = 'structure_factors structure_factors_factor';warningstyleg='';}

	return '<div class="'+warningstyle+'" style="display:none;" id="factors_'+sId+'" idPoint="'+inIdPoint+'"><table width="100%%" border="0" cellspacing="0" cellpadding="0"><tr><td width="24"><img src="Grph/factor/factor16'+warningstyleg+'.png" width="16" height="16" /></td><td class="comment">'+sName+'</td><td width="96" class="comment" id="factor_'+sId+'_var" align="center">'+sVar1+'</td><td width="144" class="comment" id="factor_'+sId+'_pdu" align="center">'+sPdu1+'</td><td width="96" class="comment" id="factor_'+sId+'_asset" align="center">'+sAsset+'</td><td width="96" class="comment" id="factor_'+sId+'_date">'+sDateControl+'</td><td width="32" valign="middle">'+sEditButton+'</td><td width="32" valign="middle"><div class="button8 button8_remove" title="Удалить источник" onclick="DelFactor('+sId+');"></div></td></tr></table></div>';
}

function divNewPoint (sName, sId, sTime, iTypePoint)
{

    switch(iTypePoint)
        {
            case '0':
                iTypePoint = '<i class="fa fa-home" id="point_'+sId+'_image"></i>';
                break;
            case '1':
                iTypePoint = '<i class="fa fa-cog" id="point_'+sId+'_image"></i>';
                break;
            case '2':
                iTypePoint = '<i class="fa fa-tint" id="point_'+sId+'_image"></i>';
                break;
        }
return '<div class="structure_factors structure_factors_point" style="display:none;" time="'+sTime+'" id="point_'+sId+'"><table width="100%%" border="0" cellspacing="0" cellpadding="0"><tr><td width="24" valign="middle" style="font-size:16px;">'+iTypePoint+'</td><td class="comment" id="point_'+sId+'_name">'+sName+'</td><td width="64" class="comment" id="point_'+sId+'_time">'+sTime+' ч.</td><td width="32" valign="middle"><div class="button8 button8_addfactors" title="Добавить фактор" onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxSelectFactors.php?pointid='+sId+'\');"></div></td><td width="32" valign="middle"><div class="button8 button8_edit" title="Редактировать источник" onclick="ClickPoint('+sId+')"></div></td><td width="32" valign="middle"><div class="button8 button8_remove" title="Удалить источник" onclick="DelPoints('+sId+')"></div></td></tr></table></div>';
}

function divNewFolder (sName, sId, sNum)
{
 	return '<div class="structure structure_folder" style="display:none;" id="'+sId+'" onclick="ClickFolder(this)">'+sName+'</div>';
}

function divNewRm (sName, sId, sNum, sidParent)
{
 	return '<div class="structure structure_rm" style="display:none;" id="'+sId+'" tag="'+sidParent+'" onclick="ClickRm(this)">'+sNum+' . '+sName+'</div>';
}

function divFolder (sName, sId, sNum)
{
 	return '<div class="structure structure_folder" id="'+sId+'" onclick="ClickFolder(this)">'+sName+'</div>';
}

function divRm (sName, sId, sNum, sidParent)
{
 	return '<div class="structure structure_rm" id="'+sId+'" tag="'+sidParent+'" onclick="ClickRm(this)">'+sNum+' . '+sName+'</div>';
}

var iSelectedTab = 0;

function changeTab(ui) {
  iSelectedTab = ui;
  ClickJ($(".structure_active"));
  $(".button_raz_active").addClass("button_raz");
  $(".button_raz_active").removeClass("button_raz_active");
  switch(ui) {
    case 0:
      $("#rm_control_tab_0").addClass("button_raz_active");
      $("#rm_control_tab_0").removeClass("button_raz");
      break;
    case 1:
      $("#rm_control_tab_1").addClass("button_raz_active");
      $("#rm_control_tab_1").removeClass("button_raz");
      break;
    case 2:
      $("#rm_control_tab_2").addClass("button_raz_active");
      $("#rm_control_tab_2").removeClass("button_raz");
      break;
    case 3:
      $("#rm_control_tab_3").addClass("button_raz_active");
      $("#rm_control_tab_3").removeClass("button_raz");
      break;
    case 4:
      $("#rm_control_tab_4").addClass("button_raz_active");
      $("#rm_control_tab_4").removeClass("button_raz");
      break;
  }
}

function hideComment()
{
	$("#comment").hide();
}

function openGroup(idGroup)
{
	progressAll_show();
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'readWorkPlacesFolders', 'idGroup':idGroup},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data)
			{
				if (data != null)
				{
					jQuery.each(data, function(key, value) {
						hideComment();
	                    $("#rm_navigation").append(divFolder(this[3],this[0],this[2]));
					});

							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'ajax.php',//url адрес файла обработчика
							data:{'action': 'readWorkPlaces', 'idGroup':idGroup},//параметры запроса
							dataType: 'json',
							response:'text',
							success:function (data)
							{
								if (data != null)
								{

									jQuery.each(data, function(key, value) {
									var newDiv = $(divRm(this[3],this[0],this[2], this[1]));
 									/*if (maxId < parseInt(this[2]))
									{
										maxId = parseInt(this[2]);
									}*/

									newDiv.insertAfter($('#'+this[1]));

							        });
								}
							}
							});
				}

				checkRmCount();
			},
				error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText);
			}
		});
		progressAll_hide();

}

function ClickFolder(object)
{
	var Iid = $(object).attr('id');
	Click(object);
	if ($('.structure_active').hasClass('structure_folder_collapse'))
	{
		$('.structure_active.structure_folder_collapse').removeClass('structure_folder_collapse');
	}
	else
	{
		$('.structure_active').addClass('structure_folder_collapse');
	}
	$('[tag = '+Iid+']').slideToggle();

	tobject = $(object);
	progressInfo_show();
		switch(iSelectedTab)
		{
			case 0:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_folder.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								$("#info_navigation").html(data);
								progressInfo_hide();
							}
							});
							break;
			}
			case 1, 2, 3:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_null.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								$("#info_navigation").html(data);
								progressInfo_hide();
							}
							});
							break;
				break;
			}
		}
}

function ClickRm(object)
{
	Click(object);
	tobject = $(object);
	progressInfo_show();

		switch(iSelectedTab)
		{
			case 0:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_rm.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								//Загрузка фрейма
								$("#info_navigation").html(data);
								//Подключения инструмента выбора дат
								$("#sNameDate").datepicker({
									showOtherMonths: true,
									selectOtherMonths: true,
									changeMonth: true,
									changeYear: true
								});
								//Подключение автозаполнения по названию / коду ОК
								$("#sNameRM").autocomplete({
									source: "aj.ok01694.php",
									minLength: 1,
									delay: 500,
									select: function( event, ui ) {
									$("#sNameRM").val(ui.item.value);
									$("#sNameRM2").val(ui.item.code);
									$("#sETKS").val(ui.item.etks);
									return false;}
								})
									.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
									return $( "<li>" )
									.append( "<a class=\"comment\" title=\"" + item.etks + "\"><span class=\"gray\">" + item.code + "</span> " + item.value + "</a>" )
									.appendTo( ul );
								};
								$("#sNameRM2").autocomplete({
									source: "aj.ok01694.php?code=1",
									minLength: 4,
									delay: 500,
									select: function( event, ui ) {
									$("#sNameRM").val(ui.item.name);
									$("#sNameRM2").val(ui.item.value);
									$("#sETKS").val(ui.item.etks);
									return false;}
								})
									.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
									return $( "<li>" )
									.append( "<a class=\"comment\">" + item.value + " <span class=\"gray\">" + item.name + "</span></a>" )
									.appendTo( ul );
								};
								$("#sETKS").autocomplete({
									source: "aj.ok01694.php?etks=1",
									minLength: 1,
									delay: 500,
									select: function( event, ui ) {
									$("#sETKS").val(ui.item.value);
									return false;}
								})
									.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
									return $( "<li>" )
									.append( "<a class=\"comment\">" + item.value + "</a>" )
									.appendTo( ul );
								};
								progressInfo_hide();
							}
							});
							break;
			}
			case 1:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_factors.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								$("#info_navigation").html(data);
								//Загрузка факторов тут
								ReadPoints(tobject.attr("id"));
								progressInfo_hide();
							}
							});
							break;
				break;
			}
			case 2:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_siz.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								$("#info_navigation").html(data);
								//Подключения инструмента выбора дат
								$("#dSizDate").datepicker({
									showOtherMonths: true,
									selectOtherMonths: true,
									changeMonth: true,
									changeYear: true
								});
								ReadSIZ(tobject.attr("id"));
								progressInfo_hide();
							}
							});
							break;
				break;
			}
			case 3:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_warranty.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								$("#info_navigation").html(data);
								progressInfo_hide();
							}
							});
							break;
				break;
			}
			case 4:
			{
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'info_navigation_actions.php',//url адрес файла обработчика
							data:{'id': tobject.attr("id")},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								$("#info_navigation").html(data);
								progressInfo_hide();
								ReadActions(tobject.attr("id"));
							}
							});
							break;
				break;
			}
		}
}

function Click(object)
{
	var jobject = $(object);
	$('.structure_active').removeClass('shawdow_min');
	$('.structure_active').removeClass('structure_active');

	jobject.addClass('structure_active');
	jobject.addClass('shawdow_min');

	$("#buttonAddRm").attr('style','diplay:yes;');
}

function ClickJ(object)
{
	jobject = $(object);

	if (jobject.hasClass('structure_folder'))
	{
		ClickFolder(object);
	}

	if (jobject.hasClass('structure_rm'))
	{
		ClickRm(object);
	}
}

function ClickAddFolder()
{
	lastactions = 0;
	var aPoupupFields = [ 'sName'];
	var aPoupupFieldsScribe = [ 'Название подразделения'];
	var aPoupupFieldsDefoultValue = [ ''];
	var s = 'AddFolder($(\'#sName\').val())';
	PoupUpMessgeCustomField('Добавление подразделения','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}

function AddFolder(sName)
{
	var idGroup = GetGroupId();
							$.ajax({
							type:'post',//тип запроса: get,post либо head
							url:'ajax.php',//url адрес файла обработчика
							data:{'action': 'addWorkPlace', 'idGroup':idGroup,'sName':sName},//параметры запроса
							//dataType: 'json',
							response:'text',
							success:function (data)
							{
								var NewDiv = $(divNewFolder(sName,data, -1));
								$("#rm_navigation").append(NewDiv);
								NewDiv.slideDown();
								hideComment();
							}
							});

}

function ClickAddRm()
{
	lastactions = 1;
	var id = '-1';

	if ($('.structure_folder.structure_active').attr('id') != null)
	{
		id = $('.structure_folder.structure_active').attr('id');
	}
	if ($('.structure_rm.structure_active').attr('tag') != null)
	{
		id = $('.structure_rm.structure_active').attr('tag');
	}

	if (id != '-1')
	{
		GetMaxId(GetGroupId());
		var aPoupupFields = ['sNameOK01694'];
		var aPoupupFieldsScribe = ['Название рабочего места'];
		var aPoupupFieldsDefoultValue = [''];
		var s = 'AddRm($(\'#sNameOK01694\').val(),'+id+')';
		PoupUpMessgeCustomField('Добавление рабочего места','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
	}
}

function CheckTime(idRm)
{


				$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'checkTime', 'idRm':idRm},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					//Сюда вставить поле с сообщением data.
					if (data != '')
					{
                        $('#TimeMesMes').html(data);
						$('#TimeMes').show();
					}
					else
					{
						$('#TimeMes').hide();
					}
				}
			});
}

function AddRm(sName, sParentId)
{
	var idGroup = GetGroupId();
	maxId = parseInt(maxId)+1;
				$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'addWorkPlace', 'idGroup':idGroup,'sName':sName, 'sNum':maxId, 'idParent': sParentId, 'sOk': sPoupUpAddOk, 'sEtks': sPoupUpAddEtks},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					var NewDiv = $(divNewRm(sName, data, maxId, sParentId));

					//Установка элемента
					if($('[id = '+sParentId+']').nextAll('.structure_folder:first').length > 0)
					{
						$('[id = '+sParentId+']').nextAll('.structure_folder:first').before(NewDiv);
					}
					else
					{
						if($('[id = '+sParentId+']').nextAll('.structure_rm:last').length > 0)
						$('[id = '+sParentId+']').nextAll('.structure_rm:last').after(NewDiv);
						else
						$('[id = '+sParentId+']').after(NewDiv);
					}

					$('[tag = '+sParentId+']').slideDown();
					$('[id = '+sParentId+']').removeClass('structure_folder_collapse');

					hideComment();
				}
			});
}

function ClickDel()
{
		var s = 'Del()';
		PoupUpReqest('Точно удалить?','Текущее рабочее место или подразделение.',s);
}

function Del()
{
	var id = '-1';
	if ($('.structure_active').attr('id') != null)
	{
		if ($('.structure_folder.structure_active').attr('id') != null)
		{
			id = $('.structure_folder.structure_active').attr('id');
		}
		else
		{
			id = $('.structure_rm.structure_active').attr('id');
		}

				if (id != '-1')
				{
					var idGroup = GetGroupId();
					$.ajax({
					type:'post',//тип запроса: get,post либо head
					url:'ajax.php',//url адрес файла обработчика
					data:{'action': 'delWorkPlace', 'id':id, 'idGroup':idGroup},//параметры запроса
					//dataType: 'json',
					response:'text',
					success:function (data)
				{
					 LoadEmptyInfo();
					$('.structure_active').slideUp('fast', function() {$('.structure_active').remove();	checkRmCount();});
					$('[tag = '+id+']').slideUp('fast', function() {$('[tag = '+id+']').remove();	checkRmCount();});
				}
		});
				}
	}
}

function LoadEmptyInfo()
{
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'info_navigation_null.php',//url адрес файла обработчика
	data:{'id': tobject.attr("id")},//параметры запроса
	//dataType: 'json',
	response:'text',
	success:function (data)
	{
		$("#info_navigation").html(data);
	}
	});
}

function ClickSaveRm()
{
	var id = $(".structure_active").attr("id");
	var sName = $("#sNameRM").val();
	var sOk = $("#sNameRM2").val();
	var sPrefix = '';
	var sNum = $("#sNumRM").val();
	var sNumAnalog = $("#sNumAnalog").val();
	var sETKS = $("#sETKS").val();
	var sCount = $("#sCount").val();
	var sCountWoman = $("#sCountWoman").val();
	var sCountYouth = $("#sCountYouth").val();
	var sCountDisabled = $("#sCountDisabled").val();
	var sSnils = $("#sSnils").val();
	var sDate = $("#sNameDate").val();
	var fWorkDay = $("#fWorkDay").val();
	if (fWorkDay != null)
	{
		fWorkDay = fWorkDay.replace(',','.');
	}
	SaveRm(id, sName, sOk, sPrefix, sNum, sNumAnalog, sETKS, sCount, sCountWoman, sCountYouth, sCountDisabled, sSnils, sDate, fWorkDay);
	$(".structure_active.structure_rm").html(sNum+" . "+sName);
	$(".structure_active.structure_folder").html(sName);


}

function SaveRm(id, sName, sOk, sPrefix, sNum, sNumAnalog, sETKS, sCount, sCountWoman, sCountYouth, sCountDisabled, sSnils, sDate, fWorkDay)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'saveWorkPlace', 'id':id, 'sName':sName, 'sOk':sOk, 'sPrefix':sPrefix, 'sNum':sNum, 'sNumAnalog':sNumAnalog, 'sETKS':sETKS, 'sCount':sCount, 'sCountWoman':sCountWoman, 'sCountYouth':sCountYouth, 'sCountDisabled':sCountDisabled, 'sSnils':sSnils, 'sDateCreate':sDate, 'fWorkDay':fWorkDay},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
                                       	CheckTime(id);
				}
	});
}

function GetMaxId(idGroup)
{
		$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'getMaxId', 'idGroup':idGroup},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					maxId = data;
				}
	});
}

function ReadPoints(sIdRm)
{
		$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'readPoints', 'idRm':sIdRm},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						$("#factors_navigation").attr('null','false');

						//Заготовка сообщения о превышении времени
						$("#factors_navigation").append('<div class="structure_factors structure_factors_warning" style="display:none;margin-left:20px;" id="TimeMes"><table width="100%%" border="0" cellspacing="0" cellpadding="0"><tr><td id="TimeMesMes" style="color:#666666;">Тест</td></tr></table></div>');
						jQuery.each(data, function(key, value)
						{
							var newDiv = $(divNewPoint(this[2],this[0], this[3],this[4]));
							newDiv.attr('style', 'display:yes;');
 							$("#factors_navigation").append(newDiv);
							ReadFactors(this[0]);
				        });
						$("#factors_navigation").append('<input name="AddPoints" type="submit" class="input_button" id="AddPoints" value="+ Добавить источник вредных или опасных факторов" onClick="ClickAddPoint();"/>');

					}
					else
					{
						$("#factors_navigation").attr('null','true');
						$("#factors_navigation").html('<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><div id="NullFactors" class="nowBlock comment" style="margin-bottom:20px;">Источники вредных или опасных факторов производственной среды и трудового процесса остутствуют.</div><input name="AddPoints" type="submit" class="input_button" id="AddPoints" value="+ Добавить источник вредных или опасных факторов" onClick="ClickAddPoint();"/></tr></table>');
					}
					CheckTime(sIdRm);

				}
	});
}

function ReadFactors(idPoint)
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'readFactors', 'idPoint':idPoint, 'idRM':idRM},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						jQuery.each(data, function(key, value)
						{
							var newDiv = $(divNewFactor(this[2],this[0],idPoint,this[3],this[5],this[6],this[4],this[15],this[16],this[7],this[8],this[9],this[10],this[11],this[12],this[13],this[14],this[17]));
							newDiv.attr('style', 'display:yes;');
							newDiv.insertAfter($(".structure_factors_point[id='point_"+idPoint+"']"));
 							//$("#factors_navigation").append(newDiv);
				        });
					}
				}
	});
}



// Добавление источника
function ClickAddPoint()
{
	var id = '-1';

	if ($('.structure_rm.structure_active').attr('tag') != null)
	{
		id = $('.structure_rm.structure_active').attr('id');
	}

	if (id != '-1')
	{
		GetMaxId(GetGroupId());
		var aPoupupFields = ['pName', 'sPointType', 'sTime'];
		var aPoupupFieldsScribe = ['Название точки измерения','Тип точки измерения','Время пребывания'];
		var aPoupupFieldsDefoultValue = ['','0','8'];
		var s = 'AddPoint($(\'#pName\').val(),'+id+',$(\'#sTime\').val(),$(\'#sPointType\').val())';
		PoupUpMessgeCustomField('Источник вредных или опасных факторов','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
	}
}

function AddPoint(sName, iId, sTime, iType)
{
	var idGroup =	GetGroupId
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'addPoint', 'idRm':iId, 'sName':sName,'idGroup':idGroup, 'sTime': sTime, 'iType': iType},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					if(data != -1)
					{
					if($("#factors_navigation").attr('null') == 'true') {$("#factors_navigation").attr('null','false');$("#NullFactors").remove();$("#factors_navigation").html('<input name="AddPoints" type="submit" class="input_button" id="AddPoints" value="+ Добавить источник вредных или опасных факторов" onClick="ClickAddPoint();"/>');}
					var newDiv = $(divNewPoint(sName, data, sTime, iType));

					$("#AddPoints").before(newDiv);
					newDiv.slideDown();
					ReadFactors(data);
					CheckTime(iId);
					}
					else
					{
						PoupUpMessge('Упс', 'Источник факторов с таким названием уже существует на этом рабочем месте');
					}
				}
	});
}

//sType 'chem' или 'class'
function AddFactor(pointId, id, sType)
{
	var idRM = $(".structure_active").attr("id");
	var idPoint = pointId;
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'addFactor', 'idPoint':idPoint, 'idFactor':id, 'sType':sType, 'idRm':idRM},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
							var newDiv = $(divNewFactor(data[2],data[0],idPoint,data[3],data[5],data[6],data[4],data[15],data[16],data[7],data[8],data[9],data[10],data[11],data[12],data[13],data[14],data[17]));
							newDiv.insertAfter($(".structure_factors_point[id='point_"+idPoint+"']"));
							newDiv.slideDown();
					}
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

function ClickFactor(inId)
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'readFactor', 'id':inId, 'idRM':idRM},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						//Перебор по группам
						switch (data[16])
						{
							case '8':
							case '31':
								var aPoupupFields = ['fMM','fSS','dControl'];
								var aPoupupFieldsScribe = ['Максимально разовая концентрация','Среднесменная концентрация','Дата проведения измерения'];
								var aPoupupFieldsDefoultValue = [data[3],data[7],data[4]];
								var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fMM\').val(),-1,$(\'#fSS\').val(),-1,-1,-1,-1,-1,-1,-1)';
							break;
							default:
							//Перебор по факторам
							switch (data[15])
							{
								case '18':
									var aPoupupFields = ['fFactLightA','fPduLightA','fFactLightB','fPduLightB','sAddLightPolygon','sAddLightHeight','sAddLightDark','sAddLightType','dControl'];
									var aPoupupFieldsScribe = ['Общее освещение, фактическое значение, лк.','Общее освещение, ПДУ, лк.','Комбинированное освещение, фактическое значение, лк.','Комбинированное освещение, ПДУ, лк.','Рабочая поверхность, плоскость нормирования','Высота подвеса ламп, м.','Не горящие лампы, %','Тип источника света','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[5],data[7],data[11],data[18],data[19],data[20],data[21],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactLightA\').val(),$(\'#fPduLightA\').val(),$(\'#fFactLightB\').val(),$(\'#fPduLightB\').val(),-1,-1,-1,-1,-1,-1), EditPointAddLight('+inId+',$(\'#sAddLightPolygon\').val(),$(\'#sAddLightHeight\').val(),$(\'#sAddLightDark\').val(),$(\'#sAddLightType\').val())';
								break;
								case '2':
								case '6':
								case '56':
									var aPoupupFields = ['fFactA','microclimFactB','dControl'];
									var aPoupupFieldsScribe = ['Фактическое значение','Категория работ','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[5],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),$(\'#microclimFactB\').val(),-1,-1,-1,-1,-1,-1,-1,-1)';
								break;
								case '16':
									var aPoupupFields = ['fFactX','fFactY','fFactZ','vCategory','dControl'];
									var aPoupupFieldsScribe = ['Ось X','Ось Y','Ось Z','Категория вибрации','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[10],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactX\').val(),-1,$(\'#fFactY\').val(),-1,$(\'#fFactZ\').val(),-1,-1,-1,$(\'#vCategory\').val(),-1)';
								break;
								case '54':
									var aPoupupFields = ['fFactX','fFactY','fFactZ','dControl'];
									var aPoupupFieldsScribe = ['Ось X','Ось Y','Ось Z','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactX\').val(),-1,$(\'#fFactY\').val(),-1,$(\'#fFactZ\').val(),-1,-1,-1,-1,-1)';
								break;
								case '26':
									var aPoupupFields = ['fFactA','fFactB','fFactC','dControl'];
									var aPoupupFieldsScribe = ['УФ-А, Вт/м2','УФ-B, Вт/м2','УФ-C, Вт/м2','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),-1,$(\'#fFactB\').val(),-1,$(\'#fFactC\').val(),-1,-1,-1,-1,-1)';
								break;
								case '39':
									var aPoupupFields = ['fFactA','fFactB','fFactC','dControl'];
									var aPoupupFieldsScribe = ['Перемещение до 1 м, кг*м','Перемещение от 1 до 5 м, кг*м','Перемещение более 5 м, кг*м','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),-1,$(\'#fFactB\').val(),-1,$(\'#fFactC\').val(),-1,-1,-1,-1,-1)';
								break;
								case '40':
									var aPoupupFields = ['fFactA','fFactB','fFactC','fFactD','dControl'];
									var aPoupupFieldsScribe = ['Подъем и перемещение тяжести, до 2 раз в час, кг','Подъем и перемещение тяжести, более 2 раз в час, кг','Суммарная масса грузов перемещаемых в течении каждого часа с рабочей поверхности, кг','Суммарная масса грузов перемещаемых в течении каждого часа с пола, кг','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[9],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),-1,$(\'#fFactB\').val(),-1,$(\'#fFactC\').val(),-1,$(\'#fFactD\').val(),-1,-1,-1)';
								break;
								case '41':
									var aPoupupFields = ['fFactA','fFactB','dControl'];
									var aPoupupFieldsScribe = ['Локальная нагрузка, едениц','Региональная нагрузка, едениц','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),-1,$(\'#fFactB\').val(),-1,-1,-1,-1,-1,-1,-1)';
								break;
								case '42':
									var aPoupupFields = ['fFactA','fFactB','fFactC','dControl'];
									var aPoupupFieldsScribe = ['При удержании груза одной рукой, кгс*с','При удержании груза двумя руками, кгс*с','При удержании груза с участием мышц корпуса и ног, кгс*с','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),-1,$(\'#fFactB\').val(),-1,$(\'#fFactC\').val(),-1,-1,-1,-1,-1)';
								break;
								case '45':
									var aPoupupFields = ['fFactA','fFactB','dControl'];
									var aPoupupFieldsScribe = ['По горизонтали, км.','По вертикали, км.','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFactA\').val(),-1,$(\'#fFactB\').val(),-1,-1,-1,-1,-1,-1,-1)';
								break;
								case '13':
									var aPoupupFields = ['fFact1','fFact2','fFact3','nCategory','dControl'];
									var aPoupupFieldsScribe = ['Фактическое значение 1','Фактическое значение 2','Фактическое значение 3','Характеристика шума','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[8],data[10],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFact1\').val(), -1, $(\'#fFact2\').val(), -1, $(\'#fFact3\').val(),-1,-1,-1,-1,-1,$(\'#nCategory\').val(),-1)';
								break;
								case '55':
								case '7':
								case '5':
								case '14':
								case '24':
								case '25':
								case '29':
								case '61':
								case '62':
								case '44':
								case '48':
								case '49':
								case '52':
								case '53':
								case '65':
								case '66':
									var aPoupupFields = ['fFact','dControl'];
									var aPoupupFieldsScribe = ['Фактическое значение','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFact\').val(),-1,-1,-1,-1,-1,-1,-1,-1,-1)';
								break;
								case '43':
									var aPoupupFields = ['fHeavyRPFact','dControl'];
									var aPoupupFieldsScribe = ['Рабочее положение тела','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fHeavyRPFact\').val(),-1,-1,-1,-1,-1,-1,-1,-1,-1)';
								break;
								case '22':
									var aPoupupFields = ['fE','fM','dControl'];
									var aPoupupFieldsScribe = ['Электрическое поле, кВ/м','Магнитное поле, А/м','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[7],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fE\').val(),-1,$(\'#fM\').val(),-1,-1,-1,-1,-1,-1,-1)';
								break;
								default:
									//Для всех прочих
									var aPoupupFields = ['fFact','fPdu','dControl'];
									var aPoupupFieldsScribe = ['Фактическое значение','Предельно допустимый уровень','Дата проведения измерения'];
									var aPoupupFieldsDefoultValue = [data[3],data[5],data[4]];
									var s = 'EditFactor('+inId+','+idRM+',$(\'#dControl\').val(),$(\'#fFact\').val(),$(\'#fPdu\').val(),-1,-1,-1,-1,-1,-1,-1,-1)';
								break;
							}
							break;
						}
						if(aPoupupFields.length > 0)
						PoupUpMessgeCustomField(data[2],'',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
					}
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

function EditFactor(inIdFactor,inIdRm,dControl,fFact1,fPdu1,fFact2,fPdu2,fFact3,fPdu3,fFact4,fPdu4,fFact5,fPdu5)
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'editFactor', 'inIdFactor':inIdFactor, 'inIdRm':inIdRm, 'dControl':dControl, 'fFact1':fFact1, 'fPdu1':fPdu1, 'fFact2':fFact2, 'fPdu2':fPdu2, 'fFact3':fFact3, 'fPdu3':fPdu3, 'fFact4':fFact4, 'fPdu4':fPdu4, 'fFact5':fFact5, 'fPdu5':fPdu5},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						var newDiv = $(divNewFactor(data[2],data[0],data[1],data[3],data[5],data[6],data[4],data[15],data[16],data[7],data[8],data[9],data[10],data[11],data[12],data[13],data[14],data[17]));
						$('#factors_'+inIdFactor).replaceWith(newDiv);
						$('#factors_'+inIdFactor).show();
					}
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

function DelFactor(id)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'delFactor', 'id':id},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					$("#factors_"+id).slideUp(('fast', function() {
					}));
				}
	});
}

function ClickPoint(objectId)
{
	tobject = $('#point_'+objectId);
	var sRMid = $(".structure_active").attr("id");
	var id = objectId;

	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'readPoint', 'idPoint':id, 'idRm':sRMid},//параметры запроса
				dataType: 'json',
				response: 'text',
				success:function (data)
				{
					var aPoupupFields = ['sName', 'sPointType', 'sTime'];
					var aPoupupFieldsScribe = ['Название точки измерения','Тип точки измерения','Время пребывания'];
					var aPoupupFieldsDefoultValue = [data['sName'],data['iType'],data['sTime']];
					var s = 'EditPoint('+id+', '+sRMid+', $(\'#sPointType\').val())';
					PoupUpMessgeCustomField('Редактирование источника','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}

	});
}

function EditPointAddLight(idFactor, sLightPolygone, sLightHeight, sLightDark, sLightType)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'EditPointAddLight', 'idFactor':idFactor, 'sLightPolygone':sLightPolygone, 'sLightHeight':sLightHeight, 'sLightDark':sLightDark, 'sLightType':sLightType},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					//alert(data);
				}
	});
}

function EditPoint(id, RMid, iType)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'editPoint', 'idRm':RMid, 'idPoint':id, 'sName':$('#sName').val(), 'sTime':$('#sTime').val(), 'iType':iType},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					$('#point_'+id+'_name').html($('#sName').val());
					$('#point_'+id+'_time').html($('#sTime').val()+' ч.');

                    //Удаляем предыдущую иконку
                    $('#point_'+id+'_image').removeClass('fa-home');
                    $('#point_'+id+'_image').removeClass('fa-cog');
                    $('#point_'+id+'_image').removeClass('fa-tint');
                    //Добавляем новую
                    switch(iType)
                        {
                            case '0':
                                $('#point_'+id+'_image').addClass('fa-home');
                                break;
                            case '1':
                                $('#point_'+id+'_image').addClass('fa-cog');
                                break;
                            case '2':
                                $('#point_'+id+'_image').addClass('fa-tint');
                                break;
                        }
					CheckTime(RMid);
				}
	});
}

function DelPoints(idPoint)
{
	var div = $('.structure_factors_point.structure_factors_active');
	var sRMid = $(".structure_active").attr("id");
	$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'ajax.php',//url адрес файла обработчика
			data:{'action': 'delPoint', 'idPoint':idPoint, 'idRm':sRMid},//параметры запроса
			//dataType: 'json',
			response:'text',
			success:function (data)
			{
				$("#point_"+idPoint).slideUp('fast', function() {
				$("#point_"+idPoint).remove();

				$('.structure_factors_factor[idPoint='+idPoint+']').each(function(index, element) {
                    $(element).slideUp('fast', function() {
						$(element).remove();
					});
                });

				if($(".structure_factors_point").length == 0){$("#factors_navigation").html('<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><div id="NullFactors" class="nowBlock comment" style="margin-bottom:20px;">Источники вредных или опасных факторов производственной среды и трудового процесса остутствуют.</div><input name="AddPoints" type="submit" class="input_button" id="AddPoints" value="+ Добавить источник вредных или опасных факторов" onClick="ClickAddPoint();"/></tr></table>');$("#factors_navigation").attr('null','true');}
				});
				CheckTime(sRMid);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText);
			}
	});

}

//Сохранение гарантий
function ClickSaveWarranty()
{
	var sRMid = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'saveWarranty', 'idRm': sRMid, 'iCompSurcharge' : $("input:radio[name ='iCompSurcharge']:checked").val(), 'sCompBaseSurcharge' : $('#sCompBaseSurcharge').val(), 'sCompFactSurcharge' : $("input:radio[name ='sCompFactSurcharge']:checked").val(), 'iCompVacation' : $("input:radio[name ='iCompVacation']:checked").val(), 'sCompBaseVacation' : $('#sCompBaseVacation').val(), 'sCompFactVacation' : $("input:radio[name ='sCompFactVacation']:checked").val(), 'iCompShortWorkDay' : $("input:radio[name ='iCompShortWorkDay']:checked").val(), 'sCompBaseShortWorkDay' : $('#sCompBaseShortWorkDay').val(), 'sCompFactShortWorkDay' : $("input:radio[name ='sCompFactShortWorkDay']:checked").val(), 'iCompMilk' : $("input:radio[name ='iCompMilk']:checked").val(), 'sCompBaseMilk' : $('#sCompBaseMilk').val(), 'sCompFactMilk' : $("input:radio[name ='sCompFactMilk']:checked").val(), 'iCompFood' : $("input:radio[name ='iCompFood']:checked").val(), 'sCompBaseFood' : $('#sCompBaseFood').val(), 'sCompFactFood' : $("input:radio[name ='sCompFactFood']:checked").val(), 'iCompPension' : $("input:radio[name ='iCompPension']:checked").val(), 'sCompBasePension' : $('#sCompBasePension').val(), 'sCompFactPension' : $("input:radio[name ='sCompFactPension']:checked").val(), 'iCompPhysical' : $("input:radio[name ='iCompPhysical']:checked").val(), 'sCompBasePhysical' : $('#sCompBasePhysical').val(), 'sCompFactPhysical' : $("input:radio[name ='sCompFactPhysical']:checked").val()},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{

				}
	});
}
function ImportWaranty(idSizRm)
{

	var idRM = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'ImportWaranty', 'idDonor':idSizRm, 'idRecepient':idRM},//параметры запроса
				response:'text',
				success:function (data)
				{
					ClickJ($(".structure_active"));
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

//Чтение мероприятий
function ReadActions(sIdRm)
{
		$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'ReadActions', 'idRm':sIdRm},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					$('#actions_navigation').attr('tag',sIdRm);
					if (data != null)
					{
						$("#actions_navigation").attr('null','false');
						//alert(data.length);
						jQuery.each(data, function(key, value)
						{
							var newDiv = $(divNewActions(this[0],this[1], this[2], this[3], this[4], this[5], this[6]));
							newDiv.attr('style', 'display:yes;');
 							$("#actions_navigation").append(newDiv);
				        });
						$("#actions_navigation").append('<input name="AddAction" type="submit" class="input_button" id="AddAction" value="+ Добавить рекомендацию" onClick="ClickAddAction();"/><input name="button" type="submit" class="input_button input_button_book" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxSelectActions.php?idRm='+$("#actions_navigation").attr('tag')+'\');" title="Справочник рекомендаций"/><input name="button" type="submit" class="input_button input_button_master" id="button" value=" " onclick="FastActions();" title="Быстрое заполнение..."/><input name="button" type="submit" class="input_button input_button_import" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxImport.php?sType=Actions\');" title="Импорт данных из других рабочих мест..."/>');
					}
					else
					{
						$("#actions_navigation").attr('null','true');
						$("#actions_navigation").html('<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><div id="NullFactors" class="nowBlock comment" style="margin-bottom:20px;">Рекомендации по улучшению условий труда, по режимам труда и отдыха, по подбору работников отсутствуют.</div><input name="AddAction" type="submit" class="input_button" id="AddAction" value="+ Добавить рекомендацию" onClick="ClickAddAction();"/><input name="button" type="submit" class="input_button input_button_book" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxSelectActions.php?idRm='+$("#actions_navigation").attr('tag')+'\');" title="Справочник рекомендаций"/><input name="button" type="submit" class="input_button input_button_master" id="button" value=" " onclick="FastActions();" title="Быстрое заполнение..."/><input name="button" type="submit" class="input_button input_button_import" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxImport.php?sType=Actions\');" title="Импорт данных из других рабочих мест..."/></tr></table>');
					}
				}
	});
}

function divNewActions (sId,sActivityName,sActivityTarget,sTerm,sInvolved,sMark,sType)
{
	var sImage = '';
	if(sType == 0) sImage = 'actions16.png'; else sImage = 'actions16o.png';
	if(sActivityName.length == 0) {sActivityName = '&#8212;';}
	var sEditButton = '<div class="button8 button8_edit" title="Редактировать рекомендацию" onclick="ClickEditActions('+sId+');">';
	return '<div class="structure_factors structure_factors_actions" style="display:none; margin-left:20px;" id="actions_'+sId+'"><table width="100%%" border="0" cellspacing="0" cellpadding="0"><tr><td width="24"><img src="Grph/actions/'+sImage+'" width="16" height="16" /></td><td class="comment">'+sActivityName+'</td><td width="32" valign="middle">'+sEditButton+'</td><td width="32" valign="middle"><div class="button8 button8_remove" title="Удалить источник" onclick="DelAction('+sId+');"></div></td></tr></table></div>';
}


//Добавление мероприятия
function ClickAddAction()
{
	var sRMid = $(".structure_active").attr("id");
	var aPoupupFields = ['tActivityName', 'tActivityTarget', 'sTerm', 'sInvolved', 'iActionType'];
	var aPoupupFieldsScribe = ['Наименоване','Цель','Срок','Привлекаемые структурные подразделения','Скрыть данную рекомендацию в Перечне рекомендуемых мероприятий'];
	var aPoupupFieldsDefoultValue = ['','','','','0'];
	var s = 'AddAction('+sRMid+', $(\'#tActivityName\').val(), $(\'#tActivityTarget\').val(), $(\'#sTerm\').val(), $(\'#sInvolved\').val(), $(\'#iActionType\').prop(\'checked\'))';
	PoupUpMessgeCustomField('Рекомендация','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}

function AddAction(idRm, sActivityName, sActivityTarget, sTerm, sInvolved, iType)
{
	if(!iType) iType = 0; else iType = 1;
	var idGroup =	GetGroupId
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'AddActions', 'idRm':idRm, 'sActivityName':sActivityName,'sActivityTarget':sActivityTarget, 'sTerm': sTerm, 'sInvolved': sInvolved, 'iType': iType},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
		{
				if($("#actions_navigation").attr('null') == 'true') {$("#actions_navigation").attr('null','false');$("#NullFactors").remove();$("#actions_navigation").html('<input name="AddAction" type="submit" class="input_button" id="AddAction" value="+ Добавить рекомендацию" onClick="ClickAddAction();"/><input name="button" type="submit" class="input_button input_button_book" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxSelectActions.php?idRm='+$("#actions_navigation").attr('tag')+'\');" title="Справочник рекомендаций"/><input name="button" type="submit" class="input_button input_button_master" id="button" value=" " onclick="FastActions();" title="Быстрое заполнение..."/><input name="button" type="submit" class="input_button input_button_import" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxImport.php?sType=Actions\');" title="Импорт данных из других рабочих мест..."/>');}
				var newDiv = $(divNewActions(data,sActivityName, sActivityTarget, sTerm, sInvolved, '', iType));

				$("#AddAction").before(newDiv);
				newDiv.slideDown();
		}
	});
}

function DelAction(id)
{
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'DelActions', 'id':id},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
		{
			$("#actions_"+id).slideUp('fast', function() {
			$("#actions_"+id).remove();
			if($(".structure_factors_actions").length == 0){$("#actions_navigation").html('<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><div id="NullFactors" class="nowBlock comment" style="margin-bottom:20px;">Рекомендации по улучшению условий труда, по режимам труда и отдыха, по подбору работников отсутствуют.</div><input name="AddAction" type="submit" class="input_button" id="AddAction" value="+ Добавить рекомендацию" onClick="ClickAddAction();"/><input name="button" type="submit" class="input_button input_button_book" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxSelectActions.php?idRm='+$("#actions_navigation").attr('tag')+'\');" title="Справочник рекомендаций"/><input name="button" type="submit" class="input_button input_button_master" id="button" value=" " onclick="FastActions();" title="Быстрое заполнение..."/><input name="button" type="submit" class="input_button input_button_import" id="button" value=" " onclick="PoupUpMessgeAjax(\'frame_PoupUp_AjaxImport.php?sType=Actions\');" title="Импорт данных из других рабочих мест..."/></tr></table>');$("#actions_navigation").attr('null','true');}
			});
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
		}
	});
}

function ClickEditActions(inId)
{
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'readAction', 'id':inId},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data)
		{
			if (data != null)
			{
				jQuery.each(data, function(key, value)
				{
					var aPoupupFields = ['tActivityName', 'tActivityTarget', 'sTerm', 'sInvolved', 'iActionType'];
					var aPoupupFieldsScribe = ['Наименоване','Цель','Срок','Привлекаемые структурные подразделения','Скрыть данную рекомендацию в Перечне рекомендуемых мероприятий'];
					var aPoupupFieldsDefoultValue = [this[1],this[2],this[3],this[4],this[6]];
					var s = 'EditActions('+inId+', $(\'#tActivityName\').val(), $(\'#tActivityTarget\').val(), $(\'#sTerm\').val(), $(\'#sInvolved\').val(), $(\'#iActionType\').prop(\'checked\'))';
					PoupUpMessgeCustomField('Рекомендация','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
				});
			}
		},
			error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
		}
	});
}

function EditActions(inId, sActivityName, sActivityTarget, sTerm, sInvolved, iType)
{
	if(!iType) iType = 0; else iType = 1;
	var idRM = $(".structure_active").attr("id");
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'editActions', 'sActivityName':sActivityName, 'sActivityTarget':sActivityTarget, 'sTerm':sTerm, 'sInvolved':sInvolved, 'inId':inId, 'iType': iType},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data)
		{
			if (data != null)
			{
				var newDiv = '';
				jQuery.each(data, function(key, value)
				{
					newDiv = $(divNewActions(this[0],this[1], this[2], this[3], this[4], this[5], this[6]));
				});
				$('#actions_'+inId).replaceWith(newDiv);
				$('#actions_'+inId).show();
			}
		},
			error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
		}
	});
}
function ImportActions(idSizRm)
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'ImportActions', 'idDonor':idSizRm, 'idRecepient':idRM},//параметры запроса
		response:'text',
		success:function (data)
		{
			ClickJ($(".structure_active"));
		},
			error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
		}
	});
}

function FastActions()
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'FastActions', 'idRm':idRM},//параметры запроса
		response:'text',
		success:function (data)
		{
			ClickJ($(".structure_active"));
		},
			error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
		}
	});
}

function FastWarranty()
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'FastWarranty', 'idRm':idRM},//параметры запроса
		response:'text',
		success:function (data)
		{
			ClickJ($(".structure_active"));
		},
			error: function (xhr, ajaxOptions, thrownError) {
			alert(xhr.responseText);
		}
	});
}

//Сохранение CИЗ
function ClickSaveSIZ()
{
	var tmpCard = 0;
	if($('#iSIZCard').prop('checked'))
	tmpCard = 1;

	var sRMid = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'saveSIZ', 'idRm': sRMid, 'sSIZbase': $('#sSIZbase').val(), 'dSizDate': $('#dSizDate').val(), 'iSIZCard': tmpCard, 'iSIZEffect': $("input:radio[name ='iSIZOEffect']:checked").val(), 'iSIZOFact': $("input:radio[name ='iSIZOFact']:checked").val(), 'iSIZOProtect': $("input:radio[name ='iSIZOProtect']:checked").val(), 'iSIZOEffect': $("input:radio[name ='iSIZOEffect']:checked").val()},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					//alert(data);
				}
	});
}

//Чтение перечня СИЗ
function ReadSIZ(sIdRm)
{
		$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'ReadSiZs', 'idRm':sIdRm},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						$("#siz_navigation").attr('null','false');
						//alert(data.length);
						jQuery.each(data, function(key, value)
						{
							var newDiv = $(divNewSiz(this[0],this[1], this[2], this[3], this[4]));
							newDiv.attr('style', 'display:yes;');
 							$("#siz_navigation").append(newDiv);
				        });
						$("#siz_navigation").append('<input name="AddSiz" type="submit" class="input_button" id="AddSiz" value="+ Добавить СИЗ" onClick="ClickAddSiz();"/>');
					}
					else
					{
						$("#siz_navigation").attr('null','true');
						$("#siz_navigation").html('<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><div id="NullSiz" class="nowBlock comment" style="margin-bottom:20px;">Средства индивидуальной защиты положенные работнику отсутствуют.</div><input name="AddSiz" type="submit" class="input_button" id="AddSiz" value="+ Добавить СИЗ" onClick="ClickAddSiz();"/></tr></table>');
					}
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

function divNewSiz (sId,sName,sFact,sSert,sProtctFactors)
{
	var sImage = '';
	var sTyle = '';
	if(sFact == 1) {sImage = 'actions16.png'; sTyle = 'structure_factors_actions';} else {sImage = 'actions16w.png'; sTyle = 'structure_factors_actions_warning';}
	if(sName.length == 0) {sName = '&#8212;';}
	if(sSert.length == 0) {sSert = '&#8212;';}
	var sEditButton = '<div class="button8 button8_edit" title="Редактировать СИЗ" onclick="ClickEditSiz('+sId+');">';
	return '<div class="structure_factors '+sTyle+'" style="display:none; margin-left:20px;" id="siz_'+sId+'"><table width="100%%" border="0" cellspacing="0" cellpadding="0"><tr><td width="24"><img src="Grph/actions/'+sImage+'" width="16" height="16" /></td><td class="comment">'+sName+'</td><td class="comment" width="24"></td><td class="comment" width="150">'+sSert+'</td><td width="32" valign="middle">'+sEditButton+'</td><td width="32" valign="middle"><div class="button8 button8_remove" title="Удалить СИЗ" onclick="DelSiz('+sId+');"></div></td></tr></table></div>';
}

//Добавление мероприятия
function ClickAddSiz()
{
	var sRMid = $(".structure_active").attr("id");
	var aPoupupFields = ['tSizName', 'sSert', 'sProtectFactor', 'iFact'];
	var aPoupupFieldsScribe = ['Наименоване СИЗ, или перечень сиз разделенный символами переноса строки','Номер и срок действия сертификата','Фактор от которого обеспечивается защита','Фактическое наличие у работника'];
	var aPoupupFieldsDefoultValue = ['','','','1'];
	var s = 'AddSiz('+sRMid+', $(\'#tSizName\').val(), $(\'#sSert\').val(), $(\'#sProtectFactor\').val(), $(\'#iFact\').prop(\'checked\'))';
	PoupUpMessgeCustomField('Средство индивидуальной защиты','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}

function AddSiz(idRm, sSizName, sSert, sProtectFactor, iFact)
{
	if(!iFact) iFact = 0; else iFact = 1;
	var idGroup =	GetGroupId
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'ajax.php',//url адрес файла обработчика
		data:{'action': 'AddSiz', 'idRm':idRm, 'sSizName':sSizName,'sSert':sSert, 'sProtectFactor': sProtectFactor, 'iFact': iFact},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
		{
			if(data === "!List")
			{
				progressInfo_show();
				ClickSaveSIZ();
				ChangeMessageSave();
				$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'info_navigation_siz.php',//url адрес файла обработчика
				data:{'id': idRm},//параметры запроса
				//dataType: 'json',
				response:'text',
				success:function (data)
				{
					$("#info_navigation").html(data);
					//Подключения инструмента выбора дат
					$("#dSizDate").datepicker({
						showOtherMonths: true,
						selectOtherMonths: true,
						changeMonth: true,
						changeYear: true
					});
					ReadSIZ(idRm);
					progressInfo_hide();
				}
				});
			}
			else
			{
				if($("#siz_navigation").attr('null') == 'true') {$("#siz_navigation").attr('null','false');$("#NullSiz").remove();$("#siz_navigation").html('<input name="AddSiz" type="submit" class="input_button" id="AddSiz" value="+ Добавить СИЗ" onClick="ClickAddSiz();"/>');}
				var newDiv = $(divNewSiz(data,sSizName,iFact,sSert,sProtectFactor));
				$("#AddSiz").before(newDiv);
				newDiv.slideDown();
			}
		}
	});
}
function DelSiz(id)
{
	$.ajax({
			type:'post',//тип запроса: get,post либо head
			url:'ajax.php',//url адрес файла обработчика
			data:{'action': 'DelSiz', 'id':id},//параметры запроса
			//dataType: 'json',
			response:'text',
			success:function (data)
			{
				$("#siz_"+id).slideUp('fast', function() {
				$("#siz_"+id).remove();
				if($(".structure_factors_actions").length == 0){$("#siz_navigation").html('<table height="95%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td align="center" valign="middle"><div id="NullSiz" class="nowBlock comment" style="margin-bottom:20px;">Средства индивидуальной защиты положенные работнику отсутствуют.</div><input name="AddSiz" type="submit" class="input_button" id="AddSiz" value="+ Добавить СИЗ" onClick="ClickAddSiz();"/></tr></table>');$("#siz_navigation").attr('null','true');}
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.responseText);
			}
	});
}
function ClickEditSiz(inId)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'ReadSiZ', 'id':inId},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						jQuery.each(data, function(key, value)
						{
							var aPoupupFields = ['sSizName', 'sSert', 'sProtectFactor', 'iFact'];
							var aPoupupFieldsScribe = ['Наименоване СИЗ','Номер и срок действия сертификата','Фактор от которого обеспечивается защита','Фактическое наличие у работника'];
							var aPoupupFieldsDefoultValue = [this[1],this[3],this[4],this[2]];
							var s = 'EditSiz('+inId+', $(\'#sSizName\').val(), $(\'#sSert\').val(), $(\'#sProtectFactor\').val(),$(\'#iFact\').prop(\'checked\'))';
							PoupUpMessgeCustomField('Средство индивидуальной защиты','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
						});
					}
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

function EditSiz(idSiz, sSizName, sSert, sProtectFactor, iFact)
{
	if(!iFact) iFact = 0; else iFact = 1;
	var idRM = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'EditSiz', 'idSiz':idSiz, 'sSizName':sSizName, 'sSert':sSert, 'sProtectFactor':sProtectFactor, 'iFact':iFact},//параметры запроса
				dataType: 'json',
				response:'text',
				success:function (data)
				{
					if (data != null)
					{
						var newDiv = '';
						jQuery.each(data, function(key, value)
						{
							newDiv = $(divNewSiz(this[0],this[1], this[2], this[3], this[4]));
				        });
						$('#siz_'+idSiz).replaceWith(newDiv);
						$('#siz_'+idSiz).show();
					}
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

function ImportSiz(idSizRm)
{
	var idRM = $(".structure_active").attr("id");
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'ImportSiz', 'idDonor':idSizRm, 'idRecepient':idRM},//параметры запроса
				response:'text',
				success:function (data)
				{
					ClickJ($(".structure_active"));
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}
function SetAllCreateDate()
{
	var dDate = new Date();
	var sDateNow =  dDate.getDate() + "." + dDate.getMonth() + "." + dDate.getFullYear();
	var sRMid = $(".structure_active").attr("id");
	var aPoupupFields = ['dNewDateCreate','dNewDateControl','dNewDateSiz'];
	var aPoupupFieldsScribe = ['Дата составления карт','Дата проведения измерений','Дата проведения оценки СИЗ'];
	var aPoupupFieldsDefoultValue = ['Не изменять','Не изменять','Не изменять'];
	var s = 'SetAllCreateDateBd('+GetGroupId()+', $(\'#dNewDateCreate\').val(), $(\'#dNewDateControl\').val(), $(\'#dNewDateSiz\').val())';
	PoupUpMessgeCustomField('Даты&nbsp;измерений&nbsp;и&nbsp;составления&nbsp;карт','',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}
function SetAllCreateDateBd(inIdGroup, dDateCreate, dDateControl, dNewDateSiz)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'SetAllCreateDate', 'inIdGroup':inIdGroup, 'dDateCreate':dDateCreate, 'dDateControl':dDateControl, 'dNewDateSiz':dNewDateSiz},//параметры запроса
				response:'text',
				success:function (data)
				{
					ClickJ($(".structure_active"));
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}
function SetAllCreateAction()
{
	var s = 'GoSetAllCreateAction()';
	PoupUpReqest('Рекомендации и подбор работников', 'Сейчас мы проведем анализ и добавим рекомендации по улучшению условий труда для всех рабочих мест этой группы данных. Продолжаем?',s);
}
function GoSetAllCreateAction()
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'SetAllCreateAction', 'inIdGroup':GetGroupId()},//параметры запроса
				response:'text',
				success:function (data)
				{
					ClickJ($(".structure_active"));
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}
function SetAllCreateWarranty()
{
	var s = 'GoSetAllCreateWarranty()';
	PoupUpReqest('Гарантии и компенсации', 'Сейчас мы проведем анализ и добавим рекомендации по установлению гарантий и компенсаций для всех рабочих мест этой группы данных. Продолжаем?',s);
}
function GoSetAllCreateWarranty()
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'SetAllCreateWarranty', 'inIdGroup':GetGroupId()},//параметры запроса
				response:'text',
				success:function (data)
				{
					ClickJ($(".structure_active"));
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}
function SetAllAsset()
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'SetAllAsset', 'inIdGroup':GetGroupId()},//параметры запроса
				response:'text',
				success:function (data)
				{
					$("#poupup_all_layout").slideUp();
					ClickJ($(".structure_active"));
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}
function SetAllNums()
{
	var s = 'GoSetAllNums(sPoupUpReqestString)';
	PoupUpReqestString('Автоматическая нумерация рабчоих мест', 'Сейчас мы пронумеруем все рабочие номера структуры, начиная с...',s,'1');
}
function GoSetAllNums(sFirstNum)
{
	$.ajax({
				type:'post',//тип запроса: get,post либо head
				url:'ajax.php',//url адрес файла обработчика
				data:{'action': 'SetAllNums', 'sFirstNum':sFirstNum, 'inIdGroup':GetGroupId()},//параметры запроса
				response:'text',
				success:function (data)
				{
					location.reload();
				},
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText);
				}
	});
}

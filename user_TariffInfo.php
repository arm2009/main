<?php
	include_once('UserControl/userControl.php');
	include_once('UserControl/userTariff.php');
	include_once('aj.tariff.php');
	include_once "MainWork/GroupWork.php";

    UserControl::isUserValidExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>
<body><? include('Frame/frame_Top.php'); ?><? include_once('Frame/frame_PoupUp.php'); ?>
<?
	if (UserTariff::GetTariffName() != 'Base' && UserTariff::GetTariffName() != 'BetaTest')
	{
		$sDateEnd = '<br />Действует до '.UserTariff::GetTariffEndDateFullString();
	}
	else
	{
		if(UserTariff::GetTariffName() == 'BetaTest')
		{
		$sDateEnd = '<br />До 15 августа 2014 года.';
		}
		else
		{
			$sDateEnd = '';
		}
	}
	$iSoWorkersNum = UserTariff::GetTariffSoWorkers();
	$sTarifName = UserTariff::GetUserTariffNameRus();
?>
<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left"><h1 class="white">Совместная работа</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);"><table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
      <tr>
        <td>
        <div id="Check_Bl_2">

<?
if ($iSoWorkersNum == 0) {echo '<div class="nowBlock" id="firstSoworker">Совместная работа &#8212; это возможность разделить удобство и функциональность АРМ&nbsp;2009 с партнерами и коллегами, объединив их в Вашем рабочем пространстве и Вашей организации проводящей СОУТ. Организуйте оперативный совместный доступ к Вашим данным в любом месте и в любое время в несколько простых действий для более эффективной работы.<br /><br /><a href="user_Tariff.php">Для начала совместной работы необходимо улучшить Ваш тариф...</a></div>';}
else
{
	$sDisplayfirst = 'none;';
	$sDisplayButton = 'none;';
	$sDisplayLast = 'none;';
	if (UserTariff::IsCanAddSoWorkers()=='true')
	{
		$sDisplayButton = 'yes;';
	}
	else
	{
		$sDisplayLast = 'yes;';
	}
	if(UserTariff::GetSoWorkersCount() == 0)
	{
		$sDisplayfirst = 'yes;';
	}
	echo('<div class="nowBlock" id="firstSoworker" style="display:'.$sDisplayfirst.';">Совместная работа &#8212; это возможность разделить удобство и функциональность АРМ&nbsp;2009 с партнерами и коллегами, объединив их в Вашем рабочем пространстве и Вашей организации проводящей СОУТ. Организуйте оперативный совместный доступ к Вашим данным в любом месте и в любое время в несколько простых действий для более эффективной работы.</div>
<div class="nowBlock" id="lastSoworker" style="display:'.$sDisplayLast.'">Упс. Возможности этого тарифа для совместной работы исчерпаны?<br />Возможно стоит рассмотреть другой тариф?<br /><br /><a href="user_Tariff.php">Улучшить Ваш тариф...</a></div>
<div style = "display:'.$sDisplayButton.';" class="block block_left_round block_right_round block_add pointer" id="buttonAdd" onclick="addUserClick()">Пригласить пользователя к совместной работе - доступно приглашений: <div style="display:inline" id="numSoWorkers">'.($iSoWorkersNum - UserTariff::GetSoWorkersCount()).'</div></div>');
	echo userTariffPage::getDivs();

	/*<?php echo UserTariff::GetSoWorkersCount(); ?></div> из <?php echo $iSoWorkersNum; ?>*/
}

//Представление текущих доступных групп
/*$aNamesGr = GroupWork::FillWorkSpace();
if(count($aNamesGr)>1)
{
	for ($iCnt = 0 ; $iCnt < count($aNamesGr); $iCnt++)
	{
	}
}*/
?>
	</div>
	</td>

      </tr>
    </table></td>
  </tr>
</table>
<?
/*Установка нижнего фрейма*/
include('Frame/frame_Bottom.php');
?>
<script>
$(document).ready(function(e) {
	$(document).tooltip({track: true, show: {
    effect: "fade",
    delay: 500
  }});
});

var SoSoworker = <? echo($iSoWorkersNum); ?>;
function refreshNumSoWorkers()
{

		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.tariff.php',//url адрес файла обработчика
		data:{'action':'refresh'},//параметры запроса
		//dataType: 'json',
		response:'text',
		success:function (data)
		{
			if(data == 0 )
			{ $('#firstSoworker').slideDown(); }
			else
			{
				 $('#firstSoworker').slideUp();
				 if((SoSoworker - data) == 0)
				 {
					 $('#lastSoworker').slideDown();
				 }
				 else
				 {
					 $('#lastSoworker').slideUp();
				 }
			}
			$("#numSoWorkers").html(SoSoworker - data);
		}
	});

}


//Функция для привязки к диву
function delDiv(tobject, sType)
{
				var value =	tobject.getAttribute('value')
				var object = $(tobject);
				var dataObj = sType+"="+value
					$.ajax({
						type:'post',
						url:'aj.tariff.php',
						data:dataObj,
						response:'text',
						success:function (data)
							{
								if (data == 'true')
								{
									$("#buttonAdd").slideDown();
								}
							}
				});
				object.slideUp();
}

//Функция на событие клика по диву с приглашением
function deleteDivEmail(tobject){
				delDiv(tobject, 'emailDel');
				refreshNumSoWorkers();
			}

//Функция на событие клика по диву с соворкером
function deleteDivUser(tobject){
				delDiv(tobject, 'userDel');
				refreshNumSoWorkers();
			}

//Нажатие по кнопке добавление соворкера
function addUserClick()
{
	PoupUpReqestString("Пригласить пользователя", "Введите электронную почту пользователя которого вы хотите пригласить:", "addUser(sPoupUpReqestString)", "");
}

//Добавление соворкера по email
function addUser(email)
{
	//Функция основной кнопки
	var selfMail;

	$.ajax({
		type:'post', url:'aj.tariff.php',data:{'checkLogin':'true'}, response:'text',success: function(data)//Прочитать свой емейл для сравнения
		{
			selfMail = data;

	if (IsInputValidEmailValue(email) && email.toLowerCase() != selfMail)
	{
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.tariff.php',//url адрес файла обработчика
		data:{'emailAdd':email},//параметры запроса
		dataType: 'json',
		response:'text',
		success:function (data)
			{//возвращаемый результат от сервера
				var newAdd;
				var delName = 'emailDel';//Использовать за подстановку в запрос пост.
				if (data.email != null)
				{
					//num = num + 1;
					newAdd = $(data.email);
					newAdd.insertAfter('#buttonAdd');
					refreshNumSoWorkers();
					delName = 'emailDel';
				}
				if (data.user != null)
				{
					//num = num + 1;
					newAdd = $(data.user);
					newAdd.insertAfter('#buttonAdd');
					refreshNumSoWorkers();
					delName = 'userDel';
				}

				if (data.error != null)
				{
					if (data.error == 'exist') {PoupUpMessge("Упс", email+" уже приглашен.");}
				}
				else
				{
					newAdd.slideDown();
				}

				if (data.full != null)
				{
					$("#buttonAdd").slideUp();
				}
			}
		});
	} else { if (email != '') {PoupUpMessge("Упс.","Почтовый адресс введен не верно.");} }
		}
	});
}

</script>
</body>
</html>

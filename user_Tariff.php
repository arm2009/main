<?php
	include_once('UserControl/userControl.php');

    UserControl::isUserValidExit();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>

</head>
<body onload="setprice();"><? include('Frame/frame_Top.php'); ?><? include_once('Frame/frame_PoupUp.php'); ?>
<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left"><h1 class="white">Изменение тарифного плана</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
    <form name="f1" id="f1" action="user_Tariff_Pay.php" method="post" onsubmit="return IsFormValidate();">
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
        <tr>
          <td><h1>Новый тариф</h1></td>
        </tr>
        <tr>
          <td><p><table width="100%" border="0" cellspacing="0" cellpadding="10" class="table_round_corner shawdow_min">
            <tr class="table_odd pointer" onclick="setfocus(1,1); setprice();">
              <td width="48" class="table_round_corner_LU"><input type="radio" name="RadioGroup1" value="Pers" id="RadioGroup1_1" checked="checked"/></td>
              <td>Персональный<br />
              <span class="comment">Без возможности совместной работы</span></td>
              <td width="25%" align="right" class="table_round_corner_RU">210 руб / мес</td>
              </tr>
            <tr class="table_even pointer" onclick="setfocus(1,2); setprice();">
              <td width="48"><input type="radio" name="RadioGroup1" value="Coop" id="RadioGroup1_2" /></td>
              <td>Совместный<br />
              <span class="comment">Совместная работа 5 пользователей</span></td>
              <td width="25%" align="right">1000 руб / мес</td>
              </tr>
            <tr class="table_odd pointer" onclick="setfocus(1,3); setprice();">
              <td width="48"><input type="radio" name="RadioGroup1" value="Corp" id="RadioGroup1_3" /></td>
              <td>Корпоративный<br /><span class="comment">Совместная работа 10 пользователей</span></td>
              <td width="25%" align="right">1900 руб / мес</td>
              </tr>
            <tr class="table_even pointer" onclick="setfocus(1,4); setprice();">
              <td width="48" class="table_round_corner_LB"><input type="radio" name="RadioGroup1" value="Net" id="RadioGroup1_4" /></td>
              <td>Сетевой<br />
                <span class="comment">Совместная работа без ограничений</span></td>
              <td width="25%" align="right" class="table_round_corner_RB">4500 руб / мес</td>
              </tr>
            </table></p></td>
        </tr>
        <tr>
          <td><h1>Период  действия</h1></td>
        </tr>
        <tr>
          <td>
            <p><table width="100%" border="0" cellspacing="0" cellpadding="10" class="table_round_corner shawdow_min">
              <tr class="table_odd pointer" onclick="setfocus(2,1);">
                <td width="48" class="table_round_corner_LU"><input type="radio" name="RadioGroup2" value="1" id="RadioGroup2_1" checked="checked"/></td>
                <td>1 месяц</td>
                <td align="right" class="table_round_corner_RU"><div id="1" style="display:none;"></div></td>
                </tr>
              <tr class="table_even pointer" onclick="setfocus(2,2);">
                <td width="48"><input type="radio" name="RadioGroup2" value="3" id="RadioGroup2_2" /></td>
                <td>3 месяца</td>
                <td align="right"><div id="3" style="display:none;"></div></td>
                </tr>
              <tr class="table_odd pointer" onclick="setfocus(2,3);">
                <td width="48"><input type="radio" name="RadioGroup2" value="6" id="RadioGroup2_3" /></td>
                <td>6 месяцев</td>
                <td align="right"><div id="6" style="display:none;"></div></td>
                </tr>
              <tr class="table_even pointer" onclick="setfocus(2,4);">
                <td width="48" class="table_round_corner_LB"><input type="radio" name="RadioGroup2" value="12" id="RadioGroup2_4" /></td>
                <td>1 год</td>
                <td align="right" class="table_round_corner_RB"><div id="1y" style="display:none;"></div></td>
                </tr>
              </table></p>
              <div class="comment" id="com2" style="display:none;">* &#8212; С учетом текущего тарифа.<br />** &#8212; С учетом текущего тарифа и 5% скидки.</div>
              <div class="comment" id="com1" style="display:none;">* &#8212; С учетом 5% скидки.</div>
            </td>
        </tr>
            <tr>
              <td><div id="paymethod" style="display:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr class="blockmargin">
                  <td><h1>Форма оплаты</h1></td>
                </tr>
                <tr class="blockmargin">
                  <td><p>
                    <table width="100%" border="0" cellspacing="0" cellpadding="10" class="table_round_corner shawdow_min">
                      <tr class="table_odd pointer" onclick="setfocus(3,3);">
                        <td width="48" class="table_round_corner_LU"><input type="radio" name="RadioGroup3" value="BankPay" id="RadioGroup3_3" checked="checked"/></td>
                        <td class="table_round_corner_RU"><i class="fa fa-university"></i> Банковский перевод<br />
                          <span class="comment">Формирование счета на оплату  для юридических лиц</span></td>
                      </tr>
                      <tr class="table_odd pointer" onclick="setfocus(3,1);">
                        <td width="48"><input type="radio" name="RadioGroup3" value="Card" id="RadioGroup3_1"/></td>
                        <td class="table_round_corner_RB"><i class="fa fa-cc-visa"></i> Банковская карта<br />
                            <span class="comment"> Оплата с использованием банковских карт</span></td>
                      </tr>

                    </table></p>
				  </td>
                </tr>
              </table></div></td>
            </tr>
            <tr>
              <td>
              <div id="endprice" style="display:none;">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr class="blockmargin">
                    <td><h1>Реквизиты для выставления счета</h1></td>
                  </tr>
                  <tr class="blockmargin">
                    <td>Название организации<br />
                      <input name="sOrgName" type="text" class="input_field input_field_715 input_field_background" id="sOrgName" value="<?php echo UserControl::GetUserFieldValue('sOrgName'); ?>"/></td>
                  </tr>
                  <tr class="blockmargin">
                    <td>Адрес<br />
                      <input name="sAdress" type="text" class="input_field input_field_715 input_field_background" id="sAdress" value="<?php echo UserControl::GetUserFieldValue('sOrgPlace'); ?>"/></td>
                  </tr>
                  <tr class="blockmargin">
                    <td>ИНН<br />
                      <input name="sInn" type="text" class="input_field input_field_715 input_field_background" id="sInn"  value="<?php echo UserControl::GetUserFieldValue('sOrgInn'); ?>"/></td>
                  </tr>
                  <tr class="blockmargin">
                    <td>КПП<br />
                      <input name="sKpp" type="text" class="input_field input_field_715 input_field_background" id="sKpp" /></td>
                  </tr>
                  <tr class="blockmargin">
                    <td>Банк<br />
                      <input name="sBank" type="text" class="input_field input_field_715 input_field_background" id="sBank" /></td>
                  </tr>
                  <tr class="blockmargin">
                    <td>БИК<br />
                      <input name="sBik" type="text" class="input_field input_field_715 input_field_background" id="sBik" /></td>
                  </tr>
                </table>
              </div>
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="1px" bgcolor="#0099CC"></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <td>
 <input name="button" type="submit" class="input_button" id="button" value="Продолжить"/> 

</td>
            </tr>        
      </table>
    </form></td>
  </tr>
</table>
<? 
/*Установка нижнего фрейма*/
include('Frame/frame_Bottom.php');
?>
<script>
/*Место для скриптов*/
var nowprice = <? echo(UserTariff::GetTariffMoneys()); ?>;

function setfocus(innum, incheck)
{
	$("input[name=RadioGroup"+innum+"][id=RadioGroup"+innum+"_" + incheck + "]").prop('checked', true);
	$("input[name=RadioGroup"+innum+"][id=RadioGroup"+innum+"_" + incheck + "]").attr('checked', 'checked');

	if(innum == 3 && incheck == 3)
	{
		$("#endprice").slideDown();
		$("#f1").attr('action', 'user_Tariff_Pay.php');
	}
	else
	{
		if(innum == 3){$("#endprice").slideUp();}
	}
	
	if(innum == 3 && incheck == 1)
	{
		$("#f1").attr('action', 'user_Tariff_PayU.php');
	}
	
	if(innum == 1 || innum == 2)
	{
		repay();
	}
}

function setprice()
{
	var innum = $("input:radio[name ='RadioGroup1']:checked").val();
	
	switch (innum)
	{
		case 'Pers':
		innum = 210;
		break;
		case 'Coop':
		innum = 1000;
		break;
		case 'Corp':
		innum = 1900;
		break;
		case 'Net':
		innum = 4500;
		break;
	}
	
	$("#1").fadeOut();
	$("#3").fadeOut();
	$("#6").fadeOut();
	$("#1y").fadeOut("slow", function() {
		
		if(nowprice==0)
		{
			/*Пересчет без скидок*/
			$("#com1").show();
			$("#1").html(innum + " руб");
			$("#3").html(innum*3 + " руб");
			$("#6").html(innum*6 + " руб");
			$("#1y").html("<h1>"+(innum*12*0.95)+" руб*</h1>");
			$("#paymethod").show();
			$("#endprice").show();
		}
		else
		{
			/*Пересчет со скидкой*/
			$("#com2").show();
			$("#1").html(reprice(innum) + "*");
			$("#3").html(reprice(innum*3) + "*");
			$("#6").html(reprice(innum*6) + "*");
			$("#1y").html("<h1>"+reprice(innum*12*0.95)+"**</h1>");
			repay();
		}

		$("#1").fadeIn();
		$("#3").fadeIn();
		$("#6").fadeIn();
		$("#1y").fadeIn();
	});
}
function repay()
{
	/*Необходимость оплаты*/
	var Iname = '';
	switch ($("input:radio[name ='RadioGroup2']:checked").val())
	{
		case '1':
		Iname = '#1';
		break;
		case '3':
		Iname = '#3';
		break;
		case '6':
		Iname = '#6';
		break;
		case '12':
		Iname = '#1y';
		break;
	}
	if($(Iname).html().indexOf('Бесплатно')>-1)
	{
		$("#paymethod").slideUp();
		$("#endprice").slideUp();
	}
	else
	{
		$("#paymethod").slideDown();
		if($("input:radio[name ='RadioGroup3']:checked").val() == 'BankPay' && !$("#endprice").is(':visible'))
		$("#endprice").show();
	}	
}
function reprice(iInSum)
{
	if(iInSum-nowprice > 0)
		return (iInSum-nowprice)+" руб.";
		else
		return "Бесплатно";
}
function IsFormValidate()
{
	
	if($("input:radio[name ='RadioGroup3']:checked").val() == 'BankPay' && $("#paymethod").is(':visible'))
	{
		var sErrHeader = 'Недостаточно информации';
		var sErrReport = 'Для корректного выставления счета необходимо указать все реквизиты';
		
		IsInputValidNotNull('#sOrgName');
		IsInputValidNotNull('#sAdress');
		IsInputValidNotNull('#sInn');
		IsInputValidNotNull('#sKpp');
		IsInputValidNotNull('#sBank');
		IsInputValidNotNull('#sBik');
		
		if(bInputValidError)
		{
			SetInputValidFocusOnFirstErrorInput();
			SetInputValidDefaultParams();
			return false;
		}
		else
		return true;
	}
	else
	{
		return true;
	}
}
</script>
</body>
</html>

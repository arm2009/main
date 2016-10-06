<?
	include_once 'UserControl/userControl.php';
	include_once 'UserControl/userTariff.php';
	include_once 'LowLevel/userValidator.php';
	include_once('LowLevel/emailSend.php');

	UserControl::isUserValidExit();
	
	//Вывод информации о выбранных параметрах
	if(isset($_POST[RadioGroup1]))
	{
		$sTarif = '';
		$sTime = '';
		$iSum = 0;
		$sSum = '';
		switch($_POST[RadioGroup1])
		{
			case Pers:
				$sTarif .= 'Персональный тариф<br /><span class="comment">Без возможности совместной работы</span>';
				$iSum = round($_POST[RadioGroup2] * 210 - UserTariff::GetTariffMoneys(),0);
				//Временная подпорка для теста
				//UserTariff::ChangeTariffData($_POST[RadioGroup2], 210, $_POST[RadioGroup1], 0);
			break;
			case Coop:
				$sTarif .= 'Совместный тариф<br /><span class="comment">Совместная работа 5 пользователей</span>';
				$iSum = round($_POST[RadioGroup2] * 1000 - UserTariff::GetTariffMoneys(),0);
				//UserTariff::ChangeTariffData($_POST[RadioGroup2], 1000, $_POST[RadioGroup1], 5);
			break;
			case Corp:
				$sTarif .= 'Корпоративный тариф<br /><span class="comment">Совместная работа 10 пользователей</span>';
				$iSum = round($_POST[RadioGroup2] * 1900 - UserTariff::GetTariffMoneys(),0);
				//UserTariff::ChangeTariffData($_POST[RadioGroup2], 1900, $_POST[RadioGroup1], 10);
			break;
			case Net:
				$sTarif .= 'Сетевой тариф<br /><span class="comment">Совместная работа без ограничений</span>';
				$iSum = round($_POST[RadioGroup2] * 4500 - UserTariff::GetTariffMoneys(),0);
				//UserTariff::ChangeTariffData($_POST[RadioGroup2], 4500, $_POST[RadioGroup1], 100);
			break;									
		}
		
		switch($_POST[RadioGroup2])
		{
			case 1:
				$sTime .= $_POST[RadioGroup2].' месяц';
				$sSum = $iSum .' рублей';
			break;			
			case 3:
				$sTime .= $_POST[RadioGroup2].' месяца';
				$sSum = $iSum .' рублей';
			break;			
			case 6:
				$sTime .= $_POST[RadioGroup2].' месяцев';
				$sSum = $iSum .' рублей';
			break;
			case 12:
				$sTime .= '1 год';
				$sSum = $iSum * 0.95 .' рублей';
				$iSum = $iSum * 0.95;
			break;									
		}
		
		
		if($iSum > 0)
		{
			//Оплата
			if($_POST[RadioGroup3] == 'BankPay')
			{
				//Банковский перевод
				$style1 = ' style="display:none;"';
				$style3 = ' style="display:none;"';
				
				//Сохранение информации о платеже
				$_POST[sOrgName] = DbConnect::ToBaseStr($_POST[sOrgName]);
				$_POST[sAdress] = DbConnect::ToBaseStr($_POST[sAdress]);
				$_POST[sInn] = DbConnect::ToBaseStr($_POST[sInn]);
				$_POST[sKpp] = DbConnect::ToBaseStr($_POST[sKpp]);
				$_POST[sBank] = DbConnect::ToBaseStr($_POST[sBank]);
				$_POST[sBik] = DbConnect::ToBaseStr($_POST[sBik]);
				$sql = "INSERT INTO `kctrud_arm2009`.`Arm_PayOut` (`iNum`, `dtStamp`, `iState`, `sTarif`, `iMonth`, `sOrgName`, `sAdress`, `sInn`, `sKpp`, `sBank`, `sBik`, `iSum`, `iUserId`)
				VALUES (NULL, NOW(), '0', '".$_POST[RadioGroup1]."', '".$_POST[RadioGroup2]."', '".$_POST[sOrgName]."', '".$_POST[sAdress]."', '".$_POST[sInn]."', '".$_POST[sKpp]."', '".$_POST[sBank]."', '".$_POST[sBik]."', ".round($iSum).", ".UserControl::GetUserLoginId().");";
				$vResult = DbConnect::GetSqlQuery($sql);
				$iInsertedKey = mysql_insert_id();
				DbConnect::Log("Создан счёт № ".$iInsertedKey, "pay_bank_create");
				$iInsertedKeyCrypt = ($iInsertedKey*1255);
			}
			else
			{
				//Пластиковые карты и платежные системы
				$style2 = ' style="display:none;"';
				$style3 = ' style="display:none;"';	
			}
		}
		else
		{
			//Установка бесплатного тарифа
			$style1 = ' style="display:none;"';
			$style2 = ' style="display:none;"';
			
				//Сохранение информации о платеже
				$iSum = $iSum + UserTariff::GetTariffMoneys();
				$_POST[sOrgName] = DbConnect::ToBaseStr($_POST[sOrgName]);
				$_POST[sAdress] = DbConnect::ToBaseStr($_POST[sAdress]);
				$_POST[sInn] = DbConnect::ToBaseStr($_POST[sInn]);
				$_POST[sKpp] = DbConnect::ToBaseStr($_POST[sKpp]);
				$_POST[sBank] = DbConnect::ToBaseStr($_POST[sBank]);
				$_POST[sBik] = DbConnect::ToBaseStr($_POST[sBik]);
				$sql = "INSERT INTO `kctrud_arm2009`.`Arm_PayOut` (`iNum`, `dtStamp`, `iState`, `sTarif`, `iMonth`, `sOrgName`, `sAdress`, `sInn`, `sKpp`, `sBank`, `sBik`, `iSum`, `iUserId`)
				VALUES (NULL, NOW(), '1', '".$_POST[RadioGroup1]."', '".$_POST[RadioGroup2]."', '".$_POST[sOrgName]."', '".$_POST[sAdress]."', '".$_POST[sInn]."', '".$_POST[sKpp]."', '".$_POST[sBank]."', '".$_POST[sBik]."', ".round($iSum).", ".UserControl::GetUserLoginId().");";
				$vResult = DbConnect::GetSqlQuery($sql);
				$iInsertedKey = mysql_insert_id();
				DbConnect::Log("Создана заявка на изменение тарифа № ".$iInsertedKey, "pay_bank_create");
		}
	}
	
	Email::CommunicationNewmail('mail@kctrud.ru', 'АРМ 2009 | Выставлен новый счет', 'Здравствуйте!<br /><br />В системме АРМ 2009 выставлен новый счет на оплату.<br /><br />Пожалуйста, проигнорируйте данное письмо, если оно попало к Вам по ошибке.');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>

<body><? include('Frame/frame_Top.php'); ?>
<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left" class="white"><h1>Оплата выбранного тарифного плана</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
    <form action="user_Tariff_PayCr.php?pay=<? if(isset($iInsertedKeyCrypt) && !empty($iInsertedKeyCrypt))echo($iInsertedKeyCrypt); ?>" method="post" target="_blank"<? echo($style2); ?>>
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
        <tr>
          <td><h1>К оплате <? echo($sSum); ?></h1></td>
        </tr>
        <tr>
			<td class="nowBlock">
            
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? echo($sTarif); ?></td>
    <td align="right"><? echo($sTime); ?></td>
  </tr>
</table></td>
        </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Вам выставлен счет с номером <? if(isset($iInsertedKey) && !empty($iInsertedKey))echo($iInsertedKey); ?>/АРМ от 25.02.2014.<br />                
                Оригинал счета будет выслан по почте на указанный адрес.<br />
                Вы можете распечатать копию счета в формате PDF.<br />
                <br />
                <span class="comment">Выбранный тарифный план будет активирован после поступления денежных средств на расчетный счет<br />
                Консалтингового центра и получения подтверждающих платежных документов из банка.</span></td>
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
            <td><input name="button" type="submit" class="input_button" id="button" value="Распечатать копию счета в формате PDF" onclick="gotourl();"/></td>
            </tr>        
      </table>
    </form>
    
    <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin" <? echo($style1); ?>>
        <tr>
          <td><h1>К оплате <? echo($sSum); ?></h1></td>
        </tr>
        <tr>
			<td class="nowBlock">
  
  
<? //Таблица текста для платежа по карте ?>    
  
<form action="https://money.yandex.ru/eshop.xml" method="post"> 

<!-- Обязательные поля -->
<input name="shopId" value="" type="hidden"/>
<input name="scid" value="" type="hidden"/>
<input name="sum" value="<? echo($sSum); ?>" type="hidden">
<input name="customerNumber" value="<? echo (UserControl::GetUserLoginId()); ?>" type="hidden"/>

<!-- Необязательные поля -->
<input name="shopArticleId" value="567890" type="hidden"/>
<input name="paymentType" value="AC" type="hidden"/>
<input name="orderNumber" value="abc1111111" type="hidden"/>
<input name="cps_phone" value="79110000000" type="hidden"/>
<input name="cps_email" value="user@domain.com" type="hidden"/>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><? echo($sTarif); ?></td>
    <td align="right"><? echo($sTime); ?></td>
  </tr>
</table></td>
        </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Вы можете перейти к оплате на странице процессингового центра.<br />
                <br />
                <span class="comment">Выбранный тарифный план будет активирован после поступления денежных средств на расчетный счет<br />
              Консалтингового центра и получения подтверждения платежа процессинговым центром.</span></td>
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
            <input name="button" type="submit" class="input_button" id="button" value="Перейти к оплате" onclick="gotourl();"/>
              </td>
            </tr>        
      </table>
</form>
      
      
    <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin" <? echo($style3); ?>>
      <tr>
        <td><h1>Бесплатно</h1></td>
      </tr>
      <tr>
        <td class="nowBlock"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><? echo($sTarif); ?></td>
            <td align="right"><? echo($sTime); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Выбранный тарифный план будет установлен после одобрения администратором.</td>
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
      <tr>
        <td><input name="button2" type="submit" class="input_button" id="button2" value="Вернутся на главную страницу" onclick="gotourl();"/></td>
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
/*Место для скриптов*/
function gotourl()
{
	setTimeout(function(){window.location = 'index.php';}, 1000);
}
</script>
</body>
</html>
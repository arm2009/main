<?
	include_once 'UserControl/userControl.php';
	include_once 'UserControl/userTariff.php';
	include_once 'LowLevel/userValidator.php';
	include_once('LowLevel/emailSend.php');
	include_once "Payu/PayU.cls.php";

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
	}

	
	
	
	//Формируем форму
$option  = array( 'merchant' => 'armndkma', 
                  'secretkey' => 'Fw16k2^1S5?@K6?!h9|n'
                );
$forSend = array (
      #'ORDER_REF' => $orderID, # Ордер. Если не указывать - создастся автоматически
      #'ORDER_DATE' => date("Y-m-d H:i:s"), # Дата платежа ( Y-m-d H:i:s ). Необязательный параметр.
      'ORDER_PNAME' => array( 'Использование системы ARM2009.'), # Массив с названиями товаров
      'ORDER_PCODE' => array( $_POST[RadioGroup1].';'.$_POST[RadioGroup2]), # Массив с кодами товаров
      'ORDER_PRICE' => array( $iSum), # Массив с ценами
      'ORDER_QTY' => array( 1),  # Массив с колличеством каждого товара
      'ORDER_VAT' => array( 0),  # Массив с указанием НДС для каждого товара
      'ORDER_SHIPPING' => 0 , # Стоимость доставки
      'PRICES_CURRENCY' => "RUB",  # Валюта мерчанта (Внимание! Должно соответствовать валюте мерчанта. )
	'TESTORDER' => 'TRUE',
      'LANGUAGE' => "RU",  
      'BILL_EMAIL' => $uemail,
	  'BILL_COUNTRYCODE' => "RU",
	  'BACK_REF' => 'http://arm2009.ru/work_Space.php?pay=1',
	  'ORDER_REF' => UserControl::GetUserLoginId()
	
      #.. все остальные параметры
      );
	  			
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
              <td>Вы можете перейти к оплате на странице процессингового центра.
                <br />
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
            <td>      <? $pay = PayU::getInst()->setOptions( $option )->setData( $forSend )->LU();
echo $pay;
?></td>
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
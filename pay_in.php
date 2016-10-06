<?
	include_once 'UserControl/userControl.php';
	include_once 'UserControl/userTariff.php';
	include_once 'LowLevel/userValidator.php';
	include_once('LowLevel/emailSend.php');
	include_once "Payu/PayU.cls.php";
$option  = array( 'merchant' => 'armndkma', 
                  'secretkey' => 'Fw16k2^1S5?@K6?!h9|n', 
                  'debug' => 1
                );

//Сообщение в логи
foreach ($_POST as $id=>$value){
	$tmpstr .= '['.$id.'] = ' .$value .'; ';
}

//Пополнение счета
if(isset($_POST[ORDERSTATUS]))
{	
	switch($_POST[ORDERSTATUS])
	{
		//Тестирование
		case 'TEST':		
			//Добавление и уведомление

			//Почтовый адрес пользователя
			$sUserEmail = UserControl::GetUserFieldValueFromId('sName',$_POST[REFNOEXT]);
			
			//Сообщение в логи		
			$aTariffCode = explode(';', $_POST[IPN_PCODE][0]);
			DbConnect::Log($aTariffCode[1].'|'.$_POST[IPN_TOTALGENERAL].'|'.$aTariffCode[0].'|'.$_POST[REFNOEXT], 'pay_tariff');
			if ($_POST[REFNOEXT] == '6')
			{
				UserTariff::ChangeTariff($aTariffCode[1],$_POST[IPN_TOTALGENERAL], $aTariffCode[0],$_POST[REFNOEXT]);
			}
			DbConnect::Log($_POST[ORDERSTATUS].$_POST[REFNOEXT].';'.$_POST[IPN_TOTALGENERAL].';'.$_POST[REFNOEXT].'|'.$aTariffCode[0].';'.$aTariffCode[1], 'pay_plus');
			
			//Отправка сообщения пользователю
			Email::CommunicationNewmail($sUserEmail, 'ARM2009 - оплата тарифа.', '<p><span class="TextHeaderSmall" style="border-radius: 10px 10px 0 0;">Поступление средств на личный счет</span></p>
			<p>На Ваш личный счет в системе ARM2009 поступили средства в размере ('.$_POST[IPN_TOTALGENERAL].' руб.).<br />
			Спасибо за то, что Вы выбрали нас! </p>');	
						
			//Подтверждение транзакции
			$forSend = array (
			'ORDER_REF' => $_POST[REFNO],
			'ORDER_AMOUNT' => $_POST[IPN_TOTALGENERAL],
			'ORDER_CURRENCY' => "RUB"  # Валюта мерчанта (Внимание! Должно соответствовать валюте мерчанта. )
			);					
			$pay = PayU::getInst()->setOptions($option)->setData($forSend)->IDN();

			DbConnect::Log($_POST[REFNOEXT], 'pay_plus_payu_autorized');
		break;
		//Авторизация
		case 'PAYMENT_AUTHORIZED':
			//Добавление и уведомление

			//Почтовый адрес пользователя
			$sUserEmail = UserControl::GetUserFieldValueFromId('sName',$_POST[REFNOEXT]);
			
			//Сообщение в логи		
			$aTariffCode = explode(';', $_POST[IPN_PCODE][0]);
			DbConnect::Log($aTariffCode[1].'|'.$_POST[IPN_TOTALGENERAL].'|'.$aTariffCode[0].'|'.$_POST[REFNOEXT], 'pay_tariff');
			UserTariff::ChangeTariff($aTariffCode[1],$_POST[IPN_TOTALGENERAL], $aTariffCode[0],$_POST[REFNOEXT]);
			DbConnect::Log($_POST[ORDERSTATUS].$_POST[REFNOEXT].';'.$_POST[IPN_TOTALGENERAL].';'.$_POST[REFNOEXT].'|'.$aTariffCode[0].';'.$aTariffCode[1], 'pay_plus');
			
			//Отправка сообщения пользователю
			Email::CommunicationNewmail($sUserEmail, 'ARM2009 - оплата тарифа.', '<p><span class="TextHeaderSmall" style="border-radius: 10px 10px 0 0;">Поступление средств на личный счет</span></p>
			<p>На Ваш личный счет в системе ARM2009 поступили средства в размере ('.$_POST[IPN_TOTALGENERAL].' руб.).<br />
			Спасибо за то, что Вы выбрали нас! </p>');	
						
			//Подтверждение транзакции
			$forSend = array (
			'ORDER_REF' => $_POST[REFNO],
			'ORDER_AMOUNT' => $_POST[IPN_TOTALGENERAL],
			'ORDER_CURRENCY' => "RUB"  # Валюта мерчанта (Внимание! Должно соответствовать валюте мерчанта. )
			);					
			$pay = PayU::getInst()->setOptions($option)->setData($forSend)->IDN();

			DbConnect::Log($_POST[REFNOEXT], 'pay_plus_payu_autorized');
		break;
		//Завершение
		case 'COMPLETE':
			//Сообщение в логи
			DbConnect::Log($_POST[REFNOEXT], 'PayU_Complete' );
		break;
		case 'REVERSED':
			//Сообщение в логи
			DbConnect::Log($_POST[REFNOEXT], 'PayU_Reversed' );
		break;		
		case 'REFUND':
			//Сообщение в логи
			DbConnect::Log($_POST[REFNOEXT], 'PayU_Refund' );
		break;								
	}

	//Отчет о доставке
	$payansewer = PayU::getInst()->setOptions( $option )->IPN();
	echo $payansewer;
//	communication_newmessage('test', $payansewer, -1);
}
?>
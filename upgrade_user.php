<?php
	include_once('LowLevel/emailSend.php');
	$mail = 'antohag@yandex.ru'; 
	$sRestoreCode = 'test';
	Email::CommunicationNewmail($mail, 'ARM2009 | Восстановление пароля', 'Здравствуйте!<br /><br />Вы отправили запрос на восстановление пароля от почтового ящика email.<br /><br />Для того чтобы задать новый пароль, перейдите по ссылке http://arm2009.ru/user_Restore.php?code='.$sRestoreCode.' и следуйте инструкциям на странице.<br /><br />Ссылка и код восстановления будут активны в течении двух дней.<br /><br />Пожалуйста, проигнорируйте данное письмо, если оно попало к Вам по ошибке.');
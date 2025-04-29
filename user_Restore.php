<?php
include_once('LowLevel/emailSend.php');
include_once('UserControl/userControl.php');

if (isset($_POST["sEmail"])) {
  if (UserControl::IsLoginExist($_POST['sEmail'])) {
    $sRestoreCode = UserControl::GenerateRestoreCode($_POST['sEmail']);
    //Менять ссылку на актуальную
    Email::CommunicationNewmail($_POST["sEmail"], 'ARM2009 | Восстановление пароля', 'Здравствуйте!<br /><br />Вы отправили запрос на восстановление пароля от почтового ящика email.<br /><br />Для того чтобы задать новый пароль, перейдите по ссылке http://arm2009.ru/user_Restore.php?code=' . $sRestoreCode . ' и следуйте инструкциям на странице.<br /><br />Ссылка и код восстановления будут активны в течении двух дней.<br /><br />Пожалуйста, проигнорируйте данное письмо, если оно попало к Вам по ошибке.');
    header('Location: index.php?sPoupupHeader=Пароль почти восстановлен&sPoupupMessge=На Ваш адрес электронной почты выслано письмо с дальнейшими инструкциями');
    exit();
  } else {
    $_POST[sPoupupHeader] = 'Ошибка отправки сообщения';
    $_POST[sPoupupMessge] = 'Адресс электронной почты не зарегистрирован';
  }
}

if (isset($_GET['code'])) {
  if (UserControl::IsRestored($_GET['code']) == false) {
    header('Location: index.php?sPoupupHeader=Упс&sPoupupMessge=Такой страницы не существует');
    exit();
  }
}

if (isset($_POST['sPass1']) && isset($_POST['sPass2'])) {
  if ($_POST['sPass1'] == $_POST['sPass2']) {
    UserControl::RestorePassword($_GET['code'], $_POST['sPass2']);
    header('Location: index.php?sPoupupHeader=Пароль восстановлен&sPoupupMessge=Пароль успешно изменен');
    exit();
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <? include('Frame/header_all.php'); ?>
</head>

<body>

  `

  <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
    <tr>
      <td align="left" class="white">
        <h1>Восстановление учетной записи</h1>
      </td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
        <form action="" method="post">

          <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin" <?php if (isset($_GET["code"])) {
                                                                                                              echo 'style="display:none"';
                                                                                                            } ?>>
            <tr>
              <td>Адрес электронной почты<br />
                <label for="textfield7"></label>
                <input name="sCode" type="text" class="input_field input_field_715 input_field_background" id="sCode" style="display:none" value="<?php if (isset($_GET["code"])) {
                                                                                                                                                    echo $_GET['code'];
                                                                                                                                                  }; ?>" />
                <input name="sEmail" type="text" class="input_field input_field_715 input_field_background" id="sEmail" value="<?php if (isset($_POST["sEmail"])) {
                                                                                                                                  echo $_POST['sEmail'];
                                                                                                                                }; ?>" />
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
            <td><input name="button" type="submit" class="input_button" id="button" value="Восстановить учетную запись" /></td>
    </tr>
  </table>
  </form>

  <form action="" method="post">
    <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin" <?php if (!isset($_GET["code"])) {
                                                                                                        echo 'style="display:none"';
                                                                                                      } ?>>
      <tr>
        <td>Придумайте новый пароль<br />
          <label for="textfield2"></label>
          <input name="sPass1" type="password" class="input_field input_field_715 input_field_background" id="sPass1" />
        </td>
      </tr>
      <tr>
        <td>Повторите, чтобы не ошибиться<br />
          <label for="textfield7"></label>
          <input name="sPass2" type="password" class="input_field input_field_715 input_field_background" id="sPass2" />
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
      <td><input name="button" type="submit" class="input_button" id="button" value="Изменить пароль" /></td>
      </tr>
    </table>
  </form>
  </td>
  </tr>
  </table>
  <?
  /*Установка нижнего фрейма*/
  include('Frame/frame_Bottom.php');
  ?>
  <script>
    /*Место для скриптов*/
  </script>
</body>

</html>
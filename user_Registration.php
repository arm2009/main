<?php
	include_once('UserControl/userControl.php');
	$bAllDone = false;
	$sErrHeader = '';
	$sErrMessage = '';
	if (isset($_POST['sName1']) && isset($_POST['sName2']) && isset($_POST['sLogin']) && isset($_POST['sPhone']) && isset($_POST['sPass1']) && isset($_POST['sPass2']))
	{
		$sLogin = $_POST['sLogin'];
		$sName1 = $_POST['sName1'];
		$sName2 = $_POST['sName2'];
		$sPhone = $_POST['sPhone'];
		$sPass1 = $_POST['sPass1'];
		$sPass2 = $_POST['sPass2'];

		if (filter_var($sLogin, FILTER_VALIDATE_EMAIL) && $sPass1 == $sPass2)
		{
			$bAllDone = true;
		}
	}
	if ($bAllDone)
	{
		$sResult = UserControl::Register($sLogin, $sPass1, $sPhone, $sName1, $sName2);
		if ($sResult != 'Double name')
		{
			UserControl::Login($sLogin, $sPass1);
			header ('Location: work_Space.php?sPoupupHeader=Поздравляем&sPoupupMessge=Теперь Вы один из нас и добро пожаловать!');
			exit();
		}
		else
		{
			$_POST[sPoupupHeader] = 'Упс';
			$_POST[sPoupupMessge] = 'Пользователь с таким адресом электронной почты уже зарегистрирован, может быть стоит воспользоватся средствами <a href="http://www.arm2009.ru/test2014/user_Restore.php">восстановления учетной записи</a>?';
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>

<body>

<? include_once('Frame/frame_PoupUp.php'); ?>

<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left" class="white"><h1>Создание новой учетной записи</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
    <form action="" method="post" onsubmit="return IsFormValidate();">
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
        <tr>
          <td>Имя:<br />
            <label for="textfield7"></label>
            <input name="sName1" type="text" class="input_field input_field_715 input_field_background" value="<?php if (isset($_POST['sPhone'])) echo $sName1; ?>" id="sName1"/></td>
        </tr>
        <tr>
          <td>Фамилия:<br />
            <label for="textfield6"></label>
            <input name="sName2" type="text" class="input_field input_field_715 input_field_background" value="<?php if (isset($_POST['sName2'])) echo $_POST['sName2']; ?>" id="sName2" /></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Адрес электронной почты<br />
            <label for="textfield"></label>
            <input name="sLogin" type="text" class="input_field input_field_715 input_field_background" value="<?php if (isset($_POST['sPhone'])) echo $_POST['sLogin']; ?>" id="sLogin" /></td>
        </tr>
        <tr>
          <td>Мобильный телефон:<br />
            <label for="textfield11"></label>
            <input name="sPhone" type="text" class="input_field input_field_715 input_field_background" value="<?php if (isset($_POST['sPhone'])) echo $_POST['sPhone']; ?>" id="sPhone" /></td>
        </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <td>Придумайте пароль<br />
            <label for="textfield"></label>
            <input name="sPass1" type="password" class="input_field input_field_715 input_field_background" id="sPass1" /></td>
        </tr>    <tr>
              <td>Повторите, чтобы не ошибиться<br />
                <label for="textfield5"></label>
                <input name="sPass2" type="password" class="input_field input_field_715 input_field_background" id="sPass2" /></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>Нажимая кнопку «Регистрация» или используя вход через социальные сети<br />
              Вы подтверждаете согласие с условиями <a href="user_accept.txt" target="_blank">лицензии</a></td>
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
            <td><input name="button" type="submit" class="input_button" id="button" value="Создать учетную запись" onclick="submitRegistration()"/></td>
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

<script type="text/javascript">

function IsFormValidate()
{
		var sErrHeader = 'Недостаточно информации';
		var sErrReport = 'Для регистрации необходимо заполнить все поля формы';

		IsInputValidNotNull('#sName1');
		IsInputValidNotNull('#sName2');
		IsInputValidEmail('#sLogin');
		IsInputValidNotNull('#sPhone');
		IsInputValidPassword('#sPass1', '#sPass2');

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

</script>
</body>
</html>

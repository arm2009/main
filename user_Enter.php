<?php
	include_once('UserControl/userControl.php');
	
	$bAllDone = false;
	
	if (isset($_POST["sLogin"]) && isset($_POST["sPass"]))
	{
		$bAllDone = true;
	}
		
	if ($bAllDone)
	{
		$bSelfMachine = true;
		if ($_POST['checkbox'] == 'on') { $bSelfMachine = false;}
		$sResult = UserControl::Login($_POST["sLogin"], $_POST["sPass"],$bSelfMachine);
		if ($sResult != 'false')
		{

			header ('Location: work_Space.php');
			exit();
		}
		else
		{

			$_POST[sPoupupHeader] = 'Упс';
			$_POST[sPoupupMessge] = 'Такой адрес электронной почты или пароль нам неизвестен, может быть стоит воспользоватся <a href="http://www.arm2009.ru/test2014/user_Restore.php">средствами восстановления учетной записи</a>?';
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
    <td align="left" class="white"><h1>Вход для пользователей</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
    <form action="" method="post">
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
        <tr>
          <td>Адрес электронной почты<br />
            <label for="textfield"></label>
            <input name="sLogin" type="text" class="input_field input_field_715 input_field_background" id="sLogin" /></td>
        </tr>
                <td>Пароль<br />
                <label for="textfield"></label>
                <input name="sPass" type="password" class="input_field input_field_715 input_field_background" id="sPass" /></td>
        </tr>
            <tr>
                <td>&nbsp;</td>
          </tr>
            <tr>
                <td><label title="Поставьте эту отметку если не хотите чтобы после закрытия браузера на этом компьютере сохранилась информация связанная с использованием АРМ 2009"><table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="32"><input type="checkbox" name="checkbox" id="checkbox"/></td>
    <td>&#8212; чужой компьютер</td>
    <td width="32">&nbsp;</td>
    <td align="right" class="comment"><a href="user_Registration.php">Новая учетная запись</a><br />
      <a href="user_Restore.php">Восстановить доступ</a></td>
  </tr>
</table></label></td>
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
            <td><input name="button" type="submit" class="input_button" id="button" value="Войти" /></td>
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
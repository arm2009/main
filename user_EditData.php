<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	
    UserControl::isUserValidExit();
	
	if (isset($_POST['sName1']) && isset($_POST['sName2']) && isset($_POST['sPhone']))
	{
		UserControl::ChangeUserData('sName1', $_POST['sName1']);
		UserControl::ChangeUserData('sName2', $_POST['sName2']);
		UserControl::ChangeUserData('sPhone', $_POST['sPhone']);
		header ('Location: work_Space.php');
		exit();
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<? include('Frame/header_all.php'); ?>
</head>

<body><? include('Frame/frame_Top.php'); ?><? include_once('Frame/frame_PoupUp.php'); ?>

<? include_once('Frame/frame_PoupUp.php'); ?>

<table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
  <tr>
    <td align="left" class="white"><h1>Управление учетной записью</h1></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_w.jpg);">
    <form action="" method="post" onsubmit="return IsFormValidate();">
      <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
        <tr>
          <td><h1><?php echo UserControl::GetUserFieldValue('sName'); ?></h1></td>
        </tr>
        <tr>
          <td>Учетная запись создана <?php echo UserControl::GetUserDataCreate() ?></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Имя:<br />
            <label for="textfield7"></label>
            <input name="sName1" type="text" class="input_field input_field_715 input_field_background" value="<?php echo UserControl::GetUserFieldValue('sName1'); ?>" id="sName1" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
        </tr>
        <tr>
          <td>Фамилия:<br />
            <label for="textfield6"></label>
            <input name="sName2" type="text" class="input_field input_field_715 input_field_background" value ="<?php echo UserControl::GetUserFieldValue('sName2'); ?>" id="sName2" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>Мобильный телефон:<br />
            <label for="textfield11"></label>
            <input name="sPhone" type="text" class="input_field input_field_715 input_field_background" id="sPhone" value ="<?php echo UserControl::GetUserFieldValue('sPhone'); ?>" onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"/></td>
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
            <td><input name="button" type="submit" class="input_button" id="button" value="Сохранить изменения" onclick="ChangeMessageSave();submitRegistration();"/><div id="ChangeMessageDisplay" class="comment" style="display:inline-block;margin-left:20px;"></div></td>
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
		var sErrReport = 'Остались не правильно заполненные поля формы';
		
		IsInputValidNotNull('#sName1');
		IsInputValidNotNull('#sName2');
		IsInputValidNotNull('#sPhone');
		
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
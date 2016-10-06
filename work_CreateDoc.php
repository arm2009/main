<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";

	UserControl::isUserValidExit();

	if(isset($_GET[first]) && isset($_GET[second]) && isset($_GET[third]))
	{
		//Обработка входящих параметров
		$iGrId = $_GET[first];

		//Номера рабочих мест
		$sDiapazone = $_GET[second];
		if($sDiapazone == 'all')
		{
			//Все рабочие места
			$sDiapazone = '';
			$sql = "SELECT `id` FROM `Arm_workplace` WHERE `idGroup` = ".$iGrId." AND `idParent` <> -1 ORDER BY `iNumber`;";
			$vResult = DbConnect::GetSqlQuery($sql);
			if (mysql_num_rows($vResult) > 0)
			{
				while($vRow = mysql_fetch_array($vResult))
				{
					$sDiapazone .= '#'.$vRow[id];
				}
			}
		}
		else
		{
			//Указанные рабочие места
			try
			{
				$sDiapazone = str_replace(' ', '', $sDiapazone);
				$aDiapazone = explode(',', $sDiapazone);
				$sDiapazone = '';
				foreach($aDiapazone as &$Value)
				{
					if(strripos($Value, '-')>-1)
					{
						$tmpArray = explode('-',$Value);
						$max=max($tmpArray);
						$min=min($tmpArray);
						for($i=$min; $i <= $max; $i++)
						{
							//Выбор из базы и добавление айди-рм
							$sql = "SELECT `id` FROM `Arm_workplace` WHERE `iNumber` = ".$i." AND `idGroup` = ".$_GET[first].";";
							$vResult = DbConnect::GetSqlQuery($sql);
							if (mysql_num_rows($vResult) > 0)
							{
								while($vRow = mysql_fetch_array($vResult))
								{
									$sDiapazone .= '#'.$vRow[id];
								}
							}
						}
					}
					else
					{
						//Выбор из базы и добавление айди-рм
						$sql = "SELECT `id` FROM `Arm_workplace` WHERE `iNumber` = ".$Value." AND `idGroup` = ".$_GET[first].";";
						$vResult = DbConnect::GetSqlQuery($sql);
						if (mysql_num_rows($vResult) > 0)
						{
							while($vRow = mysql_fetch_array($vResult))
							{
								$sDiapazone .= '#'.$vRow[id];
							}
						}
					}
				}
			}
			catch (Exception $e)
			{
				DbConnect::Log($sDiapazone,'PrintDeClassError',UserControl::GetUserLoginId());
			}
		}
		$sDiapazone = substr($sDiapazone, 1);
		$aDiapazone = explode('#', $sDiapazone);

		//Иденификация типов документов
		$sDocType = $_GET[third];
		$sDocType = substr($sDocType, 1);
		$aDocType = explode('_', $sDocType);

		//Формирование листа заказов
		$sDocArray = '';
		$sTargetArray = '';
		$iDocCount = 0;
		foreach($aDocType as &$Value)
		{
			switch ($Value)
			{
				//Отчет
				case 'S0':
				case 'S1':
				case 'S2':
				case 'S5':
				case 'S6':
				case 'R0':
				case 'R1':
				case 'P0':
				case 'P1':
				case 'P2':
				case 'P3':
				case 'P4':
				case 'P5':
				case 'P6':
				case 'E0':
				case 'E1':
				case 'E2':
				case 'C0':
				case 'C1':
				case 'C2':
				case 'C3':
				case 'C4':
				case 'C5':
				case 'C6':
				$sDocArray .= ', \''. $Value .'\'';
				$sTargetArray.= ', \''. $iGrId .'\'';
				$iDocCount++;
				break;
				case 'S3':
					foreach($aDiapazone as &$Rm)
					{
						$sDocArray .= ', \''. $Value .'\'';
						$sTargetArray.= ', \''. $Rm .'\'';
						$iDocCount++;
					}
				break;
				case 'S4':
					foreach($aDiapazone as &$Rm)
					{
						//Проверка на наличие СИЗ
						$sql = "SELECT `id` FROM `Arm_Siz` WHERE `rmId` = ".$Rm.";";
						$vResult = DbConnect::GetSqlQuery($sql);
						if (mysql_num_rows($vResult) > 0)
						{
							$sDocArray .= ', \''. $Value .'\'';
							$sTargetArray.= ', \''. $Rm .'\'';
							$iDocCount++;
						}
					}
				break;
			}
		}

		$sDocArray = substr($sDocArray, 1);
		$sTargetArray = substr($sTargetArray, 1);

		//Внесение сессии
		$sql = "INSERT INTO `CreateDoc_Session` (`id`, `iUserId`, `dBegin`, `sPath`, `iState`, `iDocCount`) VALUES (NULL, ".UserControl::GetUserLoginId().", NOW(), '', 0, ".$iDocCount.");";
		DbConnect::GetSqlQuery($sql);
		$SessionId = mysql_insert_id();
		$ThisPath = UserControl::GetUserLoginId().'_'.$SessionId.'_'.date("Y-m-d_H-i-s");
		$sql = "UPDATE `CreateDoc_Session` SET `sPath` = '".$ThisPath."' WHERE `CreateDoc_Session`.`id` = ".$SessionId.";";
		DbConnect::GetSqlQuery($sql);
		mkdir('DownloadDoc/'.$ThisPath);
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<? include('Frame/header_all.php'); ?>
<style type="text/css">
#apDiv1 {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 1;
	background-position:center;
	background-repeat:no-repeat;
	background-image:url(Grph/bkg/bkg-2016.jpg);
}
</style>
</head>
<body>
<div id="apDiv1" class="shawdow_max white"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:800px;min-height:600px;">
  <tr>
    <td align="center" valign="middle" class="white" style="">

<div style="background-image:url(Grph/bkg/pattern_texture_w.jpg); padding:50px;" class="shawdow_max">

    <table width="715" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><table width="715" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="96"><div class="button button_print shawdow_max"></div></td>
            <td><div id="print_text">Идет формирование документов<br />
              <span id="print_comment" class="comment" style="display:none;">Подготовка к формированию документов</span></div></td>
            </tr>
        </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><div id="Print_Progress"></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td align="right"><input name="button2" type="submit" class="input_button" id="button2" value="Отменить" onclick="bCancel = true;$('#print_text').html('Формирование остановлено');$('#print_text').addClass('red');setTimeout('window.close()',1000);"/></td>
      </tr>
    </table>
</div>

    </td>
  </tr>
</table>
</div>
</body>
</html>
<script>
var aDocArray = [<? echo($sDocArray); ?>];
var aTargetArray = [<? echo($sTargetArray); ?>];
var iNowPosition = 0;
var bCancel = false;
var iSessionId = <? echo($SessionId); ?>;
var sPath = '<? echo($ThisPath); ?>';

$(document).ready(function(e){
	$( "#Print_Progress" ).progressbar({value: false});
	var progressbar = $( "#Print_Progress" ), progressbarValue = progressbar.find( ".ui-progressbar-value" );
	progressbarValue.css({"background-image": 'url(Grph/bkg/pattern_texture_b.jpg)'});
	progressbar.addClass('shawdow_max');

	$('#print_comment').fadeIn();
	PrintBegin();
});

function PrintBegin()
{
	if(iNowPosition < aDocArray.length)
	{
		//Подготовка к формированию
		$('#print_comment').html((iNowPosition+1)+' из '+aDocArray.length+', не закрывайте эту вкладку');
		$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj.CreateDoc.php',//url адрес файла обработчика
		data:{'iTarget': aTargetArray[iNowPosition], 'iDocType':aDocArray[iNowPosition], 'sPath':sPath},//параметры запроса
		response:'text',
		success:function (data)
		{
			//alert(data);
			var filename = data.substr(data.lastIndexOf("/") + 1);
			SaveToDisk(data, filename);
			//Переход к формированию следующего элемента
			iNowPosition++;
			setTimeout('PrintBegin()',1500);
		}
		});
	}
	else
	PrintEnd();
}

function PrintEnd()
{
	$('#print_text').html('Формирование документов завершено<br /><span id="print_comment" class="comment">Вкладка закроется автоматически</span>');
	setTimeout('window.close()',5000);
}
</script>

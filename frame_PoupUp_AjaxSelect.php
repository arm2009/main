<?
	include_once('LowLevel/dbConnect.php');
	
	$sName = 'Заготовка для выборок';
	
	if(isset($_POST[sName]))
	{
		$sName = $_POST[sName];
	}
?>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="JS/jquery-ui-1.10.4.custom.min.js"></script>

<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><?php echo $sName; ?></td>
      </tr>
      <tr>
        <td><div id="MainDataCont" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:500px;" class="comment">

		<table id="MainData">
			<tbody>
			</tbody>
		</table>


          </div></td>
        </tr>
      </table>
  </div></td>
</tr>
<tr class="blockmargin">
<td>&nbsp;</td>
</tr>
<tr class="blockmargin">
<td height="1px" bgcolor="#0099CC"></td>
</tr>
<tr class="blockmargin">
<td>&nbsp;</td>
</tr>
<tr>
<td align="right"><div id="PoupUpButton">
<input type="submit" class="input_button" id="buttonOk" value="Выбрать" onclick=""/>
<input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="progressInfo_hide(); return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<script>
var iPage = 1;
var iMaxPages = -1;

$('#MainDataCont').scroll( function() {
    	var recepientsContainerTop = $('#MainDataCont').position().top;
   	  var recepientsContainerTopHeight = $('#MainDataCont').height();
   	  var lastTrPos = $('#MainData > tbody > tr:last').position().top;
//высчитываем дошел ли пользователь до последней строки таблицы
   	  if(lastTrPos > recepientsContainerTop && lastTrPos < (recepientsContainerTop+recepientsContainerTopHeight) ){
   		addData();
   	  }
});

$(document).ready(function(){

	addData();
	
	var loadDataHandler = function (scrollData) {
		alert('!');
		//если больше данных нет, то нам больше получать данные не нужно
		/*if(nextPage == null){
			$('#recipientsUsersDataContainer').unbind('scroll',loadDataHandler);
			return;
		}*/
	   	  

	    }
});


//Дозагрузка элементов
function addData()
{
	if (iPage + 1 <= iMaxPages || iMaxPages == -1)
	{
	$.ajax({
		type:'post',//тип запроса: get,post либо head
		url:'aj_select.php',//url адрес файла обработчика
		data:{'iPage':iPage},//параметры запроса
		dataType: 'json',
		//response:'text',
		success:function (data) 
			{
				if (data != null)
				{
					var i = 0;
					
					iMaxPages = data.iPageCount;
					iPage ++;
					
					while (data.aResult[i][1] != null)
					{
						i++;
						var html = "<tr><td>"+data.aResult[i][1]+"</td></tr>";
						$("#MainData > tbody:last").append(html);
					}
				}
				else
				{
					
				}
			}
		});
	}
}

</script>
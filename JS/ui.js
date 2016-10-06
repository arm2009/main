/*
Механизация сворачивающихся блоков, инициируется из сворачивающего блока, например:
<h1 class="rollDown" id="header_roll0" onclick="RoollClick('roll0');">Сведения о организации</h1>
Управляет сворачивающимся блоком, например:
<div id="body_roll0" style="display:none;"></div>

Id блоков должны иметь вид: header_ХХХХХХ и body_ХХХХХХ, а сама процедура вызыватся функцией RoollClick('ХХХХХХ'); где ХХХХХХ - уникальный идентификатор.
*/
function RoollClick(inname)
{
	if($("#body_"+inname).is(":visible"))
	{
		$("#body_"+inname).slideUp();
		$("#header_"+inname).removeClass('rollUp');
		$("#header_"+inname).addClass('rollDown');
	}
	else
	{
		$("#body_"+inname).slideDown();
		$("#header_"+inname).removeClass('rollDown');
		$("#header_"+inname).addClass('rollUp');
	}
}
function SaveToDisk(fileURL, fileName) {
    // for non-IE
    if (!window.ActiveXObject) {
        var save = document.createElement('a');
        save.href = fileURL;
        save.target = '_blank';
        save.download = fileName || 'unknown';

        var event = document.createEvent('Event');
        event.initEvent('click', true, true);
        save.dispatchEvent(event);
        (window.URL || window.webkitURL).revokeObjectURL(save.href);
    }

    // for IE
    else if ( !! window.ActiveXObject && document.execCommand)     {
        var _window = window.open(fileURL, '_blank');
        _window.document.close();
        _window.document.execCommand('SaveAs', true, fileName || fileURL)
        _window.close();
    }
}
/*
Механизация переключателей:
<table width="100%" border="0" cellspacing="0" cellpadding="5">
<tr>
  <td id="check1_hr" height="50" align="center" class="corner_act white" onclick="CheckPress(1);">Основные данные</td>
  <td id="check2_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(2);">Аккредитация</td>
  <td id="check3_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(3);">Кадровый состав</td>
  <td id="check4_hr" height="50" align="center" class="corner_pass pointer" onclick="CheckPress(4);">Средства измерения</td>
</tr>
<tr>
  <td id="check1_bd" height="15" class="corner"></td>
  <td id="check2_bd" height="15"></td>
  <td id="check3_bd" height="15"></td>
  <td id="check4_bd" height="15"></td>
</tr>
</table>
*/
var iPressedChecker = 1;
function CheckPress(iInputCheck) {
	//Сняли выделение
	$('#check'+iPressedChecker+'_hr').removeClass('corner_act');
	$('#check'+iPressedChecker+'_hr').removeClass('white');
	$('#check'+iPressedChecker+'_bd').removeClass('corner');
	$('#check'+iPressedChecker+'_hr').addClass('corner_pass');
	$('#check'+iPressedChecker+'_hr').addClass('pointer');
	$('#check'+iPressedChecker+'_hr').attr('onclick','CheckPress('+iPressedChecker+');');
	if(iPressedChecker == 1) {$('#check'+iPressedChecker+'_hr').attr('style','border-left:none;');}
	
	//Добавили выделение
	$('#check'+iInputCheck+'_hr').removeClass('corner_pass');
	$('#check'+iInputCheck+'_hr').removeClass('pointer');
	$('#check'+iInputCheck+'_hr').addClass('corner_act');
	$('#check'+iInputCheck+'_hr').addClass('white');
	$('#check'+iInputCheck+'_bd').addClass('corner');
	$('#check'+iInputCheck+'_hr').removeAttr('onclick');
	
	$('#Check_Bl_'+iPressedChecker).slideUp();
	$('#Check_Bl_'+iInputCheck).slideDown();
	iPressedChecker = iInputCheck;
}
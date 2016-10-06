/*
Механизация сворачивающихся блоков, инициируется из сворачивающего блока, например:
<h1 class="rollDown" id="header_roll0" onclick="RoollClick('roll0');">Сведения о организации</h1>
Управляет сворачивающимся блоком, например:
<div id="body_roll0" style="display:none;"></div>

Id блоков должны иметь вид: header_ХХХХХХ и body_ХХХХХХ, а сама процедура вызыватся функцией RoollClick('ХХХХХХ'); где ХХХХХХ - уникальный идентификатор.
*/
//Работа с прогрессбарами
var sProgressNowElement = '';
var sProgressHiNowElement = '';

function progress_show(sInElement)
{
	sProgressNowElement = sInElement;
	progressResize();
	$('#ProgressFrame').show();
}
function progress_hide()
{
	sProgressNowElement = '';
	$('#ProgressFrame').fadeOut();
}
function progressHi_show(sInElement)
{
	sProgressHiNowElement = sInElement;
	progressResize();
	$('#ProgressFrameHiLevel').show();
}
function progressHi_hide()
{
	sProgressHiNowElement = '';
	$('#ProgressFrameHiLevel').fadeOut();
}
function progressResize()
{
	if(sProgressNowElement.length>0)
	{
		$('#ProgressFrame').css('left', $(sProgressNowElement).position().left);
		$('#ProgressFrame').css('top', $(sProgressNowElement).position().top);
		$('#ProgressFrame').width($(sProgressNowElement).width());
		$('#ProgressFrame').height($(sProgressNowElement).height());
	}
	if(sProgressHiNowElement.length>0)
	{
		$('#ProgressFrameHiLevel').css('left', $(sProgressHiNowElement).position().left);
		$('#ProgressFrameHiLevel').css('top', $(sProgressHiNowElement).position().top);
		$('#ProgressFrameHiLevel').width($(sProgressHiNowElement).width());
		$('#ProgressFrameHiLevel').height($(sProgressHiNowElement).height());
	}	
}

function progressRm_show()
{
	progress_show('#rm_navigation');
}
function progressRm_hide()
{
	progress_hide();
}
function progressInfo_show()
{
	progress_show('#info_navigation');
}
function progressInfo_hide()
{
	progress_hide();
}
function progressAll_show()
{
	progress_show('body');
}
function progressAll_hide()
{
	progress_hide();
}
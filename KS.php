<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="справочник,ОК016-94,ЕТКС,КС,ЕКС,должностей,профессий,тарифы,квалификационный">
<meta name="description" content="АРМ 2009 Специальная оценка условий труда - справочник ОК 016-94, ЕТКС, КС.">
<? include('Frame/header_all.php'); ?>
<style type="text/css">
#apDiv1 {
	position: absolute;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 1;
	background-color:#FFF;
	background-position:center;
	background-repeat:no-repeat;
	background-image:url(Grph/bkg/workers.jpg);
}

.structure_workers{display:block;padding:25px;background-position:left;text-align:left;border-left:#0099CC 3px solid;font-size:18px;margin-top:25px;background-image:url(Grph/user/user16l.png);background-image:url(Grph/bkg/pattern_texture_b.jpg);color:#FFF;}
.structure_workers_inside{display:block;padding:25px;background-repeat:no-repeat;background-position:left;text-align:left;text-align:justify;border:#0099CC 1px solid;}
</style>
</head>
<body>
<? include('Frame/frame_Top.php'); ?>
<? include_once('Frame/frame_PoupUp.php'); ?>
<div id="ProgressFrame" class="modal_progress"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><img src="Grph/progres/progress.gif"/></td></tr></table></div>
<div id="ProgressFrameHiLevel" class="modal_progress" style="z-index:10000;"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="middle"><img src="Grph/progres/progress.gif"/></td></tr></table></div>
<div id="apDiv1" class="shawdow_max"><table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:800px;min-height:600px;">
  <tr>
    <td align="center" valign="middle">

<div style="background-image:url(Grph/bkg/pattern_texture_w.jpg); padding:50px;" class="shawdow_max" id="mainform">
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="center"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td valign="middle"><div class="comment">Поиск ЕТКС / КС по должности, профессии, коду ОК 016-94<br />
<input name="findOK" type="text" class="input_field_micro input_field_445 input_field_background" id="findOK" value="<? if(isset($_GET[findOK])){echo($_GET[findOK]);} ?>" onkeypress="return runScript(event)" style="font-size:20px;"/>
          </div></td>
          <td width="282" align="right" valign="middle"><a href="index.php" target="_blank"><img src="Grph/sLogo_b.png" width="250" height="50" /></a></td>
          </tr>
        </table></td>
    </tr>
  </table>
<style>.dgray{color:#333;} .lgray{color:#999;} .selectblock:hover{border:1px #0099CC dashed;} .selectblock{border:1px #FFFFFF solid;}</style>
<div id="findResult" style="margin-top:25px;display:none;">
    <table width="715" border="0" cellspacing="0" cellpadding="0">
      <tr><td align="left" style="border-top:1px solid #0099CC;">
<div id="Result">
</div>
</td></tr></table></div></div>
    </td>
  </tr>
</table>
</div>
</body>
</html>
<script>
$(document).ready(function(e) {
	
	//Туллтипы
	$(document).tooltip({track: true, show: {effect: "fade",delay: 500}});

	//Автозаполнение
	$("#findOK").autocomplete({
		source: "aj.ok01694.php",           
		minLength: 3,
		delay: 500,		  
		select: function( event, ui ) {
		$("#findOK").val(ui.item.value);
		GetResult();
		return false;}
	})
		.data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.append( "<a class=\"comment\" title=\"" + item.etks + "\"><span class=\"gray\">" + item.code + "</span> " + item.value + "</a>" )
		.appendTo( ul );
	};
	
	<? if(isset($_GET[findOK])){echo('GetResult();');} ?>
});
function warningSay(inId,inName)
{
	var aPoupupFields = ['tOther', 'sSenks'];
	var aPoupupFieldsScribe = ['Ваше уточнение','Представьтесь, чтобы мы могли сказать Вам спасибо'];
	var aPoupupFieldsDefoultValue = ['',''];
	var s = 'Alert($(\'#tEtks\').val();';
	PoupUpMessgeCustomField(inName,'',s,aPoupupFields,aPoupupFieldsScribe, aPoupupFieldsDefoultValue);
}
function runScript(e)
{
	if (e.keyCode == 13) {
		GetResult();
        return false;
    }
}
function GetResult()
{
	$('#findResult').slideUp();
	$('#findOK').autocomplete('close');
	progressHi_show('#apDiv1');
	$.ajax({
	type:'post',//тип запроса: get,post либо head
	url:'aj.KS.php',//url адрес файла обработчика
	data:{'find': $("#findOK").val()},//параметры запроса
	//dataType: 'json',
	response:'text',
	success:function (data) 
	{ 
		$('#Result').html(data);
		$('#findResult').slideDown('slow', function() {
			progressHi_hide();
		});
	}
	});
	
	return true;
}
function insertParam(key, value)
{
    key = encodeURI(key); value = encodeURI(value);
    var kvp = document.location.search.substr(1).split('&');
    var i=kvp.length; var x; while(i--) 
    {
        x = kvp[i].split('=');
        if (x[0]==key)
        {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }
    if(i<0) {kvp[kvp.length] = [key,value].join('=');}
    document.location.search = kvp.join('&'); 
}

</script>
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter1172599 = new Ya.Metrika({id:1172599,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
var reformalOptions = {
	project_id: 630753,
	show_tab: false,
	project_host: "arm2009SUOT.reformal.ru"
};

(function() {
	var script = document.createElement('script');
	script.type = 'text/javascript'; script.async = true;
	script.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'media.reformal.ru/widgets/v3/reformal.js';
	document.getElementsByTagName('head')[0].appendChild(script);
})();
</script>
<?php
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "Util/String.php";

	$bExitButtonView = false;

	if (isset($_GET['do']))
	{
		if($_GET['do'] == 'loguot')
		{
			UserControl::Logout();
		}
	}

	if (UserControl::IsLogin() && !isset($_GET['do']))
	{

		$vResult = UserValidator::GetSqlQuerySafe(UserControl::GetUserLoginIdCrypt(), UserControl::GetUserHash2(), "SELECT DECODE(sName, '04022009') FROM Arm_users WHERE id = ".UserControl::GetUserLoginId());
		if ($vResult != null)
		{
			$sUserName = mysql_result($vResult, 0, 0);
			//$sUserName = UserControl::GetUserFieldValue('sName');
			$bExitButtonView = true;
		}
		else
		{
			UserControl::Logout();
			$_POST[sPoupupHeader] = 'Упс';
			$_POST[sPoupupMessge] = 'Учетная запись используется на другом устройстве';
		}
	}

	if (UserControl::IsLogin() && isset($_GET['pay']))
	{
		if ($_GET['pay'] == '0')
		{
			$_POST[sPoupupHeader] = 'Упс';
			$_POST[sPoupupMessge] = 'Что-то пошло не так, попробуйте повторить платеж не много позже.';
		}

		if ($_GET['pay'] == '1')
		{
			$_POST[sPoupupHeader] = 'Все в порядке';
			$_POST[sPoupupMessge] = 'Платеж успешно проведен.';
		}
	}

	//Вставка счетчика
	function inject_counter($count, $numcount, $text = null)
	{
		echo('<table border="0" cellpadding="0" cellspacing="0"><tr><td><img src="Grph/counter/left.png"/></td>');
		$divider = 0;
		$razn = $numcount - strlen($count);

		for($i = 0; $i < $razn; $i++)
		{
			$count = '0' .$count;
		}

		for($i = 0; $i < strlen($count); $i++)
		{
			if($divider == 3)
			{
				$divider =0;
				echo('<td><img src="Grph/counter/defind.png"/></td>');
			}

			switch($count[$i])
			{
				case 0:
				echo('<td><img src="Grph/counter/0.png"/></td>');
				break;
				case 1:
				echo('<td><img src="Grph/counter/1.png"/></td>');
				break;
				case 2:
				echo('<td><img src="Grph/counter/2.png"/></td>');
				break;
				case 3:
				echo('<td><img src="Grph/counter/3.png"/></td>');
				break;
				case 4:
				echo('<td><img src="Grph/counter/4.png"/></td>');
				break;
				case 5:
				echo('<td><img src="Grph/counter/5.png"/></td>');
				break;
				case 6:
				echo('<td><img src="Grph/counter/6.png"/></td>');
				break;
				case 7:
				echo('<td><img src="Grph/counter/7.png"/></td>');
				break;
				case 8:
				echo('<td><img src="Grph/counter/8.png"/></td>');
				break;
				case 9:
				echo('<td><img src="Grph/counter/9.png"/></td>');
				break;
			}
			$divider++;
		}
		if(!is_null($text))
		{
			echo('<td><img src="Grph/counter/defind.png"/></td>');
			echo('<td style="background:url(Grph/counter/defind.png); padding-left: 15px; color:#BBB;">'.$text.'</td>');
		}
		echo('<td><img src="Grph/counter/right.png"/></td></tr></table>');
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="css/arm2009style/jquery-ui-1.10.4.custom.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="highslide/highslide.css" />
    <link href="css_base.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="Video/minimalist.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="JS/jquery-ui-1.10.4.custom.min.js"></script>
<script src="Video/flv.min.js"></script>
<script type="text/javascript" src="highslide/highslide.js"></script>
<script type="text/javascript" src="highslide/highslide.config.js" charset="utf-8"></script>

<title>АРМ 2009 Специальная оценка условий труда - программное обеспечение для организаций проводящих специальную оценку труда, экспертов и испытательных лабораторий.</title>
<meta name="keywords" content="программное,обеспечение,автоматизация,ПО,специальная,оценка,труда,аттестация,рабочих,мест,испытательная,лаборатория,нормативный,документ,обучение,эксперт,специалист,комиссия,АКОТ">
<meta name="description" content="АРМ 2009 | Специальная оценка труда — программное обеспечение для испытательных лабораторий и организаций проводящих специальную оценку условий труда">
<meta name="robots" content="index, follow">
<meta name="author" content="Консалтинговый центр Труд">
<meta name="copyright" content="Все права принадлежат Консалтинговому центру Труд">
<style>
/*Основные данные*/
body {background:url(Grph/bkg/pattern_texture_b.jpg);}
/*Типы блоков*/
.flowplayer { width: 100%; background-size: cover; max-width: 960px; border:#FFF 1px dashed;}
.flowplayer .fp-controls { background-color: rgba(102, 51, 51, 0.4)}
.flowplayer .fp-timeline { background-color: rgba(102, 51, 51, 0.5)}
.flowplayer .fp-progress { background-color: rgba(219, 0, 0, 1)}
.flowplayer .fp-buffer { background-color: rgba(249, 249, 249, 1)}
.flowplayer { background-image: url("Video/Screenblue.jpg");}
</style>
</head>

<body ><? if (!isset($_GET['do'])) { include('Frame/frame_Top.php');} ?><? include_once('Frame/frame_PoupUp.php'); ?>

<? include_once('Frame/frame_PoupUp.php'); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="960" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td align="center" class="white"><img src="Grph/Logo_w.png" alt="АРМ 2009 | Специальная оценка условий труда" width="715" height="143" class="blockmargin"/></td>
      </tr>
    </table></td>
  </tr>
</table>
<div style="background-image:url(Grph/bkg/pattern_texture_r.jpg);color:white;text-align:center;-webkit-box-shadow: inset 0px 0px 15px 0px rgba(0,0,0,0.5);-moz-box-shadow: inset 0px 0px 15px 0px rgba(0,0,0,0.5);box-shadow: inset 0px 0px 15px 0px rgba(0,0,0,0.5);" class="spec_block">
	<div style="width:715px;display:inline-block;text-align:left;margin-top:3em;margin-bottom:3em;">
<h1>АРМ 2009 - стал открытым!</h1>
<p>
	С 1 октября 2017 года, единогласным решением дружного коллектива разработчиков<br>АРМ 2009 - становится программным обеспечением с открытым исходным кодом.
</p>
<p>
	Что это значит для Вас?
</p>
<p>
	Если вы являетесь экспертом или просто заинтересованы в оформлении материалов специальной оценки условий труда, то с 1 октября 2017 года - Вы можете использовать АРМ 2009 совершенно бесплатно и без каких-либо ограничений.
</p>
<p>
	Если вы разработчик, то Вы можете присоединится к работе над проектом на <a href="https://github.com/arm2009/main" target="_blank">GitHub</a>!
</p>
<hr style="background-color:#e68c74;">
<p>
	Спасибо всем, кто поддерживал и поддерживает Нас и этот проект начиная с 2007 года.
</p>
	</div>
</div>
<div style="background-image:url(Grph/bkg/pattern_texture_w.jpg);">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        <table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
          <tr>
            <td align="left"><table width="715" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="304" align="left" valign="middle"><img src="Grph/user/user256hl.png" width="256" height="256" class="shawdow_min"/></td>
                <td align="left" valign="middle"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                  <tr>
                    <td><div class="button_text button_active button_hightlight shawdow_min" title="Вход в существующую учетную запись" onclick="window.location.href = 'user_Enter.php';">Вход<br />
                      <span class="comment">Вход в существующую учетную запись</span></div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div class="button_text button_active button_hightlight shawdow_min" title="Создание новой учетной записи" onclick="window.location.href = 'user_Registration.php';">Регистрация<br />
                      <span class="comment">Создание новой учетной записи</span></div></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td><div class="button_text button_active button_hightlight shawdow_min" title="Восстановить доступ к учетной записи" onclick="window.location.href = 'user_Restore.php';">Восстановление пароля<br />
                      <span class="comment">Восстановить доступ к учетной записи</span></div></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
      </table></td>
    </tr>
  </table>
</div>
<div>
  <table width="960" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
    <tr>
      <td align="left" class="white">

<style>
.table_header_index{background-color:rgba(255,255,255,0.5);}
.table_odd_index{background-color:rgba(255,255,255,0.25);}
.table_even_index{background-color:rgba(255,255,255,0.15);}
</style>
<p align="center"><iframe width="960" height="540" src="//www.youtube.com/embed/gcVFoH8M3DM?showinfo=0" frameborder="0" allowfullscreen></iframe></p>
<table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="128" bgcolor="#70c6e3">
        <a href="Grph/sqare/1.jpg" onclick="return hs.expand(this)" title="Комментарий">
        <img src="Grph/sqare/1b.jpg" alt="Комментарий" width="128" height="96" border="0"/></a>
    </td>
    <td>&nbsp;</td>
    <td width="128" bgcolor="#70c6e3">
    <a href="Grph/sqare/2.jpg" onclick="return hs.expand(this)" title="Комментарий"> <img src="Grph/sqare/2b.jpg" alt="АРМ 2009 | Специальная оценка условий труда" width="128" height="96" border="0"/></a></td>
    <td>&nbsp;</td>
    <td width="128" bgcolor="#70c6e3">
    <a href="Grph/sqare/3.jpg" onclick="return hs.expand(this)" title="Комментарий"> <img src="Grph/sqare/3b.jpg" alt="АРМ 2009 | Специальная оценка условий труда" width="128" height="96" border="0"/></a></td>
    <td>&nbsp;</td>
    <td width="128" bgcolor="#70c6e3">
    <a href="Grph/sqare/4.jpg" onclick="return hs.expand(this)" title="Комментарий"> <img src="Grph/sqare/4b.jpg" alt="АРМ 2009 | Специальная оценка условий труда" width="128" height="96" border="0"/></a></td>
    <td>&nbsp;</td>
    <td width="128" bgcolor="#70c6e3">
    <a href="Grph/sqare/5.jpg" onclick="return hs.expand(this)" title="Комментарий"> <img src="Grph/sqare/5b.jpg" alt="АРМ 2009 | Специальная оценка условий труда" width="128" height="96" border="0"/></a></td>
    <td>&nbsp;</td>
    <td width="128" bgcolor="#70c6e3">
    <a href="Grph/sqare/6.jpg" onclick="return hs.expand(this)" title="Комментарий"> <img src="Grph/sqare/6b.jpg" alt="АРМ 2009 | Специальная оценка условий труда" width="128" height="96" border="0"/></a></td>
    </tr>
</table>
<p>&nbsp;</p>
<h1>АРМ 2009 | Специальная оценка условий труда</h1>
<p>АРМ 2009 | Специальная оценка труда — принципиально новое программное обеспечение для испытательных лабораторий и организаций проводящих специальную оценку условий труда совмещающее в себе многолетний опыт разработчиков и современные технологии. </p>
<p>&nbsp;</p>
<h1>Просто и доступно</h1>
<p>Низкая стоимость, мобильность и удобный интерфейс АРМ 2009 позволяет гибко подходить к организации работы экспертов и специалистов испытательных лабораторий. Больше нет необходимости покупать, обновлять, устанавливать или настраивать программное обеспечение, проходить обучение или читать толстые руководства пользователя, достаточно набрать в адресной строке браузера компьютера или мобильного устройства www.arm2009.ru и можно начинать работу там, где вам это удобно. При этом стоимость подписки на использование АРМ 2009 начинается от 210 рублей в месяц и уменьшается по мере увеличения числа пользователей. </p>
<p>&nbsp;</p>
<style>
	.button_text_index{color:#FFF;border:none;cursor:pointer;background-color:#70c6e3;}
	.button_text_index:hover{color:#09C;background-color:#FFF;border-color:#09C;}
</style>
<div class="button_text button_text_index shawdow_min" onclick="window.location.href = 'user_Registration.php';">Начать   прямо сейчас, совершенно бесплатно...<br />
<span class="comment">Создание новой учетной записи</span></div>
<p>&nbsp;</p>
<h1>Качество, скорость и безопасность</h1>
<p> Единые стандарты в оформлении материалов, строгое соответствие с нормативно-правовыми документами и стандартные справочники, сводят ошибки при вводе и анализе данных специальной оценке условий труда к минимуму, позволяя увеличить качество материалов и многократно увеличить скорость обработки данных, а система шифрования и резервное копирование надежно защитит их от непредвиденных ситуаций и злоумышленников.</p>
<p>&nbsp;</p>
<h1>Совместная работа.</h1>
<p>Сложная организация совместной работы нескольких пользователей, отделов или даже филиалов – теперь не проблема, достаточно добавить несколько пользователей в своё рабочее пространство и они смогут работать совместно вне зависимости от места своего нахождения или количества, будь то соседние столы или города, 5 специалистов или 500.</p>
<p>&nbsp;</p>
<h1>Совместимо с АКОТ.</h1>
<p>Экспортируйте отчеты о проведенной специальной оценке в Автоматизированную систему анализа и контроля в области охраны труда, с гарантированным результатом в несколько простых шагов.</p>
<? if(date('U') < date('U','2016-01-01')): ?>
<p style="font-style:italic;">Функция доступна с 1 января 2016 года.</p>
<? endif; ?>
<p>&nbsp;</p>
<h1>Поддержка и корректировка.</h1>
<p>Мы оперативно реагируем на изменения в нормативно – правовых документах и прислушиваемся к  опыту, замечаниям и предложениям наших пользователей, внося соответствующие изменения в программное обеспечение. Мы постоянно работаем над улучшением его качества и расширением возможностей доступных нашим пользователям.</p>
<p>&nbsp;</p>
<h1>Тарифные планы и оплата</h1>
<p>С 1 октября 2016 года АРМ 2009 стал совершенно бесплатен для своих пользователей.</p>
</td>
    </tr>
  </table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-image:url(Grph/bkg/pattern_texture_w.jpg);">
  <tr>
    <td><table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin">
      <tr>
        <td valign="top"><div class="button button_find shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Загрузить на локальный диск" onclick="addstatistic('KS.php'); window.open('KS.php', '_blank');window.focus();">Cправочник ОК 016-94, ЕТКС, КС.<br />
          <span class="comment">АРМ2009 | Специальная оценка условий труда</span></div></td>
      </tr>
      <tr>
        <td height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div class="button button_download shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Загрузить на локальный диск" onclick="addstatistic('arm2009.ru_FZ_Schemma.pdf'); window.open('download/arm2009.ru_FZ_Schemma.pdf', '_blank');window.focus();">Схема  проведения специальной оценки условий труда<br />
          <span class="comment">415 kb | АРМ2009 | Специальная оценка условий труда</span></div></td>
      </tr>
      <tr>
        <td height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td height="1" bgcolor="#0099CC"></td>
        <td height="1" bgcolor="#0099CC"></td>
      </tr>
      <tr>
        <td height="35">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td width="96" valign="top"><div class="button button_download shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Загрузить на локальный диск" onclick="addstatistic('arm2009.ru_FZ_Specialnaya_Ocenka_Uslovii_Truda.pdf'); window.open('download/arm2009.ru_FZ_426_28.12.2013_Specialnaya_Ocenka_Uslovii_Truda.pdf', '_blank');window.focus();">О специальной оценке условий труда<br />
          <span class="comment">377 kb | Федеральный закон № 426-ФЗ от 28 декабря 2013 г.</span></div></td>
      </tr>
      <tr>
        <td width="96" height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div class="button button_download shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Загрузить на локальный диск" onclick="addstatistic('arm2009.ru_PR_33n_24.01.2014_Methodology.pdf'); window.open('download/arm2009.ru_PR_33n_24.01.2014_Methodology.pdf', '_blank');window.focus();">Об утверждении методики проведения специальной  оценки условий труда, классификатора вредных и (или) опасных производственных факторов, формы отчета  о проведении специальной оценки условий труда  и инструкции по её заполнению<br />
          <span class="comment">1 177 kb | Приказ Минтруда России № 33н от 21.03.2014 г.</span></div></td>
      </tr>
      <tr>
        <td height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td width="96" valign="top"><div class="button button_download shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Загрузить на локальный диск" onclick="addstatistic('arm2009.ru_FZ_421_28.12.2013_Izmeneniya_ND.pdf'); window.open('download/arm2009.ru_FZ_421_28.12.2013_Izmeneniya_ND.pdf', '_blank');window.focus();">О внесении изменений в отдельные законодательные акты<br />
          Российской Федерации в связи с принятием Федерального закона<br />
          &quot;О специальной оценке условий труда&quot;<br />
          <span class="comment">1 187 kb | Федеральный закон № 421-ФЗ от 28 декабря 2013 г.</span></div></td>
      </tr>
      <tr>
        <td height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div class="button button_link shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Открыть в новом окне" onclick="addstatistic('http://www.rosmintrud.ru/docs/mintrud/orders/246'); window.open('http://www.rosmintrud.ru/docs/mintrud/orders/246', '_blank');window.focus();">О форме и порядке подачи декларации соответствия условий труда государственным нормативным требованиям охраны труда, Порядке формирования и ведения реестра деклараций соответствия условий труда государственным нормативным требованиям охраны труда<br />
          <span class="comment">consultant.ru | Приказ Минтруда России № 80н от 07.02.2014</span><span class="comment"> г.</span></div></td>
      </tr>
      <tr>
        <td height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div class="button button_link shawdow_min"></div></td>
        <td><div class="button_text button_active shawdow_min" title="Открыть в новом окне" onclick="addstatistic('http://www.rosmintrud.ru/docs/mintrud/orders/171'); window.open('http://www.rosmintrud.ru/docs/mintrud/orders/171', '_blank');window.focus();">Об утверждении формы сертификата эксперта на право выполнения работ по специальной оценке условий труда, технических требований к нему, инструкции по заполнению бланка сертификата эксперта на право выполнения работ по специальной оценке условий труда и Порядка формирования и ведения реестра экспертов организаций, проводящих специальную оценку условий труда<br />
          <span class="comment">consultant.ru | Приказ Минтруда России № 32н от 24.01.2014</span><span class="comment"> г.</span></div></td>
      </tr>
      <tr>
        <td height="35" valign="top">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td height="1" bgcolor="#0099CC"></td>
        <td height="1" bgcolor="#0099CC"></td>
      </tr>
      <tr>
        <td height="35">&nbsp;</td>
        <td height="35">&nbsp;</td>
      </tr>
      <tr>
        <td><div class="button button_mail shawdow_min"></div></td>
        <td><div id="email" class="button_text button_active shawdow_min" title="Подписать Ваш email на обновления от разработчиков" onclick="scribe();">Подписать Ваш email на обновления от разработчиков<br />
          <span class="comment">АРМ2009 | Специальная оценка условий труда</span></div></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div class="button button_phone shawdow_min"></div></td>
        <td><div> 660032, г. Красноярск, ул. Андрея Дубенского 4<br />
          (391) 228-73-58 / mail@kctrud.ru </div></td>
      </tr>
      <tr>
        <td valign="top">&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td valign="top"><div class="button button_stat shawdow_min"></div></td>
        <td align="left" class="blockmargin_micro">
		  <?
		$sQuery = "SELECT SUM(iDocCount) FROM `CreateDoc_Session`;";
		$vResult = DbConnect::GetSqlCell($sQuery);
		inject_counter($vResult, 12, morph($vResult, 'документ','документа','документов')); ?><br />
          <?
		$sQuery = "SELECT `id` FROM `Arm_workplace` WHERE `idParent` <> -1;";
		$vResult = DbConnect::GetSqlQuery($sQuery);
		inject_counter(mysql_num_rows($vResult), 9, morph(mysql_num_rows($vResult), 'рабочее место','рабочих места','рабочих мест')); ?><br />
          <?
		$sQuery = "SELECT `id` FROM `Arm_group`;";
		$vResult = DbConnect::GetSqlQuery($sQuery);
		inject_counter(mysql_num_rows($vResult), 6, morph(mysql_num_rows($vResult), 'работодатель','работодателя','работодателей')); ?><br />
          <?
		$sQuery = "SELECT `id` FROM `Arm_users`;";
		$vResult = DbConnect::GetSqlQuery($sQuery);
		inject_counter(mysql_num_rows($vResult), 4, morph(mysql_num_rows($vResult), 'эксперт','эксперта','экспертов')); ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#0099CC" style="background:url(Grph/bkg/pattern_texture_b.jpg);"><table width="715" border="0" align="center" cellpadding="0" cellspacing="0" class="blockmargin_micro">
      <tr>
        <td width="224" class="white"><a href="http://www.kctrud.ru/" target="_blank" title="Конаслтинговый центр Труд - разработка програмного обеспечения"><img src="Grph/KCLogo.png" alt="Конаслтинговый центр Труд - разработка програмного обеспечения" width="224" height="50" /></a></td>
        <td align="center" class="white"><div class="share42init"
    	data-url="http://arm2009.ru/"
		data-title="АРМ 2009 | Специальная оценка условий труда"
		data-image="http://arm2009.ru/Grph/ARM2009.jpg"
		data-description="АРМ 2009 | Специальная оценка труда — принципиально новое программное обеспечение для испытательных лабораторий и организаций проводящих специальную оценку условий труда совмещающее в себе многолетний опыт разработчиков и современные технологии."></div>
          <script type="text/javascript" src="Share42/share42.js"></script></td>
        <td width="182" align="right" class="white"><a href="http://xn--90aciabp5adg0bq2c.xn--p1ai/" target="_blank" title="Обучениевсем.рф - система дистанционного образования"><img src="Grph/alleducation.png" alt="Обучениевсем.рф - система дистанционного образования" width="182" height="50" /></a></td>
      </tr>
    </table></td>
  </tr>
</table>
<script>
function scribe()
{
	$('#email').removeClass('button_active');
	$('#email').removeAttr('onclick');
	$('#email').removeAttr('title');
	$("#email").fadeOut("slow", function() {
	$('#email').html('<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><input name="inemail" type="text" class="input_field" id="inemail" value="Ваш email" onclick="textclick();" style="width:90%;" onkeydown="onreturnpress();"/></td><td width="180" align="right"><input name="button" type="submit" class="input_button" id="button" value="Подписаться" onclick="gotourl();" title="Подписать Ваш email на обновления от разработчиков"/></td></tr></table>');
		$("#email").fadeIn("slow");
	});
}
function gotourl()
{
		$.ajax({
		url: 'addemail.php?setmail='+$('#inemail').val(),
		success: function(data) {
			if(data == 1)
			{
				$("#email").fadeOut("slow", function() {
				$('#email').html('Спасибо за то, что вы с нами!<span class="comment"></span><br /><span class="comment">Ваш адрес электронной почты  добавлен в список рассылки</span>');
				$("#email").fadeIn("slow");
				});
			}
			else
			{
				$("#email").fadeOut("slow", function() {
				$('#email').html('<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><input name="inemail" type="text" class="input_field input_wrong" id="inemail" value="Неверный адресс электронной почты"  onclick="textclick();" onkeydown="onreturnpress();"/></td><td width="180" align="right"><input name="button" type="submit" class="input_button" id="button" value="Подписаться" onclick="gotourl();" title="Подписать Ваш email на обновления от разработчиков"/></td></tr></table>');
				$("#email").fadeIn("slow");
				});
			}
		}});

	return false;
}
function textclick()
{
	$('#inemail').removeAttr('onclick');
	$('#inemail').removeClass('input_wrong');
	$('#inemail').select();
}
function onreturnpress()
{
	if(event.keyCode == 13)
	{
		gotourl();
	}
}
function addstatistic(filename)
{
		$.ajax({url: 'addemail.php?statistic='+filename});
}
</script>
<!-- Yandex.Metrika counter -->
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
</script>
</body>
</html>

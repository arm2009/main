<table width="715" border="0" cellspacing="0" cellpadding="0">
<tr>
  <td align="left"><div id="PoupUpMessage">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>Диапазон формирования документов</td>
        </tr>
      <tr>
        <td><div style="margin:10px;border:#09C solid 1px;padding:10px;" class="comment">
        <label><input type="radio" name="print_DV" value="all" id="print_DV_0" checked="checked" onchange="$('#sPrintDV').slideUp();"/>Вся группа данных</label><br />
        <label><input type="radio" name="print_DV" value="diap" id="print_DV_1"  onchange="$('#sPrintDV').slideDown();"/>Указанный диапазон рабочих мест<br /><input name="sPrintDV" type="text" class="input_field_micro input_field_background" style="width:95%;display:none;" id="sPrintDV" title="Номера рабочих мест через запятую или диапазоны рабочих мест через тире, например: 1, 3, 5, 2-4" onkeypress="AjaxPrintPressReturn(event)" value=""/></label>
        </div>
        </tr>
      <tr>
        <td>Формы вывода документов</td>
        </tr>
      <tr>
        <td><div id="print_PF_div" style="display:block;overflow:auto;margin:10px;border:#09C solid 1px;padding:10px;max-height:300px;" class="comment">

<div id="header_prSOUT" onclick="RoollClick('prSOUT');" class="rollDown" title="В соответствии с приказом Министерства труда и социальной защиты Российской Федерации от 24 января 2014 года № 33н">Отчет о проведении специальной оценки условий труда</div>
<div id="body_prSOUT" style="display:none;margin:10px; margin-left:30px;">
    <label><input type="checkbox" name="print_PF" value="S0" id="print_PF_0" />Титульный лист отчета о проведении СОУТ</label><br />
    <label><input type="checkbox" name="print_PF" value="S1" id="print_PF_1" />Сведения об организации проводящей СОУТ</label><br />
    <label><input type="checkbox" name="print_PF" value="S2" id="print_PF_2" />Перечень рабочих мест, на которых проводилась СОУТ</label><br />
    <label><input type="checkbox" name="print_PF" value="S3" id="print_PF_3" />Карта СОУТ</label><br />
    <label><input type="checkbox" name="print_PF" value="S4" id="print_PF_4" />Протокол оценки эффективности СИЗ</label><br />
    <label><input type="checkbox" name="print_PF" value="S5" id="print_PF_5" />Сводная ведомость результатов СОУТ</label><br />
    <label><input type="checkbox" name="print_PF" value="S6" id="print_PF_6" />Перечень рекомендуемых мероприятий</label><br />
</div>
<div id="header_prEXP" onclick="RoollClick('prEXP');" class="rollDown">Документы организации проводящей СОУТ</div>
<div id="body_prEXP" style="display:none;margin:10px; margin-left:30px;">
    <label title="В соответствии с приказом Министерства труда и социальной защиты Российской Федерации от 3 июля 2014 года № 436н"><input type="checkbox" name="print_PF" value="E0" id="print_PF_7"/>Сведения о результатах проведения СОУТ, в Федеральную службу по труду и занятости</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="E1" id="print_PF_7" disabled="disabled"/>О возможности снижения класса условий труда в связи с применением эффективных СИЗ</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="E2" id="print_PF_8" disabled="disabled"/>О возможности использования результатов производственного контроля в рамках СУОТ</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="E1" id="print_PF_9" disabled="disabled"/>Заключение эксперта по итогам специальной оценки условий труда</label><br />
</div>
<div id="header_prIL" onclick="RoollClick('prIL');" class="rollDown">Протоколы испытательной лаборатории</div>
<div id="body_prIL" style="display:none;margin:10px; margin-left:30px;">
    <label><input type="checkbox" name="print_PF" value="E2" id="print_PF_10"/>Рабочий журнал проведения измерений в рамках согласованного перечня</label><br />
	<label><input type="checkbox" name="print_PF" value="P1" id="print_PF_18"/>Протокол оценки напряженности трудового процесса</label><br />
	<label><input type="checkbox" name="print_PF" value="P0" id="print_PF_17"/>Протокол оценки тяжести трудового процесса</label><br />
    <label><input type="checkbox" name="print_PF" value="P4" id="print_PF_21"/>Протокол оценки воздуха рабочей зоны</label><br />
    <label><input type="checkbox" name="print_PF" value="P2" id="print_PF_19"/>Протокол оценки световой среды</label><br />
    <label><input type="checkbox" name="print_PF" value="P5" id="print_PF_22"/>Протокол оценки виброакустики</label><br />
    <label><input type="checkbox" name="print_PF" value="P6" id="print_PF_23"/>Протокол оценки шума</label><br />
    <label><input type="checkbox" name="print_PF" value="P3" id="print_PF_20"/>Протокол оценки микроклимата</label><br />
</div>
<div id="header_prWRK" onclick="RoollClick('prWRK');" class="rollDown">Документы работодателя</div>
<div id="body_prWRK" style="display:none;margin:10px; margin-left:30px;">
    <label><input type="checkbox" name="print_PF" value="R0" id="print_PF_0"/>Приказ о создании комиссии по проведению СОУТ</label><br />
    <label  title="В соответствии с приказом Министерства труда и социальной защиты Российской Федерации от 7 февраля 2014 года № 80н"><input type="checkbox" name="print_PF" value="R1" id="print_PF_9"/>Декларация соответствия условий труда</label><br />
</div>
<div id="header_prCOM" onclick="RoollClick('prCOM');" class="rollDown">Документы комиссии</div>
<div id="body_prCOM" style="display:none;margin:10px; margin-left:30px;">
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C0" id="print_PF_10" disabled="disabled"/>Проведении исследований (испытаний) и измерений производственных факторов</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C1" id="print_PF_11" disabled="disabled"/>Снижении класса условий труда в связи с применением эффективных СИЗ</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C2" id="print_PF_12" disabled="disabled"/>Возможности использования результатов производственного контроля</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C3" id="print_PF_13" disabled="disabled"/>Утверждении результатов идентификации производственных факторов</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C4" id="print_PF_14" disabled="disabled"/>Невозможности проведения исследований производственных факторов</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C5" id="print_PF_15" disabled="disabled"/>Утверждении перечня рабочих мест на которых будет проведена СОУТ</label><br />
    <label class="doctype_disable" title="Над этим документом мы ещё работаем"><input type="checkbox" name="print_PF" value="C6" id="print_PF_16" disabled="disabled"/>Признании условий труда на рабочих местах допустимыми</label><br />
</div>

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
<td align="right"><div id="PoupUpButton"><input type="submit" class="input_button" id="buttonOk" value="Сформировать" onclick="StartPrint();"/><input type="submit" class="input_button" id="buttonClose" value="Закрыть" onclick="return PoupUpMessgeClose();"/></div></td>
</tr>
</table>
<script>
function StartPrint()
{
	var bErr = true;
	var sDV = $("input:radio[name ='print_DV']:checked").val();
	if(sDV == 'diap')
	{
		IsInputValidPrintDiapazonString('#sPrintDV');
		if(bInputValidError)
		{
			SetInputValidFocusOnFirstErrorInput();
			SetInputValidDefaultParams();
			bErr = false;
		}
	}

	if($("input:checkbox[name ='print_PF']:checked").length == 0)
	{
		$('#print_PF_div').addClass('input_wrong');
		bErr = false;
	}
	else
	{
		$('#print_PF_div').removeClass('input_wrong');
	}

	if(bErr)
	{

		var sPrint_diap = '';
		if($("input:radio[name ='print_DV']:checked").val() == 'all') sPrint_diap = 'all'; else sPrint_diap = $('#sPrintDV').val();
		var sPrint_doc = '';
		$("input:checkbox[name ='print_PF']:checked").each(function(key, value)
		{
			sPrint_doc += '_'+$(value).val();
		});

		window.open('work_CreateDoc.php?first='+GetGroupId()+'&second='+sPrint_diap+'&third='+sPrint_doc, '_blank');
//		window.focus();
		PoupUpMessgeClose();
	}
}

function AjaxPrintPressReturn(e)
{
	if (e.keyCode == 13) {
		StartPrint();
	}
}
</script>

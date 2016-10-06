<form action="" method="post" onsubmit="test()">
<table width="200" border="0">
  <tr>
    <td>&nbsp;</td>
    <td><input name="val1" type="text" class="input_field input_field_715 input_field_background" id="sLogin" size='100'/>Пункт по мед осмотрам:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="val2" type="text" class="input_field input_field_715 input_field_background" id="sPass" size='100'/>Пункт по химии:</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><label>
	<?php 
	//Тут обрабатывается гет
	if (isset($_GET['val1']) && isset($_GET['val2'])) //Проверка, пришли ли значения get
				{
					echo 'Первое значение: '.$_GET['val1'].'</br>';//Вывод
					echo 'Второе значение: '.$_GET['val2'];
				}
	
	?>
    </label></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><input name="button" type="submit" class="input_button" id="button" value="Поиск" /></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>

<script>
function test()
{
	alert($('#val2').val());
}
</script>



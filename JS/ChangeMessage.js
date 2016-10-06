//Библиотека уведомлений
//Наглядно информирует пользователя о текущем состоянии документа
//Отображение <div id="ChangeMessageDisplay"></div>
//Инициируется/Сбрасывается ChangeMessageSave();
//Подключается к контролируемым объектам onchange="ChangeMessageChange();" onkeypress="ChangeMessageChange();"
function ChangeMessageChange()
{
	$("#ChangeMessageDisplay").removeClass("green");
	$("#ChangeMessageDisplay").addClass("red");
	$("#ChangeMessageDisplay").html("Изменения не сохранены");
}
function ChangeMessageSave()
{
	$("#ChangeMessageDisplay").removeClass("red");
	$("#ChangeMessageDisplay").addClass("green");
	$("#ChangeMessageDisplay").html("Все изменения сохранены");
}
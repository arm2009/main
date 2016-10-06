<?php
	include_once('MainWork/WorkCalc.php');
//Тестовые хреньки
//	WorkCalc::Add_Event(16, 'Test', 'Info', '111', '05-02-2014', '05-08-2015');

	if (isset($_POST['action']))
	{
		switch ($_POST['action'])
		{
			case 'add_event':
				WorkCalc::Add_Event($_POST['idWorkGroup'],$_POST['sName'],$_POST['sInfo'],$_POST['sSerial'],$_POST['dDateStart'],$_POST['dDateEnd']);
			break;
			case 'get_event':
			break;
			
			case 'edit_event':
			break;
			
			case 'get_event_list':
			break;
		}
	}	
?>
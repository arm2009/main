<?php
	include_once('MainWork/GroupWork.php');
	include_once('MainWork/WorkPlace.php');
	include_once('MainWork/WorkFactors.php');
	include_once('MainWork/WorkCalc.php');
	include_once('Util/String.php');

	if (isset($_POST['action']))
	{
		switch ($_POST['action'])
		{
			case 'checkTime':
			if (isset($_POST['idRm']))
			{
				echo (WorkFactors::GetTime($_POST['idRm']));
				break;
			}
			case 'addComiss':
			if (isset($_POST['idGroup']))
			{
				$newId = GroupWork::AddComiss($_POST['sName'], $_POST['sPost'], $_POST['idGroup']);
				echo GetDivComiss($_POST['sName'], $_POST['sPost'], $newId);
				break;
			}
			case 'readComiss':
			{
				if (isset($_POST['id']))
				{
					echo json_encode(GroupWork::ReadOneComiss($_POST['id']));
				}
				break;
			}
			case 'editComiss':
			{
				if (isset($_POST['id']))
				{
					echo ($_POST['sName']);
					GroupWork::EditComiss($_POST['id'], $_POST['sName'], $_POST['sPost']);
				}
				break;
			}
			case 'delComiss':
			{
				if (isset($_POST['id']))
				{
					GroupWork::DelComiss($_POST['id']);
				}
				break;
			}
			case 'readWorkPlacesFolders':
			{
				if (isset($_POST['idGroup']))
				{
					echo json_encode(WorkPlace::GetWorkPlaseList($_POST['idGroup']));
				}
				break;
			}
			case 'readWorkPlaces':
			{
				if (isset($_POST['idGroup']))
				{
					echo json_encode(WorkPlace::GetWorkPlaseList($_POST['idGroup'], 'false'));
				}
				break;
			}
			case 'addWorkPlace':
			{
				if (isset($_POST['idGroup']))
				{
					echo (WorkPlace::AddWorkPlace($_POST['idGroup'], $_POST['idParent'], $_POST['sName'], $_POST['sOk'], $_POST['sPrefix'], $_POST['sNum'], $_POST['sEtks']));
				}
			break;
			}
			case 'delWorkPlace':
			{
				if (isset($_POST['id']))
				{
					WorkPlace::DelWorkPlace($_POST['id'], $_POST['idGroup']);
				}
			break;
			}
			case 'saveWorkPlace':
			{
				if (isset($_POST['id']))
				{
					echo($_POST['fWorkDay']);
					WorkPlace::ChangeWorkPlaceAll($_POST['id'], $_POST['sName'], $_POST['sOk'], $_POST['sPrefix'], $_POST['sNum'], $_POST['sNumAnalog'], $_POST['sETKS'], $_POST['sCount'], $_POST['sCountWoman'], $_POST['sCountYouth'], $_POST['sCountDisabled'], $_POST['sSnils'], $_POST['sDateCreate'], $_POST['fWorkDay']);
				}
				break;
			}
			case 'getMaxId':
			{
				if (isset($_POST['idGroup']))
				{
					echo WorkPlace::GetMaxNumber($_POST['idGroup']);
				}
				break;
			}
			case 'readPoints':
			{
				if (isset($_POST['idRm']))
				{
					echo json_encode(WorkFactors::GetPointsList($_POST['idRm']));
				}
				break;
			}
			case 'readPoint':
			{
				if (isset($_POST['idPoint']) && isset($_POST['idRm']))
				{
					echo json_encode(WorkFactors::GetPoint($_POST['idPoint'], $_POST['idRm']));
				}
				break;
			}
			case 'editPoint':
			{
				if (isset($_POST['idPoint']) && isset($_POST['idRm']))
				{
					WorkFactors::EditPoint($_POST['idRm'], $_POST['idPoint'], $_POST['sName'],makeToFloat($_POST['sTime']),$_POST['iType']);
				}
				break;
			}
			case 'EditPointAddLight':
			{
				if (isset($_POST['idFactor']))
				{
					WorkFactors::EditPointAddLight($_POST['idFactor'], $_POST['sLightPolygone'], $_POST['sLightHeight'], $_POST['sLightDark'], $_POST['sLightType']);
				}
				break;
			}
			case 'addPoint':
			{
				if (isset($_POST['idRm']))
				{
					echo (WorkFactors::AddPoint($_POST['idRm'], $_POST['sName'], $_POST['idGroup'], $_POST['sTime'], $_POST['iType']));
				}
				break;
			}
			case 'delPoint':
			{
				if (isset($_POST['idPoint']) AND isset($_POST['idRm']))
				{
					WorkFactors::DelPoint($_POST['idPoint'], $_POST['idRm']);
				}
				break;
			}
			case 'addFactor':
			{
				if (isset($_POST['idPoint']) AND isset($_POST['idFactor']) AND isset($_POST['idRm']))
				{
					echo json_encode(WorkFactors::AddFactor($_POST['idPoint'], $_POST['idFactor'], $_POST['sType'],$_POST['idRm']));
				}
				break;
			}
			case 'readFactors':
			{
				if (isset($_POST['idPoint']) AND isset($_POST['idRM']))
				{
					echo json_encode(WorkFactors::GetFactorsList($_POST['idPoint'], $_POST['idRM']));
				}
			}
			case 'readFactor':
			{
				if (isset($_POST['id']) AND isset($_POST['idRM']))
				{
					echo json_encode(WorkFactors::ReadFactor($_POST['id'], $_POST['idRM']));
				}
				break;
			}
			case 'delFactor':
			{
				if (isset($_POST['id']))
				{
					WorkFactors::DelFactor($_POST['id']);
				}
				break;
			}
			case 'setArchive':
			{
				if (isset($_POST['idGroup']))
				{
					GroupWork::SetStatus($_POST['idGroup'], 'archive');
				}
				break;
			}
			case 'setDelete':
			{
				if (isset($_POST['idGroup']))
				{
					GroupWork::SetStatus($_POST['idGroup'], 'deleted');
				}
				break;
			}
			case 'editFactor':
			{
				if (isset($_POST['inIdFactor']) && isset($_POST['inIdRm']) && isset($_POST['fFact1']) && isset($_POST['fPdu1']) && isset($_POST['dControl']))
				{
					echo json_encode(WorkFactors::EditFactor($_POST['inIdFactor'], $_POST['inIdRm'], makeToFloat($_POST['fFact1']), makeToFloat($_POST['fPdu1']), $_POST['dControl'], makeToFloat($_POST['fFact2']), makeToFloat($_POST['fPdu2']), makeToFloat($_POST['fFact3']), makeToFloat($_POST['fPdu3']), makeToFloat($_POST['fFact4']), makeToFloat($_POST['fPdu4']), makeToFloat($_POST['fFact5']), makeToFloat($_POST['fPdu5'])));
				}
				break;
			}
			case 'saveWarranty':
			{
				if (isset($_POST['idRm']))
				{
					WorkPlace::SaveWarranty($_POST['idRm'], $_POST['iCompSurcharge'], $_POST['sCompBaseSurcharge'], $_POST['sCompFactSurcharge'], $_POST['iCompVacation'], $_POST['sCompBaseVacation'], $_POST['sCompFactVacation'], $_POST['iCompShortWorkDay'], $_POST['sCompBaseShortWorkDay'], $_POST['sCompFactShortWorkDay'], $_POST['iCompMilk'], $_POST['sCompBaseMilk'], $_POST['sCompFactMilk'], $_POST['iCompFood'], $_POST['sCompBaseFood'], $_POST['sCompFactFood'], $_POST['iCompPension'], $_POST['sCompBasePension'], $_POST['sCompFactPension'], $_POST['iCompPhysical'], $_POST['sCompBasePhysical'], $_POST['sCompFactPhysical']);
				}
				break;
			}
			case 'ReadActions':
			{
				if (isset($_POST['idRm']))
				{
					echo json_encode(WorkPlace::GetActivityList($_POST['idRm']));
				}
				break;
			}
			case 'readAction':
			{
				if (isset($_POST['id']))
				{
					echo json_encode(WorkPlace::ReadActivity($_POST['id']));
				}
				break;
			}
			case 'AddActions':
			{
				if (isset($_POST['idRm']))
				{
					echo WorkPlace::AddActivity($_POST['idRm'],$_POST['sActivityName'],$_POST['sActivityTarget'],$_POST['sTerm'],$_POST['sInvolved'],$_POST['sMark'],$_POST['iType']);
				}
				break;
			}
			case 'DelActions':
			{
				if (isset($_POST['id']))
				{
					echo WorkPlace::DelActivity($_POST['id']);
				}
				break;
			}
			case 'editActions':
			{
				if (isset($_POST['inId']))
				{
					echo json_encode(WorkPlace::EditActivity($_POST['inId'], $_POST['sActivityName'],$_POST['sActivityTarget'],$_POST['sTerm'],$_POST['sInvolved'],'',$_POST['iType']));
				}
				break;
			}
			case 'saveSIZ':
			{
				if (isset($_POST['idRm']))
				{
					echo WorkPlace::SaveSiz($_POST['idRm'], $_POST['sSIZbase'], $_POST['dSizDate'], $_POST['iSIZCard'], $_POST['iSIZEffect'], $_POST['iSIZOFact'], $_POST['iSIZOProtect'], $_POST['iSIZOEffect']);
				}
				break;
			}
			case 'ReadSiZs':
			{
				if (isset($_POST['idRm']))
				{
					echo json_encode(WorkPlace::GetSizList($_POST['idRm']));
				}
				break;
			}
			case 'ReadSiZ':
			{
				if (isset($_POST['id']))
				{
					echo json_encode(WorkPlace::ReadSiz($_POST['id']));
				}
				break;
			}
			case 'AddSiz':
			{
				if (isset($_POST['idRm']))
				{
					echo(WorkPlace::AddSiz($_POST['idRm'], $_POST['sSizName'], $_POST['iFact'], $_POST['sSert'], $_POST['sProtectFactor']));
				}
				break;
			}
			case 'DelSiz':
			{
				if (isset($_POST['id']))
				{
					echo(WorkPlace::DelSiz($_POST['id']));
				}
				break;
			}
			case 'EditSiz':
			{
				if (isset($_POST['idSiz']))
				{
					echo json_encode(WorkPlace::EditSiz($_POST['idSiz'], $_POST['sSizName'],$_POST['iFact'],$_POST['sSert'],$_POST['sProtectFactor']));
				}
				break;
			}
			case 'ImportSiz':
			{
				if (isset($_POST['idDonor']))
				{
					WorkPlace::ImportSiz($_POST['idDonor'], $_POST['idRecepient']);
				}
				break;
			}
			case 'SetAllCreateDate':
			{
				if (isset($_POST['inIdGroup']))
				{
					WorkPlace::SetAllCreateDate($_POST['inIdGroup'], $_POST['dDateCreate'], $_POST['dDateControl'], $_POST['dNewDateSiz']);
				}
				break;
			}
			case 'ImportWaranty':
			{
				if (isset($_POST['idDonor']))
				{
					WorkPlace::ImportWaranty($_POST['idDonor'], $_POST['idRecepient']);
				}
				break;
			}
			case 'ImportActions':
			{
				if (isset($_POST['idDonor']))
				{
					WorkPlace::ImportActions($_POST['idDonor'], $_POST['idRecepient']);
				}
				break;
			}
			case 'FastActions':
			{
				if (isset($_POST['idRm']))
				{
					WorkPlace::FastActions($_POST['idRm']);
				}
				break;
			}
			case 'FastWarranty':
			{
				if (isset($_POST['idRm']))
				{
					WorkPlace::FastWarranty($_POST['idRm']);
				}
				break;
			}
			case 'SetAllCreateAction':
			{
				if (isset($_POST['inIdGroup']))
				{
					WorkPlace::SetAllCreateAction($_POST['inIdGroup']);
				}
				break;
			}
			case 'SetAllCreateWarranty':
			{
				if (isset($_POST['inIdGroup']))
				{
					WorkPlace::SetAllCreateWarranty($_POST['inIdGroup']);
				}
				break;
			}
			case 'SetAllAsset':
			{
				if (isset($_POST['inIdGroup']))
				{
					WorkFactors::SetAllAsset($_POST['inIdGroup']);
				}
				break;
			}
			case 'SetAllNums':
			{
				if (isset($_POST['sFirstNum']))
				{
					WorkPlace::SetAllNums($_POST['inIdGroup'],$_POST['sFirstNum']);
				}
				break;
			}
			case 'add_event':
				WorkCalc::Add_Event($_POST['idWorkGroup'],$_POST['sName'],$_POST['sInfo'],$_POST['sSerial'],$_POST['dDateStart'],$_POST['dDateEnd']);
			break;
			case 'Remove_Event':
				WorkCalc::Remove_Event($_POST['idEvent']);
			break;
			case 'get_event':
				echo json_encode(WorkCalc::Get_Event($_POST['idEvent']));
			break;
			case 'edit_event':
				WorkCalc::Edit_Event($_POST['id'],$_POST['idWorkGroup'],$_POST['sName'],$_POST['sInfo'],$_POST['sSerial'],$_POST['dDateStart'],$_POST['dDateEnd']);
			break;
			case 'get_event_list':
			break;
		}
	}

	function GetDivComiss($sName, $sPost, $id, $sStyleDisplay = 'display: none;')
	{
		return '<div style="'.$sStyleDisplay.'" class="block_micro block_left_round block_right_round block_user pointer block_edit" tag ="'.$id.'" title="Изменить сведения" id="popup" onClick="ClickComiss(this)">'.$sName.'<br /><span class="comment">'.$sPost.'</span></div>';
	}

?>

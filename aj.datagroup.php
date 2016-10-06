<?
	include_once "LowLevel/dataCrypt.php";
	include_once "UserControl/userControl.php";
	include_once "LowLevel/userValidator.php";
	include_once "MainWork/GroupWork.php";
	include_once "Util/String.php";

		$aArray = GroupWork::FillWorkSpace();
		
		foreach($aArray as $aArr)
		{
			$aArrayIds[] = $aArr[0];				
		}
		
		$idWorkSpaces = implode(',',$aArrayIds);
	
	$sql = 'SELECT DECODE(Arm_users.sOrgName,"04022009") as sNameSpace, Arm_group.id as id, Arm_group.sName as sName FROM Arm_users, Arm_group WHERE Arm_users.id = Arm_group.idParent AND Arm_users.id IN ('.$idWorkSpaces.') AND Arm_group.sName LIKE "%'.DbConnect::ToBaseStr($_GET['term']).'%" AND Arm_group.sStatus = "";';
 	$result = DbConnect::GetSqlQuery($sql);	

	if (mysql_num_rows($result) > 0)
	{
		while($vRow = mysql_fetch_array($result))
		{
			$row['value']=str_replace("&quot;", '"', $vRow[sName]);
			$row['sNameSpace']=str_replace("&quot;", '"', $vRow[sNameSpace]);
			$row['id']=$vRow[id];
			$aResult[] = $row;
		}
	}
	echo json_encode($aResult);
?>
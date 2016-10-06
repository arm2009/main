<?php
	include_once('LowLevel/dbConnect.php');
	
	$sSelMask = '';
	$vResult = null;
	$sql = '';
	$iCountOnPage = 50;
	$iPage = 1;
	
	if (isset($_POST[iPage]))
	{
		$iPage = $_POST[iPage];
	}
	
	$iStartPage = ($iPage * $iCountOnPage) - $iCountOnPage;
	$iEndPage = ($iPage * $iCountOnPage);
	
	if ($_POST[sSelMask])
	{
		$sSelMask == $_POST[sSelMask];
		$sql = 'SELECT id, sName FROM Nd_gn1313 WHERE sName LIKE "%'.$sSelMask.'%" LIMIT '.$iStartPage.', '.$iEndPage.';';
		$sqlT = 'SELECT id FROM Nd_gn1313 WHERE sName LIKE "%'.$sSelMask.'%";';
	}
	else
	{
		$sql = 'SELECT id, sName FROM Nd_gn1313 LIMIT '.$iStartPage.', '.$iEndPage.';';
		$sqlT = 'SELECT id, sName FROM Nd_gn1313;';
	}

	$vResult = DbConnect::GetSqlQuery($sql);
	$vResultT = DbConnect::GetSqlQuery($sqlT);
	
	//Подсчет общего количества страниц и округление в большую сторону
	$iPageCount = ceil(mysql_num_rows($vResultT) / $iCountOnPage);
	
	if (mysql_num_rows($vResult) > 0)
	{
		$aResult = array();
		while($row = mysql_fetch_array($vResult))
				{
					array_push($aResult, $row);
				}

		
		$ret_json = array('aResult' => $aResult, 'iPageCount' => $iPageCount);
		echo json_encode($ret_json);
	}

?>
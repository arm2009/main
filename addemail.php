<?
	function SQLquery($query)
	{
		$SQL_Host = "kctrud.mysql";
		$SQL_Root = "kctrud_mysql";
		$SQL_Psw = "mmjstjnb";
		$SQL_DB = "kctrud_arm2009";
			
		$con=mysqli_connect($SQL_Host, $SQL_Root, $SQL_Psw, $SQL_DB);
		// Check connection
		if (mysqli_connect_errno())
		  {
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }
		$result = mysqli_query($con, 'SET NAMES UTF8');	
//		echo($query);
		$result = mysqli_query($con, $query);
		mysqli_close($con);
		return $result;
	}
	
	if(isset($_GET[setmail]))
	{
		if(!filter_var($_GET[setmail], FILTER_VALIDATE_EMAIL))
		{
			echo('0');
		}
		else
		{
			$sql = "INSERT INTO `kctrud_arm2009`.`nu_subscribers` (`id`, `email`, `create_datetime`) VALUES (NULL, '".$_GET[setmail]."', NOW());";
			SQLquery($sql);
			echo('1');
		}
	}
	
	if(isset($_GET[statistic]))
	{
		$sql = "INSERT INTO `kctrud_arm2009`.`nu_downloadstatistic` (`id`, `file_name`, `download_datetime`) VALUES (NULL, '".$_GET[statistic]."', NOW());";
		SQLquery($sql);
	}	
?>
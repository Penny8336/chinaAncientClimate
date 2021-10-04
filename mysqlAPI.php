<?php

	$sqlEvent = 10;//$_GET['event'];
	$sqlYearStart = $_GET['start'];
	$sqlYearEnd = $_GET['end'];
	$sqlProvin = explode(".",$_GET['provin']);
	
	// echo '1.'.$sqlEvent.'<BR>';
	// echo '2.'.$sqlYearStart.'<BR>';
	// echo '3.'.$sqlYearEnd.'<BR>';
	// echo '4.'.$_GET['provin'].'<BR>';
	$table = json_decode(file_get_contents("category.json"), true);

	$condition1 = "( year_greg_st > ".$sqlYearStart." and year_greg_st < ".$sqlYearEnd.")";
	$condition2 = "( year_greg_ed > ".$sqlYearStart." and year_greg_ed < ".$sqlYearEnd.")";
	$link = mysqli_connect("140.122.184.245:3306", "Midterm", "", "historical_climate") or die("無法開啟MySQL資料庫連接!<br/>");

	$allData = array();
	foreach($sqlProvin as $nowProvin){
		//echo $nowProvin.".<BR>";
		$condition3 = "( place_provin = '".$nowProvin."' )";
		$sql = "SELECT * FROM RECORD where ( ".$condition1." or ".$condition2." ) and ".$condition3;
		//$sql = "SELECT * FROM RECORD where ( ".$condition1." or ".$condition2." ) ";
		//echo $sql.".<BR>";
		$result = mysqli_query($link, $sql);
		if(!$result){
			echo ("Error: ".mysqli_error($link));
		exit();
		}

		$subCategoryData = array();
		foreach(array_keys($table[$sqlEvent][0]) as $subCategoryCode){
			$subCategoryData[$subCategoryCode] = [];
		}
		//$json = json_encode($subCategoryData);
		// echo $json."<BR><BR>";
		// echo '<BR><BR>';
		// print_r($subCategoryData["25"]);
		

		$timeRange = array();
		$provin = array();
		$city = array();
		$event = array();
		$source = array();
		while($row = mysqli_fetch_array($result)){
			//echo $row['event_code']."<BR>";
			$temp = explode(";",$row['event_code']);
			foreach($temp as $code){
				$code_1 = substr($code,0,2);
				$code_2 = substr($code,2,2);
				$code_3 = substr($code,4,3);
				//echo $code_1.$code_2.$code_3.'=='.$code.'<BR>';
				if($code_1 == $sqlEvent){
					if($row['mon_greg_st'] == -9999)
						$row['mon_greg_st'] = 1;
					if($row['mon_greg_ed'] == -9999)
						$row['mon_greg_ed'] = 12;
					$eventObj = new stdClass();
					$eventObj -> timeRange = $row['year_greg_st']."-".$row['mon_greg_st'].",".$row['year_greg_ed']."-".$row['mon_greg_ed'];
					// if(empty($table[$code_1][0][$code_2][0][$code_3]))
						// echo 'FK:'.$code_1.':'.$code_2.':'.$code_3.'<BR><BR>'.$row['record_ID'];
					@$eventObj -> val = $table[$code_1][0][$code_2][0][$code_3];
					array_push($subCategoryData[$code_2],$eventObj);
				}
			}
			//echo "<BR>";
		}
		// $json = json_encode($subCategoryData);
		// echo $json."<BR><BR>";

		$provinData = array();
		//print_r($subCategoryData);

		foreach(array_keys($subCategoryData) as $subCategoryCode){
			if(!empty($subCategoryData[$subCategoryCode])){
				//echo $table[$sqlEvent][0][$subCategoryCode][1]["name"].'<BR>';
				$provinDataObj = new stdClass();
				$provinDataObj -> label = $table[$sqlEvent][0][$subCategoryCode][1]["name"];
				$provinDataObj -> data = $subCategoryData[$subCategoryCode];
				array_push($provinData,$provinDataObj);
			}
		}
		$allProvinObj = new stdClass();
		$allProvinObj -> group = $nowProvin;
		$allProvinObj -> data = $provinData;

		array_push($allData,$allProvinObj);
	}
	$json = json_encode($allData);
	echo $json;
	


?>
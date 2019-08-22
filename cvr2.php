<?php


echo "현재 날짜 : ". date("Y-m-d")."<br/><br/>";
$yesterday = date("Y-m-d", strtotime("-1 day", time()));
echo $yesterday;

echo "<br>";
$yesterdayY = substr($yesterday, 2, 2);
$yesterdayM = substr($yesterday, 5, 2);
$yesterdayD = substr($yesterday, 8, 2);
echo $yesterdayY;
echo "<br>";
echo $yesterdayM;
echo "<br>";
echo $yesterdayD;
echo "<br>";

$yesterday2 = $yesterdayY.$yesterdayM.$yesterdayD;
 

$fp = fopen("/var/log/httpd/access_log", "r") ;

// 파일 내용 출력
while( !feof($fp) ) {
	$str = fgets($fp);

	$strArr = explode(" ", $str);


	$pos = array_search($strArr[0], $ipArr);

	echo $str;
	echo "<br>";


	// if ($strArr[6] == '/ci/index.php/Controller_EC/index') {
		
	// 	$count_pv++;

	// 	if ($pos !== false) {
		    
	// 	}else{
	// 		array_push($ipArr, $strArr[0]) ;

	// 		$count_uu++;
	// 		if ($d === '06') {
				
	// 		}
	// 	}
	// }else if ($strArr[6] == '/ci/index.php/Controller_EC/quick_order') 
	// {
	// 	$cellSum++;
	// }


}

// 파일 닫기
fclose($fp);



// $db_host = "localhost"; 
// $db_user = "root"; 
// $db_passwd = "dl4064";
// $db_name = "test"; 
// $conn = mysqli_connect($db_host,$db_user,$db_passwd,$db_name);

// if (mysqli_connect_errno($conn)) {
// echo "데이터베이스 연결 실패: " . mysqli_connect_error();
// } else {
// echo "성공~!!!";
// }

// $insert_query = "INSERT INTO cvr_tb VALUES('$today', '$count_pv', '$count_uu', '$cellSum', '$cvr')";
// mysqli_query($conn, $insert_query);

// mysqli_close($conn);







?>
<?php


echo "오늘 날짜";
echo "<br>";
echo date("Y-m-d");
echo "<br><br>";

echo "어제 날짜";
echo "<br>";
$yesterday = date("Y-m-d", strtotime("-1 day", time()));
echo $yesterday;
echo "<br><br>";

$yesterdayY = substr($yesterday, 2, 2);
$yesterdayM = substr($yesterday, 5, 2);
$yesterdayD = substr($yesterday, 8, 2);
echo $yesterdayY;
echo "<br>";
echo $yesterdayM;
echo "<br>";
echo $yesterdayD;
echo "<br>";
$yesterday_filename = $yesterdayY.$yesterdayM.$yesterdayD;
echo $yesterday_filename;
echo "<br><br>";



$count_uu = 0;
$count_pv = 0;
$cellSum = 0;

$ipArr = array();

$flag = true;

$lines = file('/etc/httpd/logs/access_log_'.$yesterday_filename);
foreach($lines as $str) {
   	//echo $str;
   	$strArr = explode(" ", $str);

   	$pos = array_search($strArr[0], $ipArr);

   	echo $strArr[0];
   	echo "<br>";
	echo $strArr[6];
   	echo "<br><br>";

   	if ($strArr[6] == '/ci/index.php/Controller_EC/index') 
   	{
		$count_pv++;

		if ($pos !== false) {
		    
		}else{
			array_push($ipArr, $strArr[0]) ;
			$count_uu++;
		}

	}else if ($strArr[6] == '/ci/index.php/Controller_EC/quick_order') 
	{
		$cellSum++;
	}
  
}




$db_host = "localhost"; 
$db_user = "root"; 
$db_passwd = "dl4064";
$db_name = "ecshop"; 
$conn = mysqli_connect($db_host,$db_user,$db_passwd,$db_name);

if (mysqli_connect_errno($conn)) {
echo "데이터베이스 연결 실패: " . mysqli_connect_error();
} else {
echo "성공~!!!";
}

$query = "select * from order_main where DATE(od_date) = '$yesterday'";
$sum = mysqli_query($conn, $query);
//$sum->field_count; 
//print_r($sum);

$cvr = $sum->field_count / $count_pv * 100;



//echo "tuyui";
//print_r($sum) ;
echo $sum->field_count; 

mysqli_close($conn);



$db_host = "localhost"; 
$db_user = "root"; 
$db_passwd = "dl4064";
$db_name = "test"; 
$conn = mysqli_connect($db_host,$db_user,$db_passwd,$db_name);

if (mysqli_connect_errno($conn)) {
echo "데이터베이스 연결 실패: " . mysqli_connect_error();
} else {
echo "성공~!!!";
}


//$insert_query = "INSERT INTO cvr_tb VALUES('$yesterday', '$count_pv', '$count_uu', '$sum->field_count', '$cvr')";
//mysqli_query($conn, $insert_query);

$sql  = "SELECT * FROM cvr_tb WHERE date = '$yesterday'";


// 完成済みのSELECT文を実行する
//$sql = "SELECT user_id, name FROM user_table";
if ($result = $conn->query($sql)) {
    // 連想配列を取得
    while ($row = $result->fetch_assoc()) {
        echo $row["date"] .' // '. $row["pv"] .' // '.  $row["uu"] .' // '. $row["cvr"] . "<br>";
    }
    // 結果セットを閉じる
    $result->close();
}



// $yesterday_cvr = mysqli_query($conn, $query);
// print_r($yesterday_cvr);

mysqli_close($conn);

//$sum->num_rows;


$src = '';
$src .= '------------------------------';
$src .= '<br>';
$src .= '日付：';
$src .= $yesterday;
$src .= '<br>';
$src .= 'PV：';
$src .= $count_pv;
$src .= '<br>';
$src .= 'UU：';
$src .= $count_uu;
$src .= '<br>';
$src .= '注文数：';
$src .= $sum->field_count;
$src .= '<br>';
$src .= 'CVR：';
$src .= $cvr;
$src .= '<br>';
$src .= '------------------------------';
echo $src;

// $fp = fopen( 'a.txt','w' ) ;
// echo "aaa";

// while ( !feof($fp) ) {

// 	$value = fgets($fp, filesize('/var/log/httpd/access_log_190816'));

// 	echo $value.'<br>';

// }

//fclose($fp);

echo "들어온 사람 IP주소 리스트<br>";
for ($i=0; $i < sizeof($ipArr); $i++) { 
	echo $ipArr[$i];
	echo "<br>";
}





?>
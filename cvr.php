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
 
// $todayD = (int)$todayD - 1;
// if ($todayD < 10) {
// 	$todayD = '0'.(string)$todayD;
// }
// echo $todayD;
// echo "<br>";
// $yesterday = $todayY.$todayM.$todayD;
// echo $yesterday;
// echo "<br>";

$fp = fopen("/var/log/httpd/access_log.".$yesterday2, "r") ;
$count_uu = 0;
$count_pv = 0;
$cellSum = 0;


$ipArr = array();

 // array_push($ipArr, '123');
 // array_push($ipArr, '123222');

$flag = true;
$today = '';

// 파일 내용 출력
while( !feof($fp) ) {
	$str = fgets($fp);

	$strArr = explode(" ", $str);


	$pos = array_search($strArr[0], $ipArr);


	//echo $str;
	//날짜입니다.
	echo $strArr[6];
	
	// IP입니다.
	//echo $strArr[6];
	echo "<br>";

	$y = substr($strArr[3], 8, 4);
	$m = substr($strArr[3], 4, 3);
	
	switch ($m) 
	{
		case 'Jan': $m = '01'; break;	
		case 'Feb': $m = '02'; break;
		case 'Mar': $m = '03'; break;	
		case 'Apr': $m = '04'; break;
		case 'May': $m = '05'; break;	
		case 'Jun': $m = '06'; break;
		case 'Jul': $m = '07'; break;	
		case 'Aug': $m = '08'; break;
		case 'Sep': $m = '09'; break;	
		case 'Oct': $m = '10'; break;
		case 'Nov': $m = '11'; break;	
		case 'Dec': $m = '12'; break;		
	}

	$d = substr($strArr[3], 1, 2);
	$h = substr($strArr[3], 13, 2);
	$i = substr($strArr[3], 16, 2);
	$s = substr($strArr[3], 19, 2);

	$date = $y."-".$m."-".$d;
	//echo $date;
	//echo "<br>";
	$time = $h.":".$i.":".$s;
	//echo $time;
	//echo "<br>";
	//echo "<br>";

	if ($flag === true) 
	{
		$today = $date;
		$flag = false;
	}


	if ($strArr[6] == '/ci/index.php/Controller_EC/index') {
		
		$count_pv++;

		if ($pos !== false) {
		    
		}else{
			array_push($ipArr, $strArr[0]) ;

			$count_uu++;
			if ($d === '06') {
				
			}
		}
	}else if ($strArr[6] == '/ci/index.php/Controller_EC/quick_order') 
	{
		$cellSum++;
	}


}

$beforeDay = date("Y-m-d", strtotime($day." -1 day"));

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
$src .= $cellSum;
$src .= '<br>';
$src .= 'CVR：';
$src .= $cellSum / $count_pv * 100;
$src .= '<br>';
$src .= '------------------------------';
echo $src;

echo "<hr>";
echo "오늘날짜<br>";
echo $today;
echo "<hr>";

echo "pv (메인페이지 접속할 때마다 오름.)<br>";
echo $count_pv;
echo "<hr>";

echo "uu (새로운 IP접속할때마다 오름)<br>";
echo $count_uu;
echo "<hr>";

echo "cellSum (주문수)<br>";
echo $cellSum;
echo "<hr>";
$cvr = $cellSum / $count_pv * 100;
echo "CVR (「注文数÷PV数×100」)<br>";
echo $cellSum / $count_pv * 100;
echo "<hr>";

echo "들어온 사람 IP주소 리스트<br>";
for ($i=0; $i < sizeof($ipArr); $i++) { 
	echo $ipArr[$i];
	echo "<br>";
}
 
// 파일 닫기
fclose($fp);



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

$insert_query = "INSERT INTO cvr_tb VALUES('$today', '$count_pv', '$count_uu', '$cellSum', '$cvr')";
mysqli_query($conn, $insert_query);

mysqli_close($conn);







?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctr extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function allItemRanking()
	{
		$this->load->model('Model');
		$orderInfo = $this->Model->rankData(); 
		$message = "";
		$rank = 1;
		$message .= '
			<h2>ランキング</h2>
			<table border="1">
			<tr>
			<th width="100px">順位</th>
			<th width="200px">イメージ</td>
			<th width="200px">商品名</td>
			<th width="100px">販売数量</td>
			</tr>
			</table>
		';
		foreach ($orderInfo as $ls) {
			$message .= '
			<p></p>
			<table border="1">
			<tr>
			<th width="100px">'.$rank.'</th>
			<th width="200px"><img width="100px" src="/ci'.$ls->pd_img.'"</td>
			<th width="200px">'.$ls->pd_name.' </td>
			<th width="100px">'.$ls->sum.' </td>
			</tr>
			</table>
		';
			$rank++;
		}
		echo $message;
	}

	public function yesterdayRanking()
	{
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));
		$this->load->model('Model');
		$orderInfo = $this->Model->rankDataY($dateOfYesterday); 
		$message = "";
		$rank = 1;
		$message .= '
			<h2>「'.$dateOfYesterday.'」のランキング</h2>
			<table border="1">
			<tr>
			<th width="100px">順位</th>
			<th width="200px">イメージ</td>
			<th width="200px">商品名</td>
			<th width="100px">総注文数</td>
			</tr>
			</table>
		';
		foreach ($orderInfo as $ls) {
			$message .= '
			<p></p>
			<table border="1">
			<tr>
			<th width="100px">'.$rank.'</th>
			<th width="200px"><img width="100px" src="/ci'.$ls->pd_img.'"</td>
			<th width="200px">'.$ls->pd_name.' </td>
			<th width="100px">'.$ls->sum.' </td>
			</tr>
			</table>
		';
			$rank++;
		}
		echo $message;
	}

	public function yesterdayRankingNoImg($date)
	{
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));
		$this->load->model('Model');
		$orderInfo = $this->Model->rankDataY($date); 
		$message = "";
		$rank = 1;
		$message .= '
			<br>
			
			<table border="1">
			<tr>
			<th colspan="3">「'.$date.'」の商品ランキング</th>
			</tr>
			<tr>
			<th width="50px">順位</th>
			<th width="180px">商品名</th>
			<th width="70px">総注文数</th>
			</tr>
		';
		foreach ($orderInfo as $ls) {
			$message .= '
			<tr>
			<th width="50px">'.$rank.'</th>
			<th width="180px">'.$ls->pd_name.' </th>
			<th width="70px">'.$ls->sum.' </th>
			</tr>
		';
			$rank++;
		}
		//echo $message;
		$message .= '</table>';
		return $message;
	}

	// 全体CVRレポートのデータ一覧
	public function getCvrInfo()
	{
		$this->load->model('Model');
		$cvrInfo = $this->Model->getAllCvrInfo_db(); 

		foreach ($cvrInfo as $li) {
			echo "「";
			echo "date : ";
			echo $li->date;
			echo " // ";
			echo "PV : ";
			echo $li->count_pv;
			echo " // ";
			echo "UU : ";
			echo $li->count_uu;
			echo " // ";
			echo "注文数 : ";
			echo $li->count_order;
			echo " // ";
			echo "CVR : ";
			echo $li->cvr;
			echo "」<br><br>";
		}
	}

	// ある日の休みを求める
	public function getHoliday($date)
	{
		//today
		$dateOfToday = date("Y-m-d");
		//yesterday
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));
		//休みのかどうか確認するコード　
		$flagOfHoliday = "Weekday"; // Weekday　/ Holiday
		//今日の曜日を取得
		$day = array("日","月","火","水","木","金","土");
		$whatDay = $day[date('w',strtotime($date))];
		//公式の休み
		$thisYear = "2019";
		$fixedHoliday = array(
			$thisYear."-"."01-01",	//元日 		
			$thisYear."-"."01-14",	//成人の日	
			$thisYear."-"."02-11",	//建国記念の日
			$thisYear."-"."03-21",	//春分の日
			$thisYear."-"."04-29",	//昭和の日
			$thisYear."-"."05-01",	//即位の日
			$thisYear."-"."05-03",	//憲法記念日
			$thisYear."-"."05-04",	//みどりの日
			$thisYear."-"."05-05",	//こどもの日
			$thisYear."-"."07-15",	//海の日
			$thisYear."-"."08-11",	//山の日
			$thisYear."-"."09-16",	//敬老の日
			$thisYear."-"."09-23",	//秋分の日
			$thisYear."-"."10-14",	//体育の日
			$thisYear."-"."10-22",	//即位礼正殿の儀
			$thisYear."-"."11-03",	//文化の日
			$thisYear."-"."11-23"	//勤労感謝の日
		);
		//変わる休み
		$NotFixedHoliday = array(
			$thisYear."-"."04-30",	//国民の休日
			$thisYear."-"."05-02",	//国民の休日
			$thisYear."-"."05-06",	//振替休日
			$thisYear."-"."08-12",	//振替休日
			$thisYear."-"."11-04" 	//振替休日
		);

		if ( ($whatDay === "土") || ($whatDay === "日") || (in_array($date, $fixedHoliday)) || (in_array($date, $NotFixedHoliday)) ) 
		{
			$flagOfHoliday = "Holiday"; 
		}

		return $flagOfHoliday;
	}

	public function registData()
	{
		//昨日の日付
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));
		$yesterdayY = substr($dateOfYesterday, 2, 2);
		$yesterdayM = substr($dateOfYesterday, 5, 2);
		$yesterdayD = substr($dateOfYesterday, 8, 2);
		$dateOfYesterday2 = $yesterdayY.$yesterdayM.$yesterdayD;
		//昨日のアクセスログファイル名
		$filenameOfYesterday = '/etc/httpd/logs/access_log_'.$dateOfYesterday2;
		//$filenameOfYesterday = '/etc/httpd/logs/access_log_190821';

		// UUの数
		$count_uu = 0;
		// PVの数
		$count_pv = 0;
		// アクセスすしたIPアドレスを保存
		$ipArr = array();

		//$lines = file('/etc/httpd/logs/access_log_190816');
		//ファイルをラインで読み込む
		$lines = file($filenameOfYesterday);
		foreach($lines as $line) {
		   	$lineArr = explode(" ", $line);
		   	$ipCheck = array_search($lineArr[0], $ipArr);

		   	//echo $lineArr[0]."<br>";
			//echo $lineArr[6]."<br>";

		   	// '/ci/index.php/Controller_EC'が含まれていたらPVの数が増加
		   	if(strpos($lineArr[6], '/ci/index.php/Controller_EC') !== false)
		   	{
				$count_pv++;

				// 有効なIP検索(バリデーション)
				$ipValid = preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $lineArr[0]);
				  
				//新しいIPの場合はIPを保存、UUの数が増加 && 有効なIP検索
				if ($ipCheck === false && $ipValid === 1) {
			
		    		array_push($ipArr, $lineArr[0]) ;
					$count_uu++;
					//echo $lineArr[0]."<br>";
				}
			}
		}
	    
		// 一日前の注文数のデータを取得
		$this->load->model('Model');
		$orderData = $this->Model->getOrderSum($dateOfYesterday);

		//$count_order = sizeof($orderData);
		$count_order = $orderData[0]->count;

		// cvr rate
		if ($count_pv === 0) $cvr = 0;
		else $cvr = round($count_order / $count_pv * 100, 2);

		$cvr_data = array(
                'date' => $dateOfYesterday,
                'count_pv' => $count_pv,
                'count_uu' => $count_uu,
                'count_order' => $count_order,
                'cvr' => $cvr
            );

		// 実行すると一日前のCVRレポートのデータがデータベースにはいる / 1日1回
		// $this->Model->insert_cvr($cvr_data);
		// ファイルの生成 / 1日1回
		// $this->makeFile();
		// 休みのかどうか確認 (Weekday or Holiday)　 

		$src = '';
		$src .= '------------------------------';
		$src .= '<br>';
		$src .= '日付：';
		$src .= $dateOfYesterday;   //???
		$src .= '<br>';
		$src .= 'PV：';
		$src .= $count_pv;
		$src .= '<br>';
		$src .= 'UU：';
		$src .= $count_uu;
		$src .= '<br>';
		$src .= '注文数：';
		$src .= $count_order;
		$src .= '<br>';
		$src .= 'CVR：';
		$src .= $cvr;
		$src .= '<br>';
		$src .= '------------------------------';
		echo $src;

		echo "<br><br>들어온 사람 IP주소 리스트<br>";
		for ($i=0; $i < sizeof($ipArr); $i++) { 
			echo $ipArr[$i];
			echo "<br>";
		}
	}

	// ファイル生成
	public function makeFile()
	{
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));

		$this->load->model('Model');
		$cvrInfo = $this->Model->getCvrInfo_db($dateOfYesterday);

		$txt = '';
		$txt .= "date : ";
		$txt .= $cvrInfo[0]->date;
		$txt .= " // ";
		$txt .= "PV : ";
		$txt .= $cvrInfo[0]->count_pv;
		$txt .= " // ";
		$txt .= "UU : ";
		$txt .= $cvrInfo[0]->count_uu;
		$txt .= " // ";
		$txt .= "注文数 : ";
		$txt .= $cvrInfo[0]->count_order;
		$txt .= " // ";
		$txt .= "CVR : ";
		$txt .= $cvrInfo[0]->cvr;
		echo $txt;

		$myfile = fopen("cvr_file/CVR_".$dateOfYesterday.".txt", "w") or die("Unable to open file!");
		
		fwrite($myfile, $txt);
		fclose($myfile);
	}

	// メールの送信メソッド
	public function submit_mail()
	{
		//today
		$dateOfToday = date("Y-m-d");
		//yesterday
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));

		//一日前のCVRレポートのデータ取得
		$this->load->model('Model');
		$cvrInfo = $this->Model->getCvrInfo_db($dateOfYesterday);

		$to  = 'LEE <tjdals3035@gmail.com>';
		//$to  = '髙井美鈴 <takai@estore.co.jp>';
		$subject = $cvrInfo[0]->date.'のCVRレポート ';
		$message = '
			<body>
			<h3>'.'今日の日付「'.$dateOfToday.'」</h3>
			<table border="1">
			<tr>
			<th colspan="2">「'.$cvrInfo[0]->date.'」のCVRレポート'.'</th>
			</tr>
			<tr>
			<th width="150px">PV</th><th width="150px">'.$cvrInfo[0]->count_pv.'</th>
			</tr>
			<tr>
			<th>UU</th><th>'.$cvrInfo[0]->count_uu.'</th>
			</tr>
			<tr>
			<th>注文数</th><th>'.$cvrInfo[0]->count_order.'</th>
			</tr>
			<tr>
			<th>CVR</th><th>'.$cvrInfo[0]->cvr.'％</th>
			</tr>
			</table>
			</body>
		';

		$rankStr = $this->yesterdayRankingNoImg($dateOfYesterday);
		$message .= $rankStr;

		$flag = true;
		$num = -1;
		$theDate = '';
		while ($flag === true) 
		{
			$flagDate = date("Y-m-d", strtotime($num." day", time()));
			$flagOfHoliday = $this->getHoliday($flagDate);

			// Weekday
			if($flagOfHoliday === "Weekday") 		
			{
				$theDate = $flagDate;
				$flag = false;
			} 
			// Holiday
			else if($flagOfHoliday === "Holiday") 
			{
				$num--;
			} 

		}
		
		if ($theDate !== '') {
			$flagDate = date("Y-m-d", strtotime($num." day", time()));
			$cvrInfo = $this->Model->getCvrInfo_db($flagDate);

			$message = '
			<body>
			<h3>'.'今日の日付「'.$dateOfToday.'」</h3>
			<table border="1">
			<tr>
			<th colspan="2">「'.$cvrInfo[0]->date.'」のCVRレポート'.'</th>
			</tr>
			<tr>
			<th width="150px">PV</th><th width="150px">'.$cvrInfo[0]->count_pv.'</th>
			</tr>
			<tr>
			<th>UU</th><th>'.$cvrInfo[0]->count_uu.'</th>
			</tr>
			<tr>
			<th>注文数</th><th>'.$cvrInfo[0]->count_order.'</th>
			</tr>
			<tr>
			<th>CVR</th><th>'.$cvrInfo[0]->cvr.'％</th>
			</tr>
			</table>
			</body>
		';

			$rankStr = $this->yesterdayRankingNoImg($flagDate);
			$message .= $rankStr;
		}

		

		// echo $message;

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		// Additional headers
		$headers .= 'To: lee@estore.co.jp' . "\r\n";
		$headers .= 'To: lelele <tjdals3035@naver.com>' . "\r\n";
		$headers .= 'From: 店舗マスター（イ ソンミン） <lee@estore.co.jp>' . "\r\n";
		$headers .= 'Cc: tjdals3035@gmail.com' . "\r\n";
		//$headers .= 'Cc: lee@estore.co.jp' . "\r\n";
		//$headers .= 'Cc: lelele <tjdals3035@naver.com>' . "\r\n";

		// $headers .= 'To: ' . "\r\n";
		// $headers .= 'From: 店舗マスター（イ ソンミン） <lee@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 伊藤友香 <y-ito@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 前田文博 <f-maeda@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 八木章 <yagi@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 熊耳理紗 <kumamimi@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 朴美真 <m-park@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 金味祉 <mi-kim@estore.co.jp>' . "\r\n";
		// $headers .= 'Cc: 李省岷 <lee@estore.co.jp>' . "\r\n";

		// $headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

		$flagOfHoliday = $this->getHoliday(date("Y-m-d"));
		// 今日が平日なあメールを送信 / 1日1回 / 非営業日は実行しない
		if($flagOfHoliday === "Weekday") 
		{
			$send = mail($to, $subject, $message, $headers);
			if ($send) $mailReturns = "Mail sent successfully.<br>";
			else $mailReturns = "Mail sent failed.<br>";
			echo $mailReturns;
		}

		
	}












	public function readFile()
	{
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));

		$lines = file('/var/www/html/cvr/cvr_file/CVR_'.$dateOfYesterday.'.txt');
		$txt;
		foreach($lines as $line) {
			$txt = $line;
		}
		//echo $txt."<br>";
		$txtArr = explode(" // ", $txt);

		$categoryArr = array();
		$varArr = array();  		
		foreach ($txtArr as $txts) {
			$txtsArr = explode(" : ", $txts);
			array_push($categoryArr, $txtsArr[0]) ;
			array_push($varArr, $txtsArr[1]) ;
		}

		for ($i=0; $i < sizeof($categoryArr); $i++) { 
			echo $categoryArr[$i];
			echo "<br>";
		}
		echo "<br>";
		for ($i=0; $i < sizeof($varArr); $i++) { 
			echo $varArr[$i];
			echo "<br>";
		}
	}

	public function test()
	{
		phpinfo();
	}

	public function getHoliday2()
	{
		//today
		$dateOfToday = date("Y-m-d");
		//yesterday
		$dateOfYesterday = date("Y-m-d", strtotime("-1 day", time()));
		//休みのかどうか確認するコード　
		$flagOfHoliday = "Weekday"; // Weekday　/ Holiday
		//今日の曜日を取得
		$day = array("日","月","火","水","木","金","土");
		$whatDay = $day[date('w',strtotime($dateOfToday))];
		//公式の休み
		$thisYear = "2019";
		$fixedHoliday = array(
			$thisYear."-"."01-01",	//元日 		
			$thisYear."-"."01-14",	//成人の日	
			$thisYear."-"."02-11",	//建国記念の日
			$thisYear."-"."03-21",	//春分の日
			$thisYear."-"."04-29",	//昭和の日
			$thisYear."-"."05-01",	//即位の日
			$thisYear."-"."05-03",	//憲法記念日
			$thisYear."-"."05-04",	//みどりの日
			$thisYear."-"."05-05",	//こどもの日
			$thisYear."-"."07-15",	//海の日
			$thisYear."-"."08-11",	//山の日
			$thisYear."-"."09-16",	//敬老の日
			$thisYear."-"."09-23",	//秋分の日
			$thisYear."-"."10-14",	//体育の日
			$thisYear."-"."10-22",	//即位礼正殿の儀
			$thisYear."-"."11-03",	//文化の日
			$thisYear."-"."11-23"	//勤労感謝の日
		);
		//変わる休み
		$NotFixedHoliday = array(
			$thisYear."-"."04-30",	//国民の休日
			$thisYear."-"."05-02",	//国民の休日
			$thisYear."-"."05-06",	//振替休日
			$thisYear."-"."08-12",	//振替休日
			$thisYear."-"."11-04" 	//振替休日
		);

		if ( ($whatDay === "土") || ($whatDay === "日") || (in_array($dateOfToday, $fixedHoliday)) || (in_array($dateOfToday, $NotFixedHoliday)) ) 
		{
			$flagOfHoliday = "Holiday"; 
		}

		return $flagOfHoliday;
	}

}
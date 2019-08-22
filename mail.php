<?php
		echo "ddddddddddddddd";
		$mailto="tjdals3035@naver.com";
		 $subject="mail test";
		 $content="test";
		 $result=mail($mailto, $subject, $content, "From: sonjh32@naver.com");
		 
		 if($result){
		  	echo "mail success";
		 }else  {

		 	error_log($result, 0); 	 
		 	echo $result;
		  	echo "mail fail";
		}



// $to = "tjdals3035@naver.com";
// $subject = "Greeting";
// $message = "Hello\nWorld";
// mail( $to, $subject, $message );

// $to = "tjdals3035@naver.com";

//    $subject = "PHP 메일 발송";

//    $contents = "PHP mail()함수를 이용한 메일 발송 테스트";

//    $headers = "From: tjdals3035@naver.com\r\n";



//    mail($to, $subject, $contents, $headers);


?>


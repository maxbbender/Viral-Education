<html>
<head>

</head>
<body>
	<div class="row">
	<?php

$curl = curl_init();

curl_setopt_array($curl, array(
				CURLOPT_URL => "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?text=" . $_GET['word'] . "&voice=es-ES_EnriqueVoice",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
// 						"authorization: Basic msfDWxR0UIFO",
// 						"authorization: Basic MDUxMGM2MWMtMGRjNS00NmZkLWJiNzYtZmU0ZWU0NjcyNzcyOm1zZkRXeFIwVUlGTw==",
						"cache-control: no-cache",
// 						"postman-token: 0510c61c-0dc5-46fd-bb76-fe4ee4672772"
// 						"postman-token: f5664090-16ba-4f94-a531-76715c580691"
				),
				CURLOPT_USERPWD => "0510c61c-0dc5-46fd-bb76-fe4ee4672772:msfDWxR0UIFO", // username:password
		));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo '<embed src="data:audio/ogg;base64,'. base64_encode($response) .'" type="audio/ogg">';
// echo $response;
}?>
</div>
</body>
</html>


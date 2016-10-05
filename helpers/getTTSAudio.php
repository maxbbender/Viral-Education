<?php
	header('Content-Type: application/json; charset=utf-8');
// 	header('Content-Type: audio/ogg; charset=utf-8');
// 	header('Content-Type: text/html; charset=utf-8');
	if (isset($_GET['word'])) {
		$curl = curl_init();
		
		$word = $_GET['word'];
		
		$url = "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?text=" . utf8_decode($word) . "&voice=es-ES_EnriqueVoice";
// 		echo '<br>URL : ' . $url;
		curl_setopt_array($curl, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // HTTP Version
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC, // BASIC AUTH
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_HTTPHEADER => array(
						"cache-control: no-cache",
				),
				CURLOPT_USERPWD => "0510c61c-0dc5-46fd-bb76-fe4ee4672772:msfDWxR0UIFO", // username:password
		));
		
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);
		
		if ($err) {
			echo "cURL Error #:" . $err;
		} else {
// 			echo '<embed src="data:audio/ogg;base64,'. base64_encode($response) .'" type="audio/ogg">';
			echo json_encode(base64_encode($response));
// 			echo json_encode($response);
// 			echo base64_encode($response);
		}
	}

?>
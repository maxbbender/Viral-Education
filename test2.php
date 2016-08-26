<html>
<head>

</head>
<body>
	<div class="row">
	<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://stream.watsonplatform.net/text-to-speech/api/v1/synthesize?text=sobreponi%C3%A9ndose%20y%20dominando%20a%20los%20amables%20horrores%20y%20libertinas%20licencias%20que%20relata.%20Y%20para%20referir%20tanto%20suceso&voice=es-US_SofiaVoice",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic MDUxMGM2MWMtMGRjNS00NmZkLWJiNzYtZmU0ZWU0NjcyNzcyOm1zZkRXeFIwVUlGTw==",
    "cache-control: no-cache",
    "postman-token: f5664090-16ba-4f94-a531-76715c580691"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo '<embed src="data:audio/ogg;base64,'. base64_encode($response) .'" type="audio/ogg">';
}?>
</div>
</body>
</html>


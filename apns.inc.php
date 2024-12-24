<?php
require_once("config.inc.php");
require_once("vendor/MultibyteStringConverter.php");

function get_apns_key() {
	$apnsSecret = file_get_contents(__DIR__ . "/" . APNS_KEY_FILE);
	return openssl_pkey_get_private($apnsSecret);
}

function jwt_base64_encode($data) {
	$result = base64_encode($data);
	$result = strtr($result, "+/", "-_");
	return rtrim($result, "=");
}

function generate_apns_token() {
	$jsonHeader = array(
		"alg" => "ES256",
		"kid" => APNS_KEY_ID
	);
	$jsonPayload = array(
		"iss" => APNS_TEAM_ID,
		"iat" => time()
	);

	$apnsKey = get_apns_key();

	$jsonHeaderEncoded = jwt_base64_encode(json_encode($jsonHeader));
	$jsonPayloadEncoded = jwt_base64_encode(json_encode($jsonPayload));
	$tokenProtectedPart = $jsonHeaderEncoded . "." . $jsonPayloadEncoded;

	$converter = new MultibyteStringConverter();

	$jsonSignatureAsn1 = "";
	openssl_sign($tokenProtectedPart, $jsonSignatureAsn1, $apnsKey, OPENSSL_ALGO_SHA256);
	openssl_free_key($apnsKey);

	$jsonSignature = $converter->fromAsn1($jsonSignatureAsn1, 64);

	return
		$tokenProtectedPart . "." .
		jwt_base64_encode($jsonSignature);
}

function send_apns_push($environment, $payload, $apnsToken, $deviceToken, $bundleID) {
	$payloadJSON = json_encode($payload);

	$environmentURL = "https://api.sandbox.push.apple.com";
	if ($environment == 1) {
		$environmentURL = "https://api.push.apple.com";
	}

	$url = "$environmentURL/3/device/$deviceToken";
	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJSON);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Authorization: Bearer $apnsToken",
		"apns-topic: $bundleID"
	));
	$response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	return $httpcode == 200;
}
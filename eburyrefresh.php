<?php
// Define the URL
$url = "https://auth-sandbox.ebury.io/token";
$auth_client_id = 'gZV3CEc7I5sAvFLiMHoG6LSkVjKicCU0';
$auth_client_secret = 'oXsjlZoalqwqe4G9ekE0dF9xmo9htkWL';
$auth_url = 'https://auth-sandbox.ebury.io';
$code = 'd217c90304b94963b1bad5288ee6bd86';
$redirect_uri = 'http://localhost:5001/callback';

// Create the credentials string and encode it in Base64
$credentials = base64_encode($auth_client_id . ':' . $auth_client_secret);


// Define the POST data
$postData = [
    'grant_type' => 'refresh_token',
    'refresh_token' => 'VEyWa33y5tMrQRRtuyMpPIsZV3s6g1',
    'scope' => 'openid'
];

// Define the authorization credentials
$authorization =  'Basic ' . $credentials;

// Initialize a cURL session
$ch = curl_init();

// Set the URL to which you want to send the POST request
curl_setopt($ch, CURLOPT_URL, $url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, true);

// Set the POST fields
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

// Set the headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: ' . $authorization,
    'Content-Type: application/x-www-form-urlencoded'
));

// Return the response as a string instead of outputting it directly
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL session
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
} else {
    // Process the response
    echo 'Response:' . $response;
}

// Close the cURL session
curl_close($ch);

$res='{
    "access_token":"zr9csj9hP79oHKzaoDibOCNekIgf85",
    "expires_in":3600,
    "id_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL2VidXJ5LWFwaS1zYW5kYm94LWF1dGgtcHJveHkuZWJ1cnkuaW8iLCJzdWIiOiJERU1PQ09OOTc0IiwiYXVkIjoiZ1pWM0NFYzdJNXNBdkZMaU1Ib0c2TFNrVmpLaWNDVTAiLCJleHAiOiIxNzIxNzE1NTY4IiwiaWF0IjoiMTcyMTcxMTk2OCIsImNsaWVudHMiOlt7ImNsaWVudF9pZCI6IkVCUENMSTI4NTc0OSIsImNsaWVudF9uYW1lIjoiQ1QgQWZyaWNhIEJWIn1dfQ.WqjkNm66YjnSU6qoA6dgoPwXVKISbUhZR-F1WaGIs1o",
    "refresh_token":"MhyDC1sdrKEeTimRPyVueXaVtV21zC",
    "token_type":"Bearer"
    }';
?>

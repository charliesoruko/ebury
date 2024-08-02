<?php
// Define the variables
class EburyAuth {    
    public $auth_client_id;
    public $auth_client_secret;
    public $auth_url;
    public $server_url;
    public $test;
    public $useremail;
    public $password;
    public $client_id;
    public $contact_id;
    public $credentials;
    public $redirect_uri;
}

$CharliesEbury=new EburyAuth();
$CharliesEbury->auth_client_id = 'gZV3CEc7I5sAvFLiMHoG6LSkVjKicCU0';
$CharliesEbury->auth_client_secret = 'oXsjlZoalqwqe4G9ekE0dF9xmo9htkWL';
$CharliesEbury->test=1;
if($CharliesEbury->test){
    $CharliesEbury->auth_url = 'https://auth-sandbox.ebury.io';
    $CharliesEbury->server_url='https://sandbox.ebury.io';
}
$CharliesEbury->useremail='Api.user@ctafrica.com';
$CharliesEbury->password='123456';
$CharliesEbury->client_id='EBPCLI285749';
$CharliesEbury->contact_id='DEMOCON974';
$CharliesEbury->credentials = base64_encode($CharliesEbury->auth_client_id . ':' . $CharliesEbury->auth_client_secret);
$CharliesEbury->redirect_uri = 'http://localhost:5001/callback';


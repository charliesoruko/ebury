<?php
include "eburyapi.php";




$authtoken=get_auth_token();

if($authtoken['success']){

    $tokenjson=json_decode($authtoken['data']);
   // var_dump($tokenjson);
    $accesstoken=$tokenjson->access_token;
    $expires_in=$tokenjson->expires_in;
    $id_token=$tokenjson->id_token;
    $refresh_token=$tokenjson->refresh_token;
    echo "AccessToken : ".$accesstoken ."----<hr/>"; 

    get_transactions_table($accesstoken);

}else{
    echo $authtoken['message'];
}

?>

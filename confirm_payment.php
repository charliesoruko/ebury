<?php
include "eburyapi.php";
$massPaymentId=$_GET["massPaymentId"];
$accesstoken=$_GET["accesstoken"];
$mode=$_GET["mode"];
$currency=$_GET["currency"];




if($mode=='confirm'){
    echo "confirm";
$confirm=confirm_mass_payment($massPaymentId,$accesstoken);
renderTableFromObjectOrArray(json_decode($confirm));
//showdata($confirm);
}
if($mode=='errors'){
    echo "errors";
    $confirm=fetchbulkPaymentErrors($massPaymentId,$accesstoken,1,10,$currency);
    showdata($confirm);
}
if($mode=="payments"){
    echo "payments";
    $confirm=fetchbulkPaymentpayments($massPaymentId,$accesstoken); 
    showdata($confirm);
}
if($mode=="trades"){
    echo "trades";
    $confirm=fetchbulkPaymentpaymentstrade($massPaymentId,$accesstoken); 
    showdata($confirm);
 }
 if($mode=="account"){
    echo "account";
    
    $confirm=fetchbulkPaymentpaymentsaccount($massPaymentId,$accesstoken); 
    renderTableFromObjectOrArray(json_decode($confirm));
   // showdata($confirm);
 }
 if($mode="receipt"){
    echo "receipt";
    $r=gettradereceipt($accesstoken);
    echo $r;
 }
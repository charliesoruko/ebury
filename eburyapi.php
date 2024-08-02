<?php
include("eburyauth.php");

function login_for_auth_code(){
    global $CharliesEbury;
                    
                    // Define the URL
                    $url = $CharliesEbury->auth_url."/login";

                    // Define the POST data
                    $postData = [
                        'email' => $CharliesEbury->useremail,
                        'password' => $CharliesEbury->password,
                        'client_id' => $CharliesEbury->auth_client_id,
                        'state' => 'state'
                    ];

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
                        'Content-Type: application/x-www-form-urlencoded'
                    ));

                    // Return the response as a string instead of outputting it directly
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                    // Execute the cURL session
                    $response = curl_exec($ch);

                    // Check for errors
                    if (curl_errno($ch)) {
                        return [
                            'error' => true,
                            'message' => curl_error($ch)// Assuming $data contains the error message
                        ];
                    } else {
                        // Process the response
                        //echo $response;
                        return [
                            'success' => true,
                            'data' => $response // Assuming $data contains the data 
                        ];
                    }

                    // Close the cURL session
                    curl_close($ch);
    }

function extractCodeFromHtml($htmlContent) {
    // Define a regex pattern to match the code query parameter
    $pattern = '/code=([^&]*)/';
    
    // Perform a regular expression match
    if (preg_match($pattern, $htmlContent, $matches)) {
        // Return the extracted code
        return $matches[1];
    } else {
        // Return null if the code is not found
        return null;
    }
}
function get_auth_code(){
        $html=login_for_auth_code();
        if(isset($html["success"])){
            $code=extractCodeFromHtml($html["data"]);
            if($code!==null){
                //echo $code;
                return $code;
                
            }
        }else{  
            echo "Error : ".$html["message"];
            return '';
        }
}
function get_auth_token(){
    global $CharliesEbury;
    $authcode=get_auth_code();
    //echo $authcode;
          // Construct the full URL
            $url = $CharliesEbury->auth_url."/token";

            // Define the POST data
            $postData = [
                'grant_type' => 'authorization_code',
                'code' => $authcode,
                'redirect_uri' => $CharliesEbury->redirect_uri
            ];

            // Initialize a cURL session
            $ch = curl_init();

            // Set the URL to which you want to send the POST request
            curl_setopt($ch, CURLOPT_URL, $url);

            // Set cURL options
            curl_setopt($ch, CURLOPT_POST, true);

            // Set the POST fields with URL-encoded data
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

            // Set the headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Basic ' . $CharliesEbury->credentials,
                'Content-Type: application/x-www-form-urlencoded'
            ));

            // Return the response as a string instead of outputting it directly
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute the cURL session
            $response = curl_exec($ch);

            // Check for errors
            if (curl_errno($ch)) {
                return [
                    'error' => true,
                    'message' => curl_error($ch)// Assuming $data contains the error message
                ];
            } else {
               // echo "response". $response;
                return [
                    'success' => true,
                    'data' => $response // Assuming $data contains the data 
                ];
            }

            // Close the cURL session
            curl_close($ch);
}
function add_beneficiary($data,$accesstoken){
    global $CharliesEbury;

    /****
     * data template 
     * 
     * 
     * $beneficiary_data = [
            'name' => 'Kenya Test2', //REQUIRED
            'email_addresses' => [
                'oruko@charlies-travels.com','test@charlies-travels.com'
            ],
            'email_notification' => true, //REQUIRED // Use true for boolean
            "address_line_1" => "Address 123",
            "street_name" => "C. de Sevilla",
            "building_number" => "101",
            "floor" => "2nd",
            "apartment_office_number" => "201",
            "city" => "Madrid",  //REQUIRED
            "state_region" => "Central Spain",
            "post_code" => "1234",
            "country_code" => "ES",
            "account_number" => "",
            "bank_address_line_1" => "",
            "bank_country_code" => "ES",
            "bank_currency_code" => "EUR",
            "bank_identifier" => "",
            "bank_name" => "",
            "correspondent_account" => "",
            "correspondent_swift_code" => "",
            "iban" => "ES1120383471246776817626",
            "inn" => "",
            "kbk" => "",
            "kio" => "",
            "kpp" => "",
            "reason_for_trade" => "",
            "reference_information" => "Salary Payment",
            "russian_central_bank_account" => "",
            "swift_code" => "CAHMESMMXXX",
            "vo" => ""
        ];
    * 
    */

    // Construct the full URL
    $url = $CharliesEbury->server_url."/beneficiaries?client_id=".$CharliesEbury->client_id;

    // Define the payload data


    // Encode the payload data to JSON
    $jsonData = json_encode($data);

    // Initialize a cURL session
    $ch = curl_init();

    // Set the URL to which you want to send the POST request
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);

    // Set the JSON payload
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Set the headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer '. $accesstoken,
        'Content-Type: application/json'
    ));

    // Return the response as a string instead of outputting it directly
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        return [
            'error' => true,
            'message' => curl_error($ch)// Assuming $data contains the error message
        ];
    } else {
        return [
            'success' => true,
            'data' => $response // Assuming $data contains the data 
        ];
    }

    // Close the cURL session
    curl_close($ch);
}
function get_quote($quote_data,$quote_type,$accesstoken){
    
    //$quote_type="estimate";  // use estimate or quote

    //example quote_data 
    /*// Define the payload data
        $data = [
            'trade_type' => 'forward', // spot or forward
            'sell_currency' => 'KES', // Replace with actual sell currency
            'buy_currency' => 'ZAR', // Replace with actual buy currency
            'amount' => 1000, // Replace with actual amount (number)
            'operation' => 'sell', // use sell or buy
        // 'value_date' => date('Y-m-d') // uses current date but can replace with any other
        ];
        */
    
    

    global $CharliesEbury;

    // Construct the full URL
    $url = $CharliesEbury->server_url."/quotes?client_id={$CharliesEbury->client_id }&quote_type={$quote_type}";


    // Encode the payload data to JSON
    $jsonData = json_encode($quote_data);

    // Initialize a cURL session
    $ch = curl_init();

    // Set the URL to which you want to send the POST request
    curl_setopt($ch, CURLOPT_URL, $url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_POST, true);

    // Set the JSON payload
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Set the headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $accesstoken,
        'Content-Type: application/json'
    ));

    // Return the response as a string instead of outputting it directly
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        return [
            'error' => true,
            'message' => curl_error($ch)// Assuming $data contains the error message
        ];
    } else {
        // Process the response
        return [
            'success' => true,
            'data' => $response // Assuming $data contains the data 
        ];
    }

    // Close the cURL session
    curl_close($ch);

    /** Example RESPONSE estimate
     * 
     * {"estimated_rate":7.104069,
     * "estimated_rate_symbol":"ZARKES",
     * "fee_amount":0.0,
     * "fee_currency":"KES",
     * "inverse_rate":0.140764,
     * "inverse_rate_symbol":"KESZAR",
     * "value_date":"2024-07-31"}
     * 
     * example response Quote 
     * $response= {
            "book_trade": "/trades?client_id=EBPCLI285749&quote_id=f0b1d4ab47a3902e0fc251639ba41e0f",
            "buy_amount": 1.18,
            "buy_currency": "EUR",
            "inverse_rate": 1.182603,
            "inverse_rate_symbol": "GBPEUR",
            "quote_id": "f0b1d4ab47a3902e0fc251639ba41e0f",
            "quoted_rate": 0.845592,
            "quoted_rate_symbol": "EURGBP",
            "sell_amount": 1.0,
            "sell_currency": "GBP",
            "value_date": "2024-07-26"
            }
     * 
     * 
     */





}
function create_trade($quote_id,$tradedata,$accesstoken){

    /**
     * // Define the payload data
        $tradedata = [   
            'reason' => "Salary/Payroll", // Replace with actual reason
        ];
     * 
     */



            global $CharliesEbury;

        // Construct the full URL
        $url = $CharliesEbury->server_url."/trades?client_id={$CharliesEbury->client_id}&quote_id={$quote_id}";



        // Encode the payload data to JSON
        $jsonData = json_encode($tradedata);

        // Initialize a cURL session
        $ch = curl_init();

        // Set the URL to which you want to send the POST request
        curl_setopt($ch, CURLOPT_URL, $url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_POST, true);

        // Set the JSON payload
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        // Set the headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ));

        // Return the response as a string instead of outputting it directly
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL session
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return [
                'error' => true,
                'message' => curl_error($ch)// Assuming $data contains the error message
            ];
        } else {
            // Process the response
            return [
                'success' => true,
                'data' => $response // Assuming $data contains the data 
            ];
        }

        // Close the cURL session
        curl_close($ch);



}


function _mass_payment($massPaymentId,$accesstoken){
    global $CharliesEbury;


        // Define the variables with appropriate values


        // Prepare the URL
        $url = $CharliesEbury->server_url.'/mass-payments/' . $massPaymentId . '/confirm?client_id=' .$CharliesEbury->client_id;
        echo $url;
        // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ''); // Empty data since --data '' is specified

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);

        // Output the response
        echo $response;
}


function create_bulk_payment($accesstoken,$data){

    /**
     * Please note that if a trade ID is being passed in the request, the payments in a mass payment instruction must be of the same BUY currency.
     */



    global $CharliesEbury;
    $url = $CharliesEbury->server_url.'/mass-payments?client_id=' . $CharliesEbury->client_id;
    $headers = [
        'Authorization: Bearer ' . $accesstoken,
        'Content-Type: application/json'
    ];
        
            // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            
            return [
                'error' => true,
                'message' => curl_error($ch)// Assuming $data contains the error message
            ];
        } else {
            // Print the response
            return [
                'success' => true,
                'data' => $response // Assuming $data contains the data 
            ];
        }

        // Close cURL session
        curl_close($ch);


    
}


function get_all_bulk_payments($authtoken){
    global $CharliesEbury;
    // Define your variables
    $appUrl = $CharliesEbury->server_url.'/mass-payments';
    $clientId = $CharliesEbury->client_id;
   
    $page = 1;
    $pageSize = 10;
    $url = sprintf('%s?client_id=%s&page=%d&page_size=%d', $appUrl, $clientId, $page, $pageSize);

    // Prepare the request headers
    $headers = [
        'Authorization: Bearer ' . $authtoken,
        'Content-Type: application/json'
    ];

        // Initialize cURL session
        $ch = curl_init($url);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return [
                'error' => true,
                'message' => curl_error($ch)// Assuming $data contains the error message
            ];
        } else {
            // Print the response
            return [
                'success' => true,
                'data' => $response // Assuming $data contains the data 
            ];
        }

        // Close cURL session
        curl_close($ch);


}

function rendermasspaymentTableFromObject($object,$accesstoken) {
    
    if (!is_object($object)) {
        echo 'Invalid input, expected an object.';
        return;
    }
    

  //var_dump($object);
   echo "<tr>";
    foreach ($object as $label => $value) {

        if($label=="links"){
           // echo "<td>".json_encode($value)."</td>";
        }
        if($label=="error"){
            echo "<td style='font-size: 10px;'>";
            if ($value!=NULL) {
            echo "<strong>Code : </strong> ".$value->code." <br>";
            echo "<strong>Details : </strong> ".$value->details."<br>";
            echo "<strong>Mesage : </strong> ".$value->message;
            }
            echo "</td>";

        }
        if($label=="payments_summary"){
            echo "<td>";
            echo "<strong>Created : </strong> ".$value->payments_created." ";
            echo "<strong>Errored : </strong> ".$value->payments_errored." <br>";
            echo "<strong>Processed : </strong> ".$value->payments_processed." ";
            echo "<strong>Received : </strong> ".$value->payments_received." <br>";
            echo "</td>";
        }
        if($label=="external_reference_id" || $label=="mass_payment_status" || $label=="sell_currency" || $label== "trades_created"){
            echo "<td>".($value)."</td>"; 
        }
        
        if($label=="mass_payment_id"){
            
           
            echo "<td>";
            echo "<a href='confirm_payment.php?mode=confirm&currency=".$object->sell_currency."&massPaymentId=".$value."&accesstoken=".$accesstoken."' target='_blank' >Confirm payment</a>";
            echo " | <a href='confirm_payment.php?mode=payments&currency=".$object->sell_currency."&massPaymentId=".$value."&accesstoken=".$accesstoken."' target='_blank' >View Payments</a> <br>";
            echo " <a href='confirm_payment.php?mode=trades&currency=".$object->sell_currency."&massPaymentId=".$value."&accesstoken=".$accesstoken."' target='_blank' >View Trades</a>";
            echo " | <a href='confirm_payment.php?mode=errors&currency=".$object->sell_currency."&massPaymentId=".$value."&accesstoken=".$accesstoken."' target='_blank' >View Errors</a> ";
            echo " | <a href='confirm_payment.php?mode=account&currency=".$object->sell_currency."&massPaymentId=".$value."&accesstoken=".$accesstoken."' target='_blank' >Funding Account</a>";
            echo "</td>";
            echo "<td>".$value."</td>";
        }

       

        }
    
    //echo "<td>".fetchbulkPaymentErrors($object->mass_payment_id,$accesstoken,1,50,$object->sell_currency)."</td>";
    echo "</tr>";
   
}

function renderTableFromObjectOrArray($data) {
    if (!is_object($data) && !is_array($data)) {

          echo json_encode($data).' - Invalid input, expected an object or array.';
       
        return;
    }

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>Label</th><th>Value</th></tr>";

    foreach ($data as $label => $value) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($label) . "</td>";

        // Handle nested objects or arrays
        if (is_object($value) || is_array($value)) {
            echo "<td>";
            echo "<pre>" . htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT)) . "</pre>";
            echo "</td>";
        } else {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }

        echo "</tr>";
    }

    echo "</table>";
}

function fetchbulkPaymentpayments( $massPaymentId, $token) {
    global $CharliesEbury;
    
    
    
    // Construct the URL with query parameters
    $url = $CharliesEbury->server_url.'/payments?client_id='. $CharliesEbury->client_id.'&mass_payment_id='.$massPaymentId;
       
    // Prepare the request headers
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        return  curl_error($ch);
    } else {
        // Print the response
        return  $response;
    }

    // Close cURL session
    curl_close($ch);
}
function fetchbulkPaymentpaymentstrade( $massPaymentId, $token) {
    global $CharliesEbury;
    
    
    
    // Construct the URL with query parameters
    $url = $CharliesEbury->server_url.'/trades?client_id='. $CharliesEbury->client_id.'&mass_payment_id='.$massPaymentId;
       
    // Prepare the request headers
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        return  curl_error($ch);
    } else {
        // Print the response
        return  $response;
    }

    // Close cURL session
    curl_close($ch);
}
function fetchbulkPaymentpaymentsaccount($massPaymentId,$accesstoken){
    global $CharliesEbury;


        // Define the variables with appropriate values


        // Prepare the URL
        $url = $CharliesEbury->server_url.'/mass-payments/' . $massPaymentId . '/funding-account?client_id=' .$CharliesEbury->client_id;
        //echo $url;
        // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json',
        ]);
       // curl_setopt($ch, CURLOPT_POSTFIELDS, ''); // Empty data since --data '' is specified

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);

        // Output the response
        return $response;
}
function fetchbulkPaymentErrors( $massPaymentId, $token, $page = 1, $pageSize = 50,$paymentCurrency) {
    global $CharliesEbury;
    
    
    
    // Construct the URL with query parameters
    $url = sprintf(
        '%s/mass-payments/%s/errors?client_id=%s&page=%d&page_size=%d&payment_currency=%s',
        $CharliesEbury->server_url,
        $massPaymentId,
        $CharliesEbury->client_id,
        $page,
        $pageSize,
        $paymentCurrency
    );

    // Prepare the request headers
    $headers = [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ];

    // Initialize cURL session
    $ch = curl_init($url);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        return  curl_error($ch);
    } else {
        // Print the response
        return  $response;
    }

    // Close cURL session
    curl_close($ch);
}
function confirm_mass_payment($massPaymentId,$accesstoken){
    global $CharliesEbury;


        // Define the variables with appropriate values


        // Prepare the URL
        $url = $CharliesEbury->server_url.'/mass-payments/' . $massPaymentId . '/confirm?client_id=' .$CharliesEbury->client_id;
        //echo $url;
        // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ''); // Empty data since --data '' is specified

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        }

        // Close the cURL session
        curl_close($ch);

        // Output the response
        return $response;
}
function getclients($accesstoken){

    global $CharliesEbury;
            // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $CharliesEbury->server_url."/clients");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            // Process the response
            return $response;
        }

        // Close the cURL session
        curl_close($ch);
}
function gettradereceipt($accesstoken){

    global $CharliesEbury;
            // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $CharliesEbury->server_url."/documents?type=tr&id=EBPOTR038113&client_id=EBPCLI285749");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            // Process the response
            return $response;
        }

        // Close the cURL session
        curl_close($ch);
}
function getalltrades($accesstoken){

    global $CharliesEbury;
            // Initialize cURL session
        $ch = curl_init();

        /**
         * The trade_type such as SPOT/ Forward / window_forward / drawdown and ndf.
            The buy_currency if you are searching for specific currencies and
            The parent_trade_id, in the case of Drawdowns
         */

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $CharliesEbury->server_url."/trades?client_id=".$CharliesEbury->client_id."&page=1&page_size=50");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            // Process the response
            return $response;
        }

        // Close the cURL session
        curl_close($ch);
}

function get_all_payments($accesstoken){

    global $CharliesEbury;
            // Initialize cURL session
        $ch = curl_init();

       

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $CharliesEbury->server_url."/payments?client_id=".$CharliesEbury->client_id."&page=1&page_size=50");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            // Process the response
            return $response;
        }

        // Close the cURL session
        curl_close($ch);
}
function get_all_transactions($accesstoken){

    global $CharliesEbury;
            // Initialize cURL session
        $ch = curl_init();

        
        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $CharliesEbury->server_url."/transactions?client_id=".$CharliesEbury->client_id."&page=1&page_size=50");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            return curl_error($ch);
        } else {
            // Process the response
            return $response;
        }

        // Close the cURL session
        curl_close($ch);
}

function showdata($confirm){
    $data=json_decode($confirm);
    foreach($data as $x){
        renderTableFromObjectOrArray($x);
        echo "<hr>"; 
    }
}

function get_all_trades_table($accesstoken){
    $trades=getalltrades($accesstoken);
    $tradesjson=json_decode($trades);

    echo "<table  border='1' cellpadding='5' cellspacing='0' style='width: max-content;' >
    <thead>
        <tr>
            <th>buy_amount</th>
            <th>buy_currency</th>
            <th>convert</th>
            <th>fee_amount</th>
            <th>fee_currency</th>
            <th>maturity_date</th>
            <th>order_date</th>
            <th>parent_trade_id</th>
            <th>rate</th>
            <th>rate_symbol</th>
            <th>reference</th>
            <th>sell_amount</th>
            <th>sell_currency</th>
            <th>status</th>
            <th>synthetic</th>
            <th>trade_id</th>
            <th>trade_receipt</th>
            <th>trade_type</th>
        </tr>
    </thead>
    ";
    foreach($tradesjson as $x){
        echo "<tr>";
        foreach ($x as $label => $value) {
           echo "<td>" ;
           if($label=="trade_receipt"){
            echo "<a href='confirm_payment.php?mode=receipt&currency=&massPaymentId=&accesstoken=".$accesstoken."' target='_blank' >Receipt</a>";
           }else{
           echo htmlspecialchars($value);
           } 
            echo "</td>";        
        }
        echo "</tr>";
    }
    echo "</table>";
}

function get_all_payments_table($accesstoken){
    $payments=get_all_payments($accesstoken);  
    $paymentsjson=json_decode($payments);
    echo "<table  border='1' cellpadding='5' cellspacing='0' style='width: max-content;' >
    <thead>
         <tr>
             <th>account_number</th>
             <th>amount</th>
             <th>authorisation_workflow</th>
             <th>authorised_by</th>
             <th>authorised_date</th>
             <th>bank_identifier</th>
             <th>beneficiary_name</th>
             <th>cancelled_by</th>
             <th>cancelled_date</th>
             <th>contact_id</th>
             <th>created_date</th>
             <th>external_reference_id</th>
             <th>iban</th>
             <th>invoice_required</th>
             <th>mass_payment_id</th>
             <th>multipayment_id</th>
             <th>payment_currency</th>
             <th>payment_date</th>
             <th>payment_id</th>
             <th>payment_instruction</th>
             <th>payment_receipt</th>
             <th>reference</th>
             <th>rejected_by</th>
             <th>rejected_date</th>
             <th>status</th>
             <th>swift_code</th>
             <th>trade_id</th>
             <th>fee_amount</th>
             <th>fee_currency</th>
 
         </tr>
    </thead>
    ";
    foreach($paymentsjson as $x){
        echo "<tr>";
 
        foreach ($x as $label => $value) {
         if($label=="fee_amount" || $label=="fee_currency"){ 
         }else{
              echo "<td>";
             echo htmlspecialchars($value);
              echo "</td>";
      }       
        }
        echo "<td>".(isset($x->fee_amount)?$x->fee_amount:"")."</td>";
        echo "<td>".(isset($x->fee_currency)?$x->fee_currency:"")."</td>";
 
        echo "</tr>";
    }
    echo "</table>";
}

function get_all_transactions_table($accesstoken){
    $trades=getalltrades($accesstoken);
    $tradesjson=json_decode($trades);

    echo "<table  border='1' cellpadding='5' cellspacing='0' style='width: max-content;' >
    <thead>
        <tr>
            <th>buy_amount</th>
            <th>buy_currency</th>
            <th>convert</th>
            <th>fee_amount</th>
            <th>fee_currency</th>
            <th>maturity_date</th>
            <th>order_date</th>
            <th>parent_trade_id</th>
            <th>rate</th>
            <th>rate_symbol</th>
            <th>reference</th>
            <th>sell_amount</th>
            <th>sell_currency</th>
            <th>status</th>
            <th>synthetic</th>
            <th>trade_id</th>
            <th>trade_receipt</th>
            <th>trade_type</th>
        </tr>
    </thead>
    ";
    foreach($tradesjson as $x){
        echo "<tr>";
        foreach ($x as $label => $value) {
           echo "<td>" ;
           if($label=="trade_receipt"){
            echo "<a href='confirm_payment.php?mode=receipt&currency=&massPaymentId=&accesstoken=".$accesstoken."' target='_blank' >Receipt</a>";
           }else{
           echo htmlspecialchars($value);
           } 
            echo "</td>";        
        }
        echo "</tr>";
    }
    echo "</table>";
}
function get_transactions_table($accesstoken){
   
    $transactions=get_all_transactions($accesstoken);
    $tradesjson=json_decode($transactions);
    

    echo "<table  border='1' cellpadding='5' cellspacing='0' style='width: max-content;' >
    <thead>
        <tr>
            <th>account_id </th>
            <th>additional_transaction_information</th>
            <th>amount</th> 
           
            <th>balance</th>           
            <th>booking_datetime</th>
            <th>client_id</th>
            <th>credit_debit_indicator</th>
            <th>creditor_account</th>
            <th>creditor_name</th>
            <th>debtor_account</th>
            <th>debtor_name</th>
            <th>status</th>
            <th>transaction_id</th>
            <th>transaction_information</th>
            <th>transaction_reference</th>
            <th>transaction_type</th>
            <th>value_datetime</th>
        </tr>
    </thead>
    ";
    foreach($tradesjson as $x){
       // echo json_encode($x)."<br>";
        echo "<tr>";
        foreach ($x as $label => $value) {
           echo "<td>" ;
           /*if($label=="amount"){
                $am=($value);
            echo $am->amount."</td><td>". $am->currency;
              }
              */
              
            if(is_object($value)){
                echo ($label." : ");
               
                foreach ($value as $l => $v) {
                    loop_through_object($v);                   
                    }
                    
            }else{
                echo $value;

                if($label=="account_id"){
                echo " 
            <a href='eburystatement.php?account_id=".$value."&accesstoken=".$accesstoken."&filetype=pdf' target='_blank' > Pdf</a> |  
            <a href='eburystatement.php?account_id=".$value."&accesstoken=".$accesstoken."&filetype=csv' target='_blank' > Csv</a>";
                }
             } 
            echo "</td>";        
        }
        echo "</tr>";
    }
    echo "</table>";

}
function create_statement($accesstoken,$filetype,$account_id,$from,$to){

    global $CharliesEbury;
   
    $data = json_encode([
            "from_value_datetime" => $from,
             "to_value_datetime" => $to
            ]);

        // Initialize cURL session
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $CharliesEbury->server_url."/accounts/".$account_id."/statements/file?client_id=".$CharliesEbury->client_id."&format=".$filetype);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        
            'Authorization: Bearer ' . $accesstoken,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        } else {
            echo $response;
            // Process the response
            $res=json_decode($response);
            
               $ch2 = curl_init();
                 // Set the cURL options
                    curl_setopt($ch2, CURLOPT_URL, $CharliesEbury->server_url.$res->url."?client_id=".$CharliesEbury->client_id);
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                    
                        'Authorization: Bearer ' . $accesstoken,
                        'Content-Type: application/json'
                    ]);
                   
                    // Execute the request
                    $response2 = curl_exec($ch2);

                    echo $response2;
        }

        // Close the cURL session
        curl_close($ch);


}

/**TEST /DEMO FUNCTIONS */


function create_bulk_payment_test($accesstoken){

    global $CharliesEbury;
    $paymentinstructions=[
        [
            "account_number" => "",
            "bank_address" => "",
            "bank_code" => "",
            "bank_country" => "GR",
            "bank_name" => "",
            "beneficiary_address" => "",
            "street_name" => "",
            "building_number" => "",
            "floor" => "",
            "apartment_office_number" => "",
            "beneficiary_city" => "Atenas",
            "state_region" => "",
            "post_code" => "",
            "beneficiary_name" => "CharliesTest",
            "beneficiary_country" => "GR",
            "direction" => "buy",
            "iban" => "GR8501408310831002101013561",
            "payment_currency" => "EUR",
            "payment_amount" => 60,
            "purpose_of_payment" => "",
            "reason_for_trade" => "Salary/Payroll",
            "reference" => "TEST PAYMENT",
            "swift_code" => "CRBAGRAAXXX",
            "value_date" => date('Y-m-d'),//"2024-08-01",
            "trade_type" => "spot"
        ],
         [
            'account_number' => '',
            'bank_address' => '',
            'bank_code' => '',
            'bank_country' => 'ES',
            'bank_name' => '',
            'beneficiary_address' => '', //required
            'street_name' => '',
            'building_number' => '',
            'floor' => '',
            'apartment_office_number' => '',
            'beneficiary_city' => 'Madrid',
            'state_region' => '',
            'post_code' => '',
            'beneficiary_name' => 'Kenya Test2', //required
            'beneficiary_country' => 'ES', //required
            'direction' => 'buy', //required
            'iban' => 'ES1120383471246776817626',
            'payment_currency' => 'USD', //required
            'payment_amount' => 10, //required
            'purpose_of_payment' => '',
            'reason_for_trade' => 'Salary/Payroll', //required
            'reference' => 'TEST PAYMENT 2',
            'swift_code' => 'CAHMESMMXXX',
            'value_date' => date('Y-m-d'), //required
            'trade_type' => 'spot'
        ]

        
    ];
   
    $data = [
        'auto_commit' => 'false', //true creates trades/ false requires confirmation
        'sell_currency' => 'KES',
        'external_reference_id' => 'TEST Charlies - '. date('Ymd - H:i:s'),
        'payment_instructions' => $paymentinstructions
    ];


    $mybulkpayment=create_bulk_payment($accesstoken,$data);
    if(isset($mybulkpayment['success'])){
        echo $mybulkpayment['data'];
        echo "<hr>";
    

    }else{
        echo $mybulkpayment['message'];
    }




    
    $allpayments=get_all_bulk_payments($accesstoken);
    if(isset($allpayments['success'])){
        $masspayments=json_decode($allpayments['data']);
        echo "<table border='1' cellpadding='5' cellspacing='0'  style='width: max-content;'>";
        echo "<tr>";
        echo "<th >Error</th>";
        echo "<th>external_reference_id</th>";
        echo "<th style='width:300px'>links</th>";
        echo "<th>mass_payment_id</th>";
        echo "<th>mass_payment_status</th>";
        echo "<th>payments_summary</th>";
        echo "<th>sell_currency</th>";
        echo "<th>trades_created</th>";
        echo "</tr>";
        foreach($masspayments as $masspayment){
        
            rendermasspaymentTableFromObject($masspayment,$accesstoken,$CharliesEbury->server_url);
           // fetchbulkPaymentErrors($masspayment->mass_payment_id,$accesstoken,1,50,$masspayment->sell_currency);
           //echo "<hr>";
          }
    
    
        echo "</table>";
      
    }else{
        echo $allpayments["message"];
    }
    
}


function get_quote_test($accesstoken){
    $quotedata = [
        'trade_type' => 'spot', // spot or forward
        'sell_currency' => 'USD', // Replace with actual sell currency
        'buy_currency' => 'KES', // Replace with actual buy currency
        'amount' => 1000, // Replace with actual amount (number)
        'operation' => 'sell', // use sell or buy
        'value_date' => '2024-7-29'//date('Y-m-d') // uses current date but can replace with any other
    ];

    $quote_type="quote";  //estimate or quote
    $myquote=get_quote($quotedata,$quote_type,$accesstoken);
   


}
function create_trade_test($myquote,$accesstoken){
    $tradedata = [   
        'reason' => "Salary/Payroll", // Replace with actual reason
    ];
    if(isset($myquote['success'])){
        // echo "shccess";
             //echo $myquote['data'];
             $quotedata=json_decode($myquote['data']);
             $quote_id=$quotedata->quote_id;
             echo "QuoteId : ".$quote_id;
 
                 $trade=create_trade($quote_id,$tradedata,$accesstoken);
                 if(isset($trade['success'])){
                     echo "<br/>Trade info : ".$trade['data'];
 
                 }else{
                     echo $trade["message"];
                 }
    }else{
             echo $myquote['message'];
    }  

}
function getclients_test($accesstoken){
    $clients=getclients($accesstoken);
     showdata($clients);
}
function loop_through_object($obj){
    if(is_object($obj)){
    foreach ($obj as $label => $val) {
        echo $label." : " .$val." | ";
    }
    }else{
        echo $obj." ";
    }
}

?>

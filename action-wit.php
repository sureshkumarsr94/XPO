<?php
    // Under development
    require __DIR__ . '/twilio-php-master/Twilio/autoload.php';
    require('lib/base.php');

    use Twilio\Twiml;
	use Twilio\Rest\Client;

	$base_obj = new base();	

	$accountSid = "ACf285b6814e1d938781a82a7ab6d532a6";
    $authToken = "446947642cc9b018d1d827edff943630";
    $actionUrl = "http://ec2-18-221-58-226.us-east-2.compute.amazonaws.com/expo/action.php";
	$outgoingNumber = '+12564348855';	

    $client = new Client($accountSid, $authToken);

    $response = new Twiml;

	$Speech = strtolower($_POST["SpeechResult"]);
	$order_number = $_GET["order_number"];
	$phone_number = $_GET["phone_number"];
	

	$sql="select  DATE_FORMAT(schd_date, '%D %M %Y') as schd_date, Customer_Name from xpo_delivery_details where Order_No='".$order_number."'";

    $data = $base_obj->_Fetch_Data($sql);
	$schd_date = $data[0]['schd_date'];	
	$name = $data[0]['Customer_Name'];




    if($Speech!=""){
        
        $ch = curl_init();  
        $wit_url = 'https://api.wit.ai/message?access_token=TKRIUOZFOEWNPO23MBDAPLQ43Q5SOF&q='.$Speech;
    	curl_setopt($ch,CURLOPT_URL,"");
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    	curl_setopt($ch,CURLOPT_HEADER, false); 
    	curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);	
    
    	$output=curl_exec($ch);
    
    	curl_close($ch);
    	
       
       
       	$gather = $response->gather([
						'input' => 'speech',
						'timeout' => 3 ,
						'action' => $actionUrl.'?order_number='.$order_number.'&phone_number='.$phone_number]);
						
		$gather->say($output['entities']['value']);
    }else {
        
        $response->say("Something went wrong. We will reach you after sometime");
        
    }



	

	

	header('Content-Type: text/xml');

    echo $response

?>

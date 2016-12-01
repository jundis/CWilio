<?php

require_once("cwilio-config.php");
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>\n";

if(strlen($_REQUEST['Digits'])<5)
{
    if($_REQUEST['Digits']=='1')
    {
        echo "<Gather numDigits='6' timeout='15' action='cwilio-support.php' method='POST'>\n";
        echo "<Say>Please enter your 6-digit ticket number. If you do not know your case number, press the pound key to be routed to the helpdesk.</Say>\n";
        echo "</Gather>\n";
    }
    else
    {
        echo "<Dial>$helpdesk</Dial>\n";
    }
}
else
{
    $header = array("Authorization: Basic ". base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey));
    $url = $connectwise . "/v4_6_release/apis/3.0/service/tickets/" . $_REQUEST['Digits'] . "/scheduleentries";

    $ch = curl_init(); //Initiate a curl session

    //Create curl array to set the API url, headers, and necessary flags.
    $curlOpts = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts); //Set the curl array to $curlOpts

    $answerTData = curl_exec($ch); //Set $answerTData to the curl response to the API.
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);  //Get the header length of the curl response
    $curlBodyTData = substr($answerTData, $headerLen); //Remove header data from the curl string.

    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);

    $jsonDecode = json_decode($curlBodyTData); //Decode the JSON returned by the CW API.

    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("<Say>Connectwise ticket ".$_REQUEST['Digits']." was not found. You'll be forwarded to the help desk momentarily</Say>\n<Dial>$helpdesk</Dial>\n</Response>"); //Report that the ticket was not found.
        }
        if($jsonDecode->code == "Unauthorized") { //If error code is an authorization error
            die("<Say>401 Unauthorized, check API key to ensure it is valid.</Say>\n</Response>"); //Fail case.
        }
        else {
            die("<Say>Unknown Error Occurred, check API key and other API settings. Error: " . $jsonDecode->code . "</Say>\n</Response>"); //Fail case.
        }
    }
    if(array_key_exists("errors",$jsonDecode)) //If connectwise returned an error.
    {
        $errors = $jsonDecode->errors; //Make array easier to access.

        die("<Say>ConnectWise Error: " . $errors[0]->message . "</Say>\n</Response>"); //Return CW error
    }

    $schedentry = end($jsonDecode);

    $url = $schedentry->_info->schedule_href;

    $ch = curl_init(); //Initiate a curl session

    //Create curl array to set the API url, headers, and necessary flags.
    $curlOpts = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts); //Set the curl array to $curlOpts

    $answerTData = curl_exec($ch); //Set $answerTData to the curl response to the API.
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);  //Get the header length of the curl response
    $curlBodyTData = substr($answerTData, $headerLen); //Remove header data from the curl string.

    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);

    $jsonDecode = json_decode($curlBodyTData); //Decode the JSON returned by the CW API.

    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("<Say>Connectwise ticket ".$_REQUEST['Digits']." was not found. You'll be forwarded to the help desk momentarily</Say>\n<Dial>$helpdesk</Dial>\n</Response>"); //Report that the ticket was not found.
        }
        if($jsonDecode->code == "Unauthorized") { //If error code is an authorization error
            die("<Say>401 Unauthorized, check API key to ensure it is valid.</Say>\n</Response>"); //Fail case.
        }
        else {
            die("<Say>Unknown Error Occurred, check API key and other API settings. Error: " . $jsonDecode->code . "</Say>\n</Response>"); //Fail case.
        }
    }
    if(array_key_exists("errors",$jsonDecode)) //If connectwise returned an error.
    {
        $errors = $jsonDecode->errors; //Make array easier to access.

        die("<Say>ConnectWise Error: " . $errors[0]->message . "</Say>\n</Response>"); //Return CW error
    }

    $url = $jsonDecode->member->_info->member_href;

    $ch = curl_init(); //Initiate a curl session

    //Create curl array to set the API url, headers, and necessary flags.
    $curlOpts = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts); //Set the curl array to $curlOpts

    $answerTData = curl_exec($ch); //Set $answerTData to the curl response to the API.
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);  //Get the header length of the curl response
    $curlBodyTData = substr($answerTData, $headerLen); //Remove header data from the curl string.

    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);

    $jsonDecode = json_decode($curlBodyTData); //Decode the JSON returned by the CW API.

    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("<Say>Connectwise ticket ".$_REQUEST['Digits']." was not found. You'll be forwarded to the help desk momentarily</Say>\n<Dial>$helpdesk</Dial>\n</Response>"); //Report that the ticket was not found.
        }
        if($jsonDecode->code == "Unauthorized") { //If error code is an authorization error
            die("<Say>401 Unauthorized, check API key to ensure it is valid.</Say>\n</Response>"); //Fail case.
        }
        else {
            die("<Say>Unknown Error Occurred, check API key and other API settings. Error: " . $jsonDecode->code . "</Say>\n</Response>"); //Fail case.
        }
    }
    if(array_key_exists("errors",$jsonDecode)) //If connectwise returned an error.
    {
        $errors = $jsonDecode->errors; //Make array easier to access.

        die("<Say>ConnectWise Error: " . $errors[0]->message . "</Say>\n</Response>"); //Return CW error
    }

    $name = $jsonDecode->firstName . " " . $jsonDecode->lastName;
    $phone = $jsonDecode->officePhone;
    $phone = preg_replace('/\D+/', '', $phone);
    $phone = "+1" . $phone;
    echo "<Say>Now connecting you with " . $name . ", please hold.</Say>\n";
    echo "<Dial timeout='1'>$phone</Dial>\n";
    echo "<Gather numDigits='6' action='cwilio-vm.php?ticket=".$_REQUEST['Digits']."' method='POST'>\n";
    echo "<Say>$name is currently unavailable. Please press 1 to leave a message, or press 2 to speak with another technician.</Say>\n";
    echo "</Gather>\n";
}

echo "</Response>";
?>
<?php
/**
 * Created by PhpStorm.
 * User: jundis
 * Date: 12/1/2016
 * Time: 12:54 PM
 */

require_once("cwilio-config.php");

if(array_key_exists("RecordingSource",$_REQUEST))
{
    $ch = curl_init(); //Initiate a curl session

    $header = array("Authorization: Basic ". base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey), "Content-Type: application/json");
    $postfieldspre = array("internalAnalysisFlag" => "True", "text" => "A new voice mail has been received in regards to this ticket. You can listen to it below:\n". $_REQUEST["RecordingUrl"]); //Post ticket as API user
    $postfields = json_encode($postfieldspre); //Format the array as JSON

    //Same as previous curl array but includes required information for PATCH commands.
    $curlOpts = array(
        CURLOPT_URL => $connectwise . "/v4_6_release/apis/3.0/service/tickets/" . $_GET['ticket'] . "/notes",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts);

    $answerTCmd = curl_exec($ch);
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $curlBodyTCmd = substr($answerTCmd, $headerLen);
    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);
    if($curlBodyTCmd == "ok") //Slack catch
    {
        return null;
    }
    $jsonDecode = json_decode($curlBodyTCmd); //Decode the JSON returned by the CW API.

    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("Connectwise record was not found."); //Report that the ticket was not found.
        }
        else if($jsonDecode->code == "Unauthorized") { //If error code is an authorization error
            die("401 Unauthorized, check API key to ensure it is valid."); //Fail case.
        }
        else if($jsonDecode->code == NULL)
        {
            //do nothing.
        }
        else {
            die("Unknown Error Occurred, check API key and other API settings. Error: " . $jsonDecode->code); //Fail case.
        }
    }
    if(array_key_exists("errors",$jsonDecode)) //If connectwise returned an error.
    {
        $errors = $jsonDecode->errors; //Make array easier to access.

        die("ConnectWise Error: " . $errors[0]->message); //Return CW error
    }

    $ch = curl_init(); //Initiate a curl session

    $postfieldspre = array(array("op" => "replace", "path" => "/customerUpdatedFlag", "value" => true));
    $postfields = json_encode($postfieldspre); //Format the array as JSON

    //Same as previous curl array but includes required information for PATCH commands.
    $curlOpts = array(
        CURLOPT_URL => $connectwise . "/v4_6_release/apis/3.0/service/tickets/" . $_GET['ticket'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => "PATCH",
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts);

    $answerTCmd = curl_exec($ch);
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $curlBodyTCmd = substr($answerTCmd, $headerLen);
    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);
    if($curlBodyTCmd == "ok") //Slack catch
    {
        return null;
    }
    $jsonDecode = json_decode($curlBodyTCmd); //Decode the JSON returned by the CW API.

    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("Connectwise record was not found."); //Report that the ticket was not found.
        }
        else if($jsonDecode->code == "Unauthorized") { //If error code is an authorization error
            die("401 Unauthorized, check API key to ensure it is valid."); //Fail case.
        }
        else if($jsonDecode->code == NULL)
        {
            //do nothing.
        }
        else {
            die("Unknown Error Occurred, check API key and other API settings. Error: " . $jsonDecode->code); //Fail case.
        }
    }
    if(array_key_exists("errors",$jsonDecode)) //If connectwise returned an error.
    {
        $errors = $jsonDecode->errors; //Make array easier to access.

        die("ConnectWise Error: " . $errors[0]->message); //Return CW error
    }

    $ch = curl_init(); //Initiate a curl session

    $postfieldspre = array(array("op" => "replace", "path" => "/status/id", "value" => "121"));
    $postfields = json_encode($postfieldspre); //Format the array as JSON

    //Same as previous curl array but includes required information for PATCH commands.
    $curlOpts = array(
        CURLOPT_URL => $connectwise . "/v4_6_release/apis/3.0/service/tickets/" . $_GET['ticket'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => "PATCH",
        CURLOPT_POSTFIELDS => $postfields,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 1,
    );
    curl_setopt_array($ch, $curlOpts);

    $answerTCmd = curl_exec($ch);
    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $curlBodyTCmd = substr($answerTCmd, $headerLen);
    // If there was an error, show it
    if (curl_error($ch)) {
        die(curl_error($ch));
    }
    curl_close($ch);
    if($curlBodyTCmd == "ok") //Slack catch
    {
        return null;
    }
    $jsonDecode = json_decode($curlBodyTCmd); //Decode the JSON returned by the CW API.

    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("Connectwise record was not found."); //Report that the ticket was not found.
        }
        else if($jsonDecode->code == "Unauthorized") { //If error code is an authorization error
            die("401 Unauthorized, check API key to ensure it is valid."); //Fail case.
        }
        else if($jsonDecode->code == NULL)
        {
            //do nothing.
        }
        else {
            die("Unknown Error Occurred, check API key and other API settings. Error: " . $jsonDecode->code); //Fail case.
        }
    }
    if(array_key_exists("errors",$jsonDecode)) //If connectwise returned an error.
    {
        $errors = $jsonDecode->errors; //Make array easier to access.

        die("ConnectWise Error: " . $errors[0]->message); //Return CW error
    }

}
if($_REQUEST['Digits']=="hangup")
{
    die;
}
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>\n";

if($_REQUEST['Digits']=='1')
{
    //echo "<Say>Please leave a message after the beep and the assigned technician will reach out to you shortly.</Say>\n";
    echo "<Record recordingStatusCallback='cwilio-vm.php?ticket=" . $_GET["ticket"] . "' recordingStatusCallbackMethod='POST'/>";
}
else
{
    echo "<Dial>$helpdesk</Dial>\n";
}

echo "</Response>";
?>
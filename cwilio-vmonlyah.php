<?php

require_once("cwilio-config.php");

if(array_key_exists("recorded",$_REQUEST))
{
    $name = "vm" . date("mdY-gis") . ".wav";
    file_put_contents($name, fopen($_REQUEST['RecordingUrl'],"r"));

    $phonenumber = substr(preg_replace('/\D+/', '',$_REQUEST['From']), 1);

    $header = array("Authorization: Basic ". base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey));
    $url = $connectwise . "/v4_6_release/apis/3.0/company/contacts?childconditions=communicationItems/value%20like%20%22" . $phonenumber . "%22";

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
    $jsonDecode = $jsonDecode[0];
    if(array_key_exists("code",$jsonDecode)) { //Check if array contains error code
        if($jsonDecode->code == "NotFound") { //If error code is NotFound
            die("<Say>Connectwise ticket ".$_REQUEST['Digits']." was not found. You'll be forwarded to the help desk momentarily</Say>\n$dialhelpdesk</Response>"); //Report that the ticket was not found.
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

    $subject = 'After hours voice message from ' . $_REQUEST['From'];

    if ($jsonDecode != null) {
        $phonetype = null;

        foreach ($jsonDecode->communicationItems as $comitem) {
            if ($comitem->value == $phonenumber) {
                $phonetype = strtolower($comitem->type->name);

            }
        }

        if ($phonetype == null) {
            $body = "The voice mail is attached, and a transcribed text can be found below. This voice mail appears to be from " . $jsonDecode->firstName . " " . $jsonDecode->lastName . " of " . $jsonDecode->company->name . " \n\n" . $_REQUEST['TranscriptionText'];

        } else {
            $body = "The voice mail is attached, and a transcribed text can be found below. This voice mail appears to be from " . $jsonDecode->firstName . " " . $jsonDecode->lastName . " of " . $jsonDecode->company->name . " from their " . $phonetype . " phone \n\n" . $_REQUEST['TranscriptionText'];
        }

        $postarray = array(
            "summary" => $subject,
            "initialInternalAnalysis" => $body,
            "company" => array(
                "id" => $jsonDecode->company->id
            ),
            "board" => array(
                "id" => $boardID
            ),
            "contact" => array(
                "id" => $jsonDecode->id
            ));
    } else {
        $body = "The voice mail is attached, and a transcribed text can be found below.\r\n\r\n" . str_replace('<br />', ' ', $_REQUEST['TranscriptionText']);

        $postarray = array(
            "summary" => $subject,
            "initialInternalAnalysis" => $body,
            "board" => array(
                "id" => $boardID
            ),
        );
    }

    $header = array("Authorization: Basic " . base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey), "Content-Type: application/json");
    $ticketurl = $connectwise . "/v_4_6_release/apis/3.0/service/tickets";

    $ch = curl_init();

    $data = json_encode($postarray);

    $curlOpts = array(
        CURLOPT_URL => $ticketurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 1,
    );

    curl_setopt_array($ch, $curlOpts);

    $response = curl_exec($ch);

    $headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $response = substr($response, $headerLen);

    if (curl_error($ch)) {
        die(curl_error($ch));
    }

    curl_close($ch);

    $response = json_decode($response);

    if (array_key_exists("id", $response))
    {
        $ticketnumber = $response->id;
    }
    else
    {
        die("Failed to make ticket.");
    }

    $name = "vm" . date("mdY-gis") . ".wav";
    file_put_contents($name, fopen($_REQUEST['RecordingUrl'],"r"));
    $ch = curl_init(); //Initiate a curl session
    $audioheader = array("Authorization: Basic ". base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey), "Content-Type: multipart/form-data");
    $data = array("File" => '@' . $name, "recordType" => "ticket", "recordId" => $ticketnumber,"title"=>"Twilio voice mail on " . date("m-d-Y g:i:sa"));

    $curlOpts = array(
        CURLOPT_URL => $connectwise . "/v4_6_release/apis/3.0/system/documents",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $audioheader,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $data,
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

    unlink($name);
}
else
{
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
    echo "<Response>\n";
    echo "<Say>Please leave a message after the beep.</Say>\n";
    //echo '<Play>http://domain.com/cwilio/vmgreetingah.mp3</Play>'; //Uncomment this and comment above line to play a mp3 greeting instead.
    echo "<Record transcribe='true' transcribeCallback='cwilio-vmonlyah.php?recorded=true'/>\n";
    echo "</Response>";
}

?>
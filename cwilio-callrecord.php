<?php
/*
	CWilio
    Copyright (C) 2016  jundis

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once("cwilio-config.php");

if ($_REQUEST['ticket']=="no")
{
    if ($localstorage)
    {
        $name = "recordings/recording" . date("mdY-gis") . ".wav";
        file_put_contents($name, fopen($_REQUEST['RecordingUrl'],"r"));
    }
    die();
}

if(array_key_exists("RecordingSource",$_REQUEST))
{
    $ch = curl_init(); //Initiate a curl session

    //Create curl array to set the API url, headers, and necessary flags.
    $curlOpts = array(
        CURLOPT_URL => "https://$accountsid:$authtoken@api.twilio.com/2010-04-01/Accounts/" . $_REQUEST["AccountSid"] . "/Calls/" . $_REQUEST["CallSid"] . ".json",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(),
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

    $jsonDecode = json_decode($curlBodyTData); //Decode the JSON returned by the Twilio API.

    $ch = curl_init(); //Initiate a curl session
    $recordtime = gmdate("i:s", $_REQUEST['RecordingDuration']);
    $header = array("Authorization: Basic ". base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey), "Content-Type: application/json");
    $postfieldspre = array("internalAnalysisFlag" => "True", "text" => "A call recording has been received in regards to this ticket. It has been attached to the documents section of this ticket.\nDuration: " . $recordtime . "\nFrom: " . $jsonDecode->from_formatted); //Post ticket as API user
    $postfields = json_encode($postfieldspre); //Format the array as JSON

    //Same as previous curl array but includes required information for POST commands.
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

    $name = "recording" . date("mdY-gis") . ".wav";
    file_put_contents($name, fopen($_REQUEST['RecordingUrl'],"r"));
    $ch = curl_init(); //Initiate a curl session
    $audioheader = array("Authorization: Basic ". base64_encode(strtolower($companyname) . "+" . $apipublickey . ":" . $apiprivatekey), "Content-Type: multipart/form-data");
    $data = array("File" => '@' . $name, "recordType" => "ticket", "recordId" => $_REQUEST['ticket'],"title"=>"Call recording on " . date("m-d-Y g:i:sa"));

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


?>
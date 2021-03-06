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

//Connectwise Configuration
$connectwise = "https://cw.domain.com"; //Set your Connectwise URL
$companyname = "company"; //Set your company name from Connectwise. This is the company name field from login.
$apipublickey = "Connectwise Public Key"; //Public API key
$apiprivatekey = "Connectwise Private Key"; //Private API key
$ticketlength = "6"; //Expected ticket number length for CW tickets. This is required so the IVR kicks into gear after X numbers are entered. You can also specify that they need to press # instead after entering ticket.
$boardID = 7; //Used for vmonly/vmonlyah files to send to a specific CW board
$domain = "test.test.com"; //your domain

//Twilio Configuration
$accountsid = "SID here"; //Find this on https://www.twilio.com/user/account/
$authtoken = "Token here"; //Find this on https://www.twilio.com/user/account/

//IVR Configuration
$ivrname = "Company Inc";
$helpdesk = "+1PHONENUMBER"; //Helpdesk phone number
$sales = "+1PHONENUMBER"; //Sales phone number
$phoneprefix = "+1PHONENUMBER"; //Phone prefix for directory. If your direct dial numbers are 612-867-53XX, set this to +161286753.
$extensiondigits = "2"; //Number of digits for company phone numbers. Number of X's according to above info.
$recordcalls = false; //Set to true if you want all transferred calls to be recorded.
$localstorage = false; //Set to true if you want all non-ticket related recorded calls to be downloaded to the server CWilio is stored on. Requires a recordings subfolder where this script is stored.
$timeout = "15"; //Set to lower than your local voice mail timeout, otherwise Twilio will treat that as an answered call and the IVR voice mail will not work.
$nightmode = 0; //Set to 1 if you want to enable a night mode auto-attendant.
$afterhourswarning = "Please note that after hours calls are billed at a separate rate outside of the normal service contract."; //Warning for billable time on after hours call, leave blank for none.
$startday = "8:00AM"; //Start of your workday
$endday = "5:00PM"; //End of your workday
$timezone = "America/Chicago"; //Set your timezone here.

$directory = "For John Smith, enter 01. 
            For Jenny Doe, enter 09. 
            etc etc";

//---
//Do not edit below
//---

//Timezone Setting to be used for all files.
date_default_timezone_set($timezone);

//Set authentication for twilio
$twilauth = "Authentication: Basic " . base64_encode($accountsid . ":" . $authtoken);

//Record calls switch
if($recordcalls)
{
    $dialhelpdesk = "<Dial record='record-from-answer' recordingStatusCallback='cwilio-callrecord.php?ticket=no'>$helpdesk</Dial>\n";
}
else
{
    $dialhelpdesk = "<Dial>$helpdesk</Dial>\n";
}
?>
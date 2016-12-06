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
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>\n";

if(array_key_exists('Digits',$_REQUEST))
{
    if($_REQUEST['Digits']=='1')
    {
        echo "<Gather numDigits='1' action='cwilio-support.php' method='POST'>\n";
        echo "<Say>If you have an existing support case, please press 1. If this is a new case, please press 2.</Say>\n";
        echo "</Gather>\n";
    }
    else if($_REQUEST['Digits']=='2')
    {
        if($recordcalls)
        {
            echo "<Dial record='record-from-answer' recordingStatusCallback='cwilio-callrecord.php?ticket=no'>$sales</Dial>\n";
        }
        else
        {
            echo "<Dial>$sales</Dial>\n";
        }
    }
    else if($_REQUEST['Digits']=='3')
    {
        echo "<Gather numDigits='4' action='cwilio-directory.php' method='POST'>\n";
        echo "<Say>$directory</Say>\n";
        echo "</Gather>\n";
    }
    else if($_REQUEST['Digits']=='0')
    {
        echo $dialhelpdesk;
    }
    else
    {
        echo "<Gather numDigits='1' action='cwilio.php' method='POST'>\n";
        echo "<Say>Invalid entry, please select from the following options. Press 1 for Tecnical Support. Press 2 for Sales. Press 3 for the staff directory.</Say>\n";
        echo "</Gather>\n";
    }
}
else
{
    echo "<Gather numDigits='1' action='cwilio.php' method='POST'>\n";
    echo "<Say>Thank you for calling $ivrname, please select from the following options. Press 1 for Tecnical Support. Press 2 for Sales. Press 3 for the staff directory. Press 0 to be forwarded to the helpdesk.</Say>\n";
    echo "<Say>Thank you for calling $ivrname, please select from the following options. Press 1 for Tecnical Support. Press 2 for Sales. Press 3 for the staff directory. Press 0 to be forwarded to the helpdesk.</Say>\n";
    echo "</Gather>\n";
}

echo "</Response>";
?>
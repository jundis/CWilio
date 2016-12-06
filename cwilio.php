<?php

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
        echo "<Dial>$sales</Dial>\n";
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
    echo "</Gather>\n";
}

echo "</Response>";
?>
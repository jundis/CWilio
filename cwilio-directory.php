<?php

require_once("cwilio-config.php");
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>\n";

if($_REQUEST['Digits'] == "0" || $_REQUEST['Digits'] < $extensiondigits)
{
    echo $dialhelpdesk;
    echo "</Response>";
    die();
}

if($recordcalls)
{
    echo "<Dial record='record-from-answer' recordingStatusCallback='cwilio-callrecord.php?ticket=no'>" . $phoneprefix . $_REQUEST['digits'] . "</Dial>\n";
}
else
{
    echo "<Dial>" . $phoneprefix . $_REQUEST['digits'] . "</Dial>\n";
}
echo "<Gather numDigits='" . $extensiondigits . "' action='cwilio-directory.php' method='POST'>\n";
echo "<Say>Unable to call " . $_REQUEST['Digits'] . ". Please try again or press 0 then pound to be routed to the help desk.</Say>";
echo "<Pause length='2'/>";
echo "<Say>$directory</Say>\n";
echo "</Gather>\n";

echo "</Response>";
?>
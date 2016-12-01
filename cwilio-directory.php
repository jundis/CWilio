<?php
/**
 * Created by PhpStorm.
 * User: jundis
 * Date: 12/1/2016
 * Time: 11:31 AM
 */

require_once("cwilio-config.php");
header("content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<Response>\n";

echo "<Dial>" . $phoneprefix . $_REQUEST['digits'] . "</Dial>\n";
echo "<Gather numDigits='" . $extensiondigits . "' action='cwilio-directory.php' method='POST'>\n";
echo "<Say>Unable to call " . $_REQUEST['Digits'] . ". Please try again.</Say>";
echo "<Pause length='2'/>";
echo "<Say>$directory</Say>\n";
echo "</Gather>\n";

echo "</Response>";
?>
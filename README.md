# CWilio

## Installation Instructions

1. Download all files and place on a web server with PHP>=5.6 and cURL
2. Change all values in cwilio-config.php
3. Open your Twilio phone number console. https://www.twilio.com/console/phone-numbers/incoming
4. Select the phone number you want to use.
5. Set the "A call comes in" URL to https://domain.tld/cwilio.php
6. Call your number and test it out!

### VM-Only

You can use the vmonly file to just transcribe and create tickets for voice mails within ConnectWise.

Just configure the CW block of cwilio-config.php and place that and vmonly.php in a folder and point a twilio line to it.

Note that in order to use the robot text instead of greeting please see comment towards end of file.

This requires an mp3 called vm.mp3 to be in the same directory, assumed to be https://domain.tld/cwilio/vm.mp3

If you want to split off after hours VMs which will be tagged differently in ConnectWise, please use a second twilio line and set its call URL to cwilio-vmonly.php?ah=true instead of cwilio-vmonly.php

## API Key Setup

1. Login to ConnectWise
2. In the top right, click on your name
3. Go to "My Account"
4. Select the "API Keys" tab
5. Click the Plus icon to create a new key
6. Provide a description and click the Save icon.
7. Save this information, you cannot retrieve the private key ever again so if lost you will need to create new ones.
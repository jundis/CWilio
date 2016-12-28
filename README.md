# CWilio

## Installation Instructions

1. Download all files and place on a web server with PHP>=5.6 and cURL
2. Change all values in cwilio-config.php
3. Open your Twilio phone number console. https://www.twilio.com/console/phone-numbers/incoming
4. Select the phone number you want to use.
5. Set the "A call comes in" URL to https://domain.tld/cwilio.php
6. Call your number and test it out!

### VM-Only

You can use the vmonly and vmonlyah files to just transcribe and e-mail for Twilio voice mails. This is just a proof of concept at this point and will be expanded to actually make tickets later on.

## API Key Setup

1. Login to ConnectWise
2. In the top right, click on your name
3. Go to "My Account"
4. Select the "API Keys" tab
5. Click the Plus icon to create a new key
6. Provide a description and click the Save icon.
7. Save this information, you cannot retrieve the private key ever again so if lost you will need to create new ones.
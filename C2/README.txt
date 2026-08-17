C2 — API Request Logger
=======================

Start the server from this folder:

    php -S localhost:8080

Endpoint to call:

    POST http://localhost:8080/log.php
    Content-Type: application/json

Example:

    curl -i -X POST http://localhost:8080/log.php \
         -H "Content-Type: application/json" \
         -d "{\"name\":\"Ada\",\"score\":42}"

There is also a test page at http://localhost:8080/ (index.html) with a button that
sends the request from the browser.

Where the log files are stored
------------------------------

Every accepted request is written into the "log" folder next to log.php:

    C2/log/HH-MM-SS-request.txt

HH, MM and SS are the hour, minute and second of the request, for example
14-32-07-request.txt. The file contains the received JSON body exactly as it was
sent. The folder is created automatically on the first request.

Responses
---------

    201 Created                 the request was logged
    400 Bad Request             the body is not valid JSON
    405 Method Not Allowed      the request was not a POST
    415 Unsupported Media Type  Content-Type was not application/json

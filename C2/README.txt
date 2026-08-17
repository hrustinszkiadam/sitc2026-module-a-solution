C2 — API Request Logger
=======================

Start the server from this folder:

    php -S localhost:8080

Endpoint to call:

    POST http://localhost:8080/api/log
    Content-Type: application/json

Example:

    curl -i -X POST http://localhost:8080/api/log \
         -H "Content-Type: application/json" \
         -d "{\"name\":\"Ada\",\"score\":42}"

There is also a test page at http://localhost:8080/ with a button that sends such a
request from the browser.

Why the URL has no .php in it
-----------------------------

The whole mini test project is served by index.php. The PHP built-in web server
returns the closest index.php for every URL that does not exist as a real file, so
index.php receives the request for /api/log and handles it. No router script and no
rewrite rules are needed.

If the server is started in the repository root instead of this folder, the endpoint
is http://localhost:8080/C2/api/log — index.php recognises both addresses.

Where the log files are stored
------------------------------

Every accepted request is written into the "log" folder next to index.php:

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
    404 Not Found               any other address

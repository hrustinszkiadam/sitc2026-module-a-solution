C1 — Registration Form Validation
=================================

Start the server from this folder:

    php -S localhost:8080

URL to call:

    http://localhost:8080/index.php   (http://localhost:8080/ works as well)

The form posts back to index.php with the POST method. All three rules are checked
in PHP, so data posted directly to the endpoint (for example with curl or Postman)
is validated the same way:

    firstName   letters a-z and A-Z only
    lastName    letters a-z and A-Z only
    terms       must be present (the checkbox has to be checked)

Example of a direct request:

    curl -i -X POST http://localhost:8080/index.php \
         -d "firstName=Ada" -d "lastName=Lovelace" -d "terms=1"

The page answers with "Success" when every field is valid, otherwise the form is
shown again with the validation messages.

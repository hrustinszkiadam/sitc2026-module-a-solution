<?php
// C1 — Registration Form Validation. The form posts to this same file and every
// rule is checked on the server, so posting straight to the endpoint is validated too.

$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

$firstName = trim($_POST['firstName'] ?? '');
$lastName  = trim($_POST['lastName'] ?? '');
$terms     = isset($_POST['terms']);

$firstNameValid = preg_match('/^[a-zA-Z]+$/', $firstName) === 1;
$lastNameValid  = preg_match('/^[a-zA-Z]+$/', $lastName) === 1;

$success = $submitted && $firstNameValid && $lastNameValid && $terms;

/** Bootstrap state class for a field, but only once the form has been submitted. */
function state(bool $submitted, bool $valid): string
{
    if (!$submitted) {
        return '';
    }

    return $valid ? ' is-valid' : ' is-invalid';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>C1 — Registration</title>
  <link rel="stylesheet" href="bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <main class="registration">
<?php if ($success): ?>
    <p class="success">Success</p>
<?php else: ?>
    <h1 class="h4 mb-4">Create your account</h1>

    <form class="row g-3" method="post" action="index.php" novalidate>
      <div class="col-md-6">
        <label for="firstName" class="form-label">First name</label>
        <input type="text" class="form-control<?= state($submitted, $firstNameValid) ?>"
               id="firstName" name="firstName" value="<?= htmlspecialchars($firstName) ?>">
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please provide a valid name.</div>
      </div>

      <div class="col-md-6">
        <label for="lastName" class="form-label">Last name</label>
        <input type="text" class="form-control<?= state($submitted, $lastNameValid) ?>"
               id="lastName" name="lastName" value="<?= htmlspecialchars($lastName) ?>">
        <div class="valid-feedback">Looks good!</div>
        <div class="invalid-feedback">Please provide a valid name.</div>
      </div>

      <div class="col-12">
        <div class="form-check">
          <input class="form-check-input<?= state($submitted, $terms) ?>" type="checkbox"
                 id="terms" name="terms" value="1" <?= $terms ? 'checked' : '' ?>>
          <label class="form-check-label" for="terms">
            Agree to terms and conditions
          </label>
          <div class="invalid-feedback">You must agree before submitting.</div>
        </div>
      </div>

      <div class="col-12">
        <button class="btn btn-primary" type="submit">Submit form</button>
      </div>
    </form>
<?php endif; ?>
  </main>

</body>
</html>

<?php

// phpcs:disable
session_start();

if (empty($_POST['code'])) {
    // If you do not enter a verification code
    $class = 'warning';
    $message = 'ERROR: You have not entered a verification code';
} else {
    if (strtolower($_POST['code']) === strtolower($_SESSION['code'])) {
        // If your code passes validation
        $class = 'success';
        $message = 'SUCCESS: the verification code has been entered correctly';
    } else {
        // If the code verification fails
        $class = 'error';
        $message = 'ERROR: the verification code was entered incorrectly';
    }
}

/////////////////////////////////////////////////////////
// THIS IS IMPORTANT!                                  //
// Regardless of the result of the check, in any case, //
// the session variable must be deleted!!!             //
/////////////////////////////////////////////////////////
unset($_SESSION['code']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CAPTCHA demo</title>
    <style>
        .success {
            color: green
        }

        .warning {
            color: goldenrod
        }

        .error {
            color: red
        }
    </style>
</head>
<body>
<h1>CAPTCHA Demo</h1>
<hr>
<h2><a href="../index.html">Home</a> | <a href="form.php">Form</a> | Validate</h2>
<hr>
<h2 class="<?= $class ?>"><?= $message ?></h2>
</body>
</html>

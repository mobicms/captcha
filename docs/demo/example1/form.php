<?php

declare(strict_types=1);

// phpcs:disable
require_once '../../../vendor/autoload.php';

session_start();

$captcha = new Mobicms\Captcha\Image();
$_SESSION['code'] = $captcha->getCode();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CAPTCHA demo</title>
</head>
<body>
<h1>CAPTCHA Demo</h1>
<hr>
<h2><a href="../index.html">Home</a> | Form</h2>
<hr>
<form method="post" action="validate.php">
    Verification code<br>
    <img alt="Verification code" src="<?= $captcha->getImage() ?>" style="border: darkgray 1px solid">
    <br><br>
    <label>Please enter verification code <small>(case-insensitive)</small><br>
        <input type="text" size="6" name="code">
    </label>
    <input type="submit" name="submit" value="Submit">
</form>
</body>
</html>

<?php

// phpcs:disable
require_once 'lib/vendor/autoload.php';

session_start();

if (isset($_POST['submit'])) {
    ////////////////////////////////////////////////////////////
    // Code verification result                               //
    ////////////////////////////////////////////////////////////
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

    // THIS IS IMPORTANT!
    // In any case, after checking the session variable needs to be deleted.
    unset($_SESSION['code']);

    include 'page_validate.phtml';
} else {
    ////////////////////////////////////////////////////////////
    // Form with a verification code                          //
    ////////////////////////////////////////////////////////////
    $captcha = new Mobicms\Captcha\Image();
    $_SESSION['code'] = $captcha->getCode();

    include __DIR__ . '/page_form.phtml';
}

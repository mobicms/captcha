<?php

// phpcs:disable
require_once '../../vendor/autoload.php';

session_start();

if (isset($_POST['submit'])) {
    ////////////////////////////////////////////////////////////
    // Code verification result                               //
    ////////////////////////////////////////////////////////////
    $class = 'error';
    if (empty($_POST['code'])) {
        // If you do not enter a verification code
        $message = 'ERROR: You have not entered a verification code';
    } else {
        if (strtolower($_POST['code']) === strtolower($_SESSION['code'])) {
            // If your code passes validation
            $class = 'success';
            $message = 'SUCCESS: the verification code has been entered correctly';
        } else {
            // If the code verification fails
            $message = 'ERROR: the verification code was entered incorrectly';
        }
    }

    include 'page_validate.phtml';
} else {
    ////////////////////////////////////////////////////////////
    // Form with a verification code                          //
    ////////////////////////////////////////////////////////////
    $code = (string) new Mobicms\Captcha\Code();
    $image = new Mobicms\Captcha\Image($code);
    $_SESSION['code'] = $code;

    include 'page_form.phtml';
}

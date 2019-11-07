# A simple PHP CAPTCHA library

[![License](https://poser.pugx.org/batumibiz/captcha/license?format=flat-square)](https://packagist.org/packages/batumibiz/captcha)
[![Total Downloads](https://poser.pugx.org/batumibiz/captcha/downloads?format=flat-square)](https://packagist.org/packages/batumibiz/captcha)
[![Latest Stable Version](https://poser.pugx.org/batumibiz/captcha/v/stable?format=flat-square)](https://packagist.org/packages/batumibiz/captcha)

[![Build Status](https://scrutinizer-ci.com/g/batumibiz/captcha/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/batumibiz/captcha/build-status/develop)
[![Code Coverage](https://scrutinizer-ci.com/g/batumibiz/captcha/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/batumibiz/captcha/?branch=develop)
[![StyleCI](https://github.styleci.io/repos/102107214/shield?branch=develop)](https://github.styleci.io/repos/102107214)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/batumibiz/captcha/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/batumibiz/captcha/?branch=develop)

Prevent form spam by generating random Captcha images.

![Captchas examples](resources/example/captcha_example.png)

## Install via Composer

`composer require batumibiz/captcha`

## Usage

1. Display in form:

    ```html+php
    <?php
    $code = (string) new Batumibiz\Captcha\Code;
    $_SESSION['code'] = $code;
    ?>

    <form method="post">
    <!-- ... -->
    <img alt="Verification code" src="<?= new Batumibiz\Captcha\Image($code) ?>">
    <input type="text" size="5" name="code">
    <!-- ... -->
    </form>
	```

2. Check whether the entered code is correct:

    ```php
    $result = filter_input(INPUT_POST, 'code');
    $session = filter_input(INPUT_SESSION, 'code');
    
    if ($result !== null && $session !== null) {
        if (strtolower($result) == strtolower($session)) {
            // CAPTCHA code is correct
        } else {
            // CAPTCHA code is incorrect, show an error to the user
        }
    }
    ```

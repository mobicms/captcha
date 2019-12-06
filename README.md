# A simple PHP CAPTCHA library

[![Build Status](https://travis-ci.org/mobicms/captcha.svg?branch=develop)](https://travis-ci.org/mobicms/captcha)
[![Code Coverage](https://scrutinizer-ci.com/g/mobicms/captcha/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/mobicms/captcha/?branch=develop)
[![StyleCI](https://github.styleci.io/repos/226185078/shield?branch=develop)](https://github.styleci.io/repos/226185078)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mobicms/captcha/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/mobicms/captcha/?branch=develop)

[![Packagist](https://img.shields.io/packagist/l/mobicms/captcha)](https://packagist.org/packages/mobicms/captcha)
[![Packagist](https://img.shields.io/packagist/dt/mobicms/captcha)](https://packagist.org/packages/mobicms/captcha)
[![GitHub tag (latest SemVer)](https://img.shields.io/github/tag/mobicms/captcha.svg?label=stable)](https://github.com/mobicms/captcha/releases)

Prevent form spam by generating random Captcha images.

![Captchas examples](resources/example/captcha_example.png)

## Install via Composer

`composer require mobicms/captcha`

## Usage

1. Display in form:

    ```html+php
    <?php
    $code = (string) new Mobicms\Captcha\Code;
    $_SESSION['code'] = $code;
    ?>

    <form method="post">
    <!-- ... -->
    <img alt="Verification code" src="<?= new Mobicms\Captcha\Image($code) ?>">
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

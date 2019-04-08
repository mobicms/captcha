# A simple PHP CAPTCHA library

[![License](https://poser.pugx.org/batumibiz/captcha/license?format=flat-square)](https://packagist.org/packages/batumibiz/captcha)
[![Total Downloads](https://poser.pugx.org/batumibiz/captcha/downloads?format=flat-square)](https://packagist.org/packages/batumibiz/captcha)
[![SemVer](http://img.shields.io/:semver-2.0.0-informational.svg?style=flat-square)](http://semver.org)
[![Latest Stable Version](https://poser.pugx.org/batumibiz/captcha/v/stable?format=flat-square)](https://packagist.org/packages/batumibiz/captcha)

[![Build Status](https://scrutinizer-ci.com/g/batumibiz/captcha/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/batumibiz/captcha/build-status/develop)
[![Code Coverage](https://scrutinizer-ci.com/g/batumibiz/captcha/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/batumibiz/captcha/?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/batumibiz/captcha/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/batumibiz/captcha/?branch=develop)
[![Maintainability](https://api.codeclimate.com/v1/badges/b586e5f367cfd10f495d/maintainability)](https://codeclimate.com/github/batumibiz/captcha/maintainability)

Prevent form spam by generating random Captcha images.

![Captchas examples](example/captcha_example.png)

## Install via Composer

`composer require mobicms/captcha`

## Usage

1. Generating code:

    ```php
    $captcha = new Mobicms\Captcha\Captcha;
    $code = $captcha->generateCode();
    $_SESSION['code'] = $code;
    ```

2. Display in form:

    ```html+php
    <form method="post">
    <!-- ... -->
    <img alt="Verification code"
        width="<?= $captcha->width ?>"
        height="<?= $captcha->height ?>"
        src="<?= $captcha->generateImage($code) ?>"
    >
    <input type="text" size="5" name="code">
    <!-- ... -->
    </form>
	```

3. Check whether the entered code is correct:

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

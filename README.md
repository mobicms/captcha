# A simple PHP CAPTCHA library

[![License](https://poser.pugx.org/mobicms/captcha/license)](https://packagist.org/packages/mobicms/captcha)
[![Latest Stable Version](https://poser.pugx.org/mobicms/captcha/v/stable)](https://packagist.org/packages/mobicms/captcha)
[![Latest Unstable Version](https://poser.pugx.org/mobicms/captcha/v/unstable)](https://packagist.org/packages/mobicms/captcha)
[![Total Downloads](https://poser.pugx.org/mobicms/captcha/downloads)](https://packagist.org/packages/mobicms/captcha)

[![Build Status](https://scrutinizer-ci.com/g/mobicms/captcha/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/mobicms/captcha/build-status/develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mobicms/captcha/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/mobicms/captcha/?branch=develop)
[![Code Climate](https://codeclimate.com/github/mobicms/captcha/badges/gpa.svg)](https://codeclimate.com/github/mobicms/captcha)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e05df855-bdd8-4567-9666-7f5c2d97c80c/mini.png)](https://insight.sensiolabs.com/projects/e05df855-bdd8-4567-9666-7f5c2d97c80c)

Prevent form spam by generating random Captcha images.

![Captchas examples](http://mobicms.org/demo/captcha_example.png)

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

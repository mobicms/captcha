# A simple PHP CAPTCHA library

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
# `mobicms/captcha`

[![GitHub](https://img.shields.io/github/license/mobicms/captcha?color=green)](https://github.com/mobicms/captcha/blob/main/LICENSE)
[![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/mobicms/captcha)](https://github.com/mobicms/captcha/releases)
[![Packagist](https://img.shields.io/packagist/dt/mobicms/captcha)](https://packagist.org/packages/mobicms/captcha)

[![CI-Analysis](https://github.com/mobicms/captcha/workflows/Analysis/badge.svg)](https://github.com/mobicms/captcha/actions?query=workflow%3AAnalysis)
[![CI-Tests](https://github.com/mobicms/captcha/workflows/Tests/badge.svg)](https://github.com/mobicms/captcha/actions?query=workflow%3ATests)
[![Sonar Coverage](https://img.shields.io/sonar/coverage/mobicms_captcha?server=https%3A%2F%2Fsonarcloud.io)](https://sonarcloud.io/code?id=mobicms_captcha)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=mobicms_captcha&metric=alert_status)](https://sonarcloud.io/summary/overall?id=mobicms_captcha)

This library is a simple PHP CAPTCHA. Prevent form spam by generating random Captcha images.

Major features:
- lightweight and fast
- not create any temporary files
- there are many settings that allow you to change the look of the picture
- you can use your own font sets

Example:

![Captchas examples](resources/example/captcha_example.png)

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org). Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require mobicms/captcha
```

## Usage

- Display in form:

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

- Check whether the entered code is correct:

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


## Contributing

Contributions are welcome! Please read [CONTRIBUTING](https://github.com/mobicms/captcha/blob/develop/.github/CONTRIBUTING.md) for details.  

This project adheres to a [Contributor Code of Conduct](https://github.com/mobicms/captcha/blob/develop/.github/CODE_OF_CONDUCT.md).
By participating in this project and its community, you are expected to uphold this code.

[![YAGNI](https://img.shields.io/badge/principle-YAGNI-blueviolet.svg)](https://en.wikipedia.org/wiki/YAGNI)
[![KISS](https://img.shields.io/badge/principle-KISS-blueviolet.svg)](https://en.wikipedia.org/wiki/KISS_principle)

In our development, we follow the principles of YAGNI and KISS.  
The source code should not have extra unnecessary functionality and should be as simple and efficient as possible.

## License

The mobicms/captcha library is licensed for use under the MIT License (MIT).  
Please see [LICENSE](https://github.com/mobicms/captcha/blob/master/LICENSE) for more information.


## Our links
- [**mobiCMS Project**](https://mobicms.org) website and support forum
- [**Facebook**](https://www.facebook.com/mobicms)
- [**Twitter**](https://twitter.com/mobicms)

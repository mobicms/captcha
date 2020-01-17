# `mobicms/captcha`

This package is part of [mobiCMS](https://github.com/mobicms/mobicms) and [JohnCMS](https://github.com/simba77/johncms),
but can be used freely in any other projects.

[![Packagist](https://img.shields.io/packagist/l/mobicms/captcha)](https://packagist.org/packages/mobicms/captcha)
[![Source Code](http://img.shields.io/badge/source-mobicms/captcha-blue.svg)](https://github.com/mobicms/captcha)
[![GitHub tag (latest SemVer)](https://img.shields.io/github/tag/mobicms/captcha.svg?label=stable)](https://github.com/mobicms/captcha/releases)
[![Packagist](https://img.shields.io/packagist/dt/mobicms/captcha)](https://packagist.org/packages/mobicms/captcha)

[![GitHub Workflow Status](https://github.com/mobicms/captcha/workflows/CI/badge.svg)](https://github.com/mobicms/captcha/actions)
[![Code Coverage](https://scrutinizer-ci.com/g/mobicms/captcha/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/mobicms/captcha/?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mobicms/captcha/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/mobicms/captcha/?branch=develop)

This library is a simple PHP CAPTCHA.  
Prevent form spam by generating random Captcha images.

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


## License

The mobicms/captcha library is licensed for use under the MIT License (MIT).  
Please see [LICENSE](https://github.com/mobicms/captcha/blob/master/LICENSE) for more information.


## Our links
- [**mobiCMS Project**](https://mobicms.org) website and support forum
- [**Facebook**](https://www.facebook.com/mobicms)
- [**Twitter**](https://twitter.com/mobicms)

# `mobicms/captcha`

[![GitHub](https://img.shields.io/github/license/mobicms/captcha?color=green)](https://github.com/mobicms/captcha/blob/main/LICENSE)
[![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/mobicms/captcha)](https://github.com/mobicms/captcha/releases)
[![Packagist](https://img.shields.io/packagist/dt/mobicms/captcha)](https://packagist.org/packages/mobicms/captcha)

[![CI-Analysis](https://github.com/mobicms/captcha/workflows/analysis/badge.svg)](https://github.com/mobicms/captcha/actions?query=workflow%3AAnalysis)
[![CI-Tests](https://github.com/mobicms/captcha/workflows/tests/badge.svg)](https://github.com/mobicms/captcha/actions?query=workflow%3ATests)
[![Sonar Coverage](https://img.shields.io/sonar/coverage/mobicms_captcha?server=https%3A%2F%2Fsonarcloud.io)](https://sonarcloud.io/code?id=mobicms_captcha)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=mobicms_captcha&metric=alert_status)](https://sonarcloud.io/summary/overall?id=mobicms_captcha)

This library is a simple PHP CAPTCHA. Prevent form spam by generating random Captcha images.

Major features:
- lightweight and fast
- not create any temporary files
- there are many settings that allow you to change the look of the picture
- you can use your own font sets

Example:

![Captchas examples](docs/images/captcha_example.png)

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org). Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require mobicms/captcha
```

## Simply usage

- Display in form:

```php
<?php
$captcha = new Mobicms\Captcha\Image();
$_SESSION['code'] = $captcha->getCode();
?>

<form method="post">
<!-- ... -->
<img alt="Verification code" src="<?= $captcha->getImage() ?>">
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


## Customization
### Use your own verification code
The Image class already has the ability to generate validation code,
which will be sufficient in most use cases.
However, if necessary, you can generate the validation code yourself
and then pass it to the constructor when the Image class is initialized:
```php
$code = 'FooBar';
$captcha = new Mobicms\Captcha\Image($code);
```

### Resizing the image
Keep in mind that the width of the image will affect the density of the text.  
If the characters are very creeping on top of each other and become illegible,
then increase the width of the image, reduce the length of the verification code, or the font size. 
```php
$captcha = new Mobicms\Captcha\Image($code);

// Set the image width (default: 190)
$captcha->imageWidth = 250;
// Set the image height (default: 90)
$captcha->imageHeight = 100;
```

### Default font size

### Fonts mixer

### Fonts folders

### Adjust font
Some fonts may have a size that looks too small or large compared to others.
In this case, you need to specify an adjustment relative to the default size.
Also, if necessary, you can force the specified font to use only uppercase or lowercase characters.

Adjustment parameters are passed to the `$fontsTune` class property as an array.  
Keep in mind that the class already has some adjustments, so if you use fonts from this package,
then combine your array of adjustments with an array of `$fontsTune` properties. 
```php
$captcha = new Mobicms\Captcha\Image($code);

$adjust = [
    // Specify the name of the font file
    'myfont1.ttf' => [
        // Specify the relative font size.
        // It will be summarized with the default size specified in the $defaultFontSize property
        // In this case, the font will be used: 30+16=46
        'size' => 16,
        // Forcing the use of only lowercase characters of the specified font
        'case' => \Mobicms\Captcha\Image::FONT_CASE_LOWER,
    ],

    'myfont2.ttf' => [
        // Forcing the use of only uppercase characters of the specified font
        'case' => \Mobicms\Captcha\Image::FONT_CASE_UPPER,
    ],

    'myfont3.ttf' => [
        // Font size will be decreased by 8
        'size' => -8,
    ],

    'myfont4.ttf' => [
        // Font size will be increased by 4
        'size' => 4,
    ],
];

$captcha->fontsTune = array_merge($captcha->fontsTune, $adjust);
```


## Contributing
Contributions are welcome! Please read [Contributing][contributing] for details.

[![YAGNI](https://img.shields.io/badge/principle-YAGNI-blueviolet.svg)][yagni]
[![KISS](https://img.shields.io/badge/principle-KISS-blueviolet.svg)][kiss]

In our development, we follow the principles of [YAGNI][yagni] and [KISS][kiss].
The source code should not have extra unnecessary functionality and should be as simple and efficient as possible.

## License

This package is licensed for use under the MIT License (MIT).  
Please see [LICENSE][license] for more information.


## Our links
- [**mobiCMS Project**][website] website and support forum
- [**GitHub**](https://github.com/mobicms) mobiCMS project repositories
- [**Twitter**](https://twitter.com/mobicms)

[website]: https://mobicms.org
[yagni]: https://en.wikipedia.org/wiki/YAGNI
[kiss]: https://en.wikipedia.org/wiki/KISS_principle
[contributing]: https://github.com/mobicms/captcha/blob/main/.github/CONTRIBUTING.md
[license]: https://github.com/mobicms/captcha/blob/main/LICENSE

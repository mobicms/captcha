# Changelog
This project follows [semantic versioning](https://semver.org/).  
All notable changes to this project will be documented in this file.  
Detailed change can see in the [repository log](https://github.com/mobicms/captcha/commits/).

## 2.0.1 - 2019-12-08

#### Changed
- Small internal improvements


## 2.0.0 - 2019-12-06

#### Added
- Added few fonts
- Ability to use custom fonts
- Ability to set various image options through class `Options`
- Separate classes to generate CAPTCHA code and image
- Ability to shuffle used fonts in the image
- Added test case

#### Changed
- The minimum required PHP version is 7.2
- Changed package directory structure
- Refactoring

#### Removed
- The problematic font `granps.ttf` has been removed
- Mobicms\Captcha\Captcha::class


## 1.0.0 - 2017-09-01
Initial release

# Changelog
 
All notable changes to this project will be documented in this file.  
The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).  
Detailed changes can see in the [repository log].

## [Unreleased]

#### Added
- Nothing

#### Changed
- Bumped minimum PHP version to 8.0
- Various source code improvements

#### Deprecated
- Nothing

#### Removed
- Nothing

#### Fixed
- Nothing

#### Security
- Nothing


## [3.1.0] - 2021-07-21

#### Changed
- Added PHP 8.x support
- Various source code improvements


## [3.0.0] - 2020-09-08

#### Added
- New class for adjustment of a configuration options
  
#### Changed
- Bumped minimum PHP version to 7.4
- Code generator: added parameter check
- Various improvements


## [2.0.1] - 2019-12-08

#### Changed
- Small internal improvements


## [2.0.0] - 2019-12-06

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


## [1.0.0] - 2017-09-01
Initial release


[Unreleased]: https://github.com/mobicms/captcha/compare/3.1.0...HEAD
[3.1.0]: https://github.com/mobicms/captcha/compare/3.0.0...3.1.0
[3.0.0]: https://github.com/mobicms/captcha/compare/2.0.1...3.0.0
[2.0.1]: https://github.com/mobicms/captcha/compare/2.0.0...2.0.1
[2.0.0]: https://github.com/mobicms/captcha/compare/1.0.0...2.0.0
[1.0.0]: https://github.com/mobicms/captcha/releases/tag/1.0.0
[repository log]: https://github.com/mobicms/captcha/commits/

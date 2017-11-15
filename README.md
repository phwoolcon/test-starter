# test-starter

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

PHPUnit Test Starter for Phwoolcon Projects

## Usage
Please use this library in your phwoolcon project repository (For example `phwoolcon/phwoolcon`).

### For Legacy Projects

1. Checkout your project code;

1. Edit `composer.json`:
    ```bash
    vim composer.json
    ```
    Add `phwoolcon/test-starter` to `require-dev` property:
    ```json
    {
        ...
        "require-dev": {
            "phwoolcon/test-starter": "~1.0"
        },
        ...
    }
    ```
1. Edit `phpunit.xml.dist`:
    ```bash
    vim phpunit.xml.dist
    ```
    Replace `bootstrap` as `vendor/phwoolcon/test-starter/start.php`:
    ```xml
    <phpunit bootstrap="vendor/phwoolcon/test-starter/start.php" ...>
        ...
    </phpunit>
    ```
    Then `composer update` and run your unit test.

### For Newly Created Packages
If you run `package:create` to create your new package, then this library has been included.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email fishdrowned@gmail.com instead of using the issue tracker.

## Credits

- [Christopher CHEN][link-author]
- [All Contributors][link-contributors]

## License

The Apache License, Version 2.0. Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/phwoolcon/test-starter.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-Apache%202.0-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/phwoolcon/test-starter/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/phwoolcon/test-starter.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/phwoolcon/test-starter.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/phwoolcon/test-starter.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/phwoolcon/test-starter
[link-travis]: https://travis-ci.org/phwoolcon/test-starter
[link-scrutinizer]: https://scrutinizer-ci.com/g/phwoolcon/test-starter/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/phwoolcon/test-starter
[link-downloads]: https://packagist.org/packages/phwoolcon/test-starter
[link-author]: https://github.com/Fishdrowned
[link-contributors]: ../../contributors

# Changelog

## [v1.0.2](https://github.com/phwoolcon/test-starter/releases/tag/v1.0.2) (2017-11-17)
#### Tests:
* Don't need db in this package
#### Refactor:
* Preload configs

## [v1.0.1](https://github.com/phwoolcon/test-starter/releases/tag/v1.0.1) (2017-11-17)
#### Refactor:
* Use `Config::$preloadConfig`
* Migrate test config files from `phwoolcon/phwoolcon`

## [v1.0.0](https://github.com/phwoolcon/test-starter/releases/tag/v1.0.0) (2017-11-16)
#### Features:
* Migrate `start.php` and `TestCase` from `phwoolcon/phwoolcon`
#### Tests:
* **Travis**:
  - Declare php versions in string
  - Install dependencies ignoring `ext-phalcon`
* Add `scrutinizer` config

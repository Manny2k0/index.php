# LaravelPackageMakeCommands

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

Extended version of laravel's default `make:*` commands. During package development, this package can be
helpful. 

## Installation

Via Composer

``` bash
composer require wovosoft/laravel-package-make-commands
```

## Usage

Make Model

```shell
php artisan make:package-model Test
php artisan make:package-model Test -m
php artisan make:package-model Test -mc
php artisan make:package-model Test -mcf
```

Make Controller
```shell
php artisan make:package-controller TestController
```

Make Request
```shell
php artisan make:package-request TestRequest
```

Make Factory
```shell
php artisan make:package-factory TestFactory
```

Make Custom Validation Rule
```shell
php artisan make:rule TestRule
```
## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.



## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.


## Credits

- [Narayan Adhikary][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/wovosoft/laravel-package-make-commands.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/wovosoft/laravel-package-make-commands.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/wovosoft/laravel-package-make-commands/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/wovosoft/laravel-package-make-commands
[link-downloads]: https://packagist.org/packages/wovosoft/laravel-package-make-commands
[link-travis]: https://travis-ci.org/wovosoft/laravel-package-make-commands
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/wovosoft
[link-contributors]: ../../contributors

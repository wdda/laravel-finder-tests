# Laravel Finder Tests

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Laravel Finder Unit Tests

## Install

Install package:

``` bash
$ composer require wdda/laravel-finder-tests --dev
```

Open your config/app.php and add the following to the providers array:
``` php
WDDA\LaravelFinderTests\FinderTestsProvider::class,
```

Run the command below to publish the package config file config/finder-tests.php:

``` php
php artisan vendor:publish --tag=finder-tests
```

## Usage

``` php
php artisan finder-tests
```
or for option only not found
``` php
php artisan finder-tests --limit=1
```

axample all settings for config finder-tests.php
```php
return  [
    'directory' => [
        [
            'rootPath' => app_path(),
            'classes' => [ //required
                'dir' => 'Modules/Models', //required
                'methodsExclude' => [
                    '__construct'
                ],
                'classesExclude' => [
                    'App\Modules\Models\Wiki'
                ]
            ],
            'tests' => [ //required
                'dir' => 'Modules/Tests/Unit', //required
                'methodsExclude' => [
                    'test'
                ],
                'classesExclude' => [
                    'App\Modules\Tests\Wiki'
                ]
            ]
        ],
        [
            //Other directory...
        ]
    ]
];

```


## Testing

``` bash
$ phpunit
```

## Security

If you discover any security related issues, please email dima@wdda.pro instead of using the issue tracker.

## Credits

- [Dmitriy Alferov][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/wdda/laravel-finder-tests.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/wdda/laravel-finder-tests/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/wdda/laravel-finder-tests.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/wdda/laravel-finder-tests.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/wdda/laravel-finder-tests.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/wdda/laravel-finder-tests
[link-travis]: https://travis-ci.org/wdda/laravel-finder-tests
[link-scrutinizer]: https://scrutinizer-ci.com/g/wdda/laravel-finder-tests/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/wdda/laravel-finder-tests
[link-downloads]: https://packagist.org/packages/wdda/laravel-finder-tests
[link-author]: https://github.com/wdda

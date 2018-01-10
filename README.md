# Laravel Finder Tests

[![Software License][ico-license]](LICENSE.md)
[![Build Status](https://travis-ci.org/wdda/laravel-finder-tests.svg?branch=v1.0.0)](https://travis-ci.org/wdda/laravel-finder-tests)

Laravel Finder Unit Tests. 

## Notice

The name of the class tests must match exactly with the name of the class
 ```php
//Class
ClassName.php

//Test class
ClassNameTest.php
```
the name of the test methods and the name of the class methods must match
 
```php
//In class
public function methodName() 
{
    ...
}
//In test class
public function testMethodName() 
{
    ...
}
```

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
[link-author]: https://github.com/wdda

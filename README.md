# MNB Exchange Rate package for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/greksazoo/mnb-exchange-laravel.svg?style=flat-square)](https://packagist.org/packages/greksazoo/mnb-exchange-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/greksazoo/mnb-exchange-laravel.svg?style=flat-square)](https://packagist.org/packages/greksazoo/mnb-exchange-laravel)

This is a MNB Exchange Rate query package for Laravel v8 or above.

## Requirements

Package requires PHP v7.4 or above, with Soap and SimpleXml.
Not tested with previous versions of Laravel.

## Know-how

This package is mainly based on top of [MNB package](https://github.com/SzuniSOFT/php-mnb) and 
[MNB Laravel package](https://github.com/SzuniSOFT/laravel-mnb). 

## Installation

You can install the package via composer:

```bash
composer require greksazoo/mnb-exchange-laravel
```
## Configuration
### Export
```bash
php artisan vendor:publish --provider="Greksazoo\MnbExchangeLaravel\MnbExchangeLaravelServiceProvider" --tag="config"
```
### config/mnb-exchange.php
```php
    /*
     * Wsdl file location.
     * */
    'wsdl' => env('MNB_SOAP_WSDL', 'http://www.mnb.hu/arfolyamok.asmx?wsdl'),

    'cache' => [

        /*
         * Desired cache driver for service.
         * */
        'store' => env('MNB_CACHE_DRIVER', 'file'),

        /*
         * Minutes the cached currencies will be held for.
         * Default: 24hrs (1440)
         * */
        'timeout' => env('MNB_CACHE_MINUTES', 1440),
    ]
```

## Usage

### Access via facade
```php
use Greksazoo\MnbExchangeLaravel\Facade\Mnb

$currency = Mnb::currentExchangeRate('EUR');

echo $currency->code; // 'EUR'
echo $currency->getCode(); // 'EUR'
echo $currency->unit; // '1'
echo $currency->getUnit(); // '1'
echo $currency->amount; // '350'
echo $currency->getAmount(); // '350'

```

### Resolve by application container
```php
$currency = app(\Greksazoo\MnbExchangeLaravel\MnbExchangeLaravel::class)->currentExchangeRate('EUR');
```
### Access refresh date by reference
You can check the feed date by passing a $date variable to some methods.
These methods will make variable to be a Carbon instance.

```php
Mnb::exchangeRates($date);
$date->isToday();
```

### Available methods

#### Won't use cache
These methods won't use and update cache.
- currentExchangeRate($code, &$date = null): Currency
- currentExchangeRates(&$date = null): array of Currency

#### Will use cache
These methods will use cache.
- exchangeRate($code, &$date = null): single Currency
- exchangeRates(&$date = null): array of currencies
- currencies(): array of strings (each is currency code)
- hasCurrency($code): bool
### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email zoli.greksa@gmail.com instead of using the issue tracker.

## Credits

-   [Zoltan Greksa](https://github.com/greksazoo)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).

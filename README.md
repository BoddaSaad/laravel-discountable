# Discountable

Make your models discountable with Laravel, allowing you to apply vouchers with lots of options.


## Installation

You can install the package via composer:

```bash
composer require boddasaad/laravel-discountable
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-discountable-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-discountable-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-discountable-views"
```

## Usage

```php
$voucher = new BoddaSaad\Voucher();
echo $voucher->echoPhrase('Hello, BoddaSaad!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Abdur-Rahman Saad](https://github.com/BoddaSaad)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

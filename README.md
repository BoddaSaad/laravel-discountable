# Discountable

Make your models discountable with Laravel, allowing you to apply vouchers with lots of options and conditions.
Here's a quick example of how to use the package:

```php
$student = Student::find(1);
// Check if the voucher is valid and can be applied (useful for UI validation before applying)
$student->checkVoucher('SUMMER2023', 100);

// Redeem the voucher, applying the discount
$student->redeemVoucher('SUMMER2023', 100);
```

## Installation

You can install the package via composer:

```bash
composer require boddasaad/laravel-discountable

php artisan discountable:install
```

This is the contents of the published config file:

```php
return [
    /*
     * Database table name that will be used to store vouchers.
     */
    'table' => 'vouchers',

    /*
     * Database table name that will be used to store voucher usages.
     */
    'usage_table' => 'voucher_usages',

    /*
     * List of characters that will be used for voucher code generation.
     */
    'characters' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',

    /*
     * Voucher code prefix.
     *
     * Example: foo
     * Generated Code: foo-AGXF-1NH8
     */
    'prefix' => null,

    /*
     * Voucher code suffix.
     *
     * Example: foo
     * Generated Code: AGXF-1NH8-foo
     */
    'suffix' => null,

    /*
     * Code mask.
     * All asterisks will be removed by random characters.
     */
    'mask' => '****-****',

    /*
     * Separator to be used between prefix, code and suffix.
     */
    'separator' => '-',
];
```

## Usage

To use the package, you need to add the `CanRedeemVouchers` trait to your model:
```php
namespace App\Models;

use BoddaSaad\Voucher\Traits\CanRedeemVouchers;

class User extends Authenticatable
{
    use CanRedeemVouchers;
    # ...
}
```

## Creating Vouchers
You can create vouchers using the `Voucher` Facade. Here's an example of how to create a voucher with various options:
```php
use BoddaSaad\Voucher\Facades\Voucher;

$voucher = Voucher::quantity(1000)
        ->discount('percentage', 20)
        ->date('2025-01-01', '2025-12-31')
        ->minimumQualifyingAmount(50)
        ->maximumDiscountAmount(100)
        ->maxUsagesPerModel(1)
        ->data(['description' => 'Holiday Discount'])
        ->create();
```

### Voucher Facade Methods

| Method                       | Description                                                    |
|------------------------------|----------------------------------------------------------------|
| `discount(type, value)`      | Set the discount type (`percentage` or `fixed`) and its value. |
| `quantity(int $amount)`      | Set the quantity available for this voucher.                   |
| `date(start, end)`           | Set the start and end date for voucher validity.               |
| `minimumQualifyingAmount(n)` | Set the minimum amount required to use the voucher.            |
| `maximumDiscountAmount(n)`   | Set the maximum discount amount that can be applied.           |
| `maxUsagesPerModel(n)`       | Set the max times a voucher can be used per model (e.g. user). |
| `data(array $data)`          | Attach additional data to the voucher.                         |
| `prefix(string $prefix)`     | Set a custom prefix for the voucher code in runtime.           |
| `suffix(string $suffix)`     | Set a custom suffix for the voucher code in runtime.           |
| `separator(string $sep)`     | Set a custom separator for the voucher code in runtime.        |
| `create()`                   | Create the voucher with the specified options.                 |

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

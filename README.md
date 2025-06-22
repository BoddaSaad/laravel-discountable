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

$voucher = Voucher::maximumRedeems(1000)
        ->discount('percentage', 20)
        ->date('2025-01-01', '2025-12-31')
        ->minimumQualifyingAmount(50)
        ->maximumDiscountAmount(100)
        ->maxUsagesPerModel(1)
        ->data(['description' => 'Holiday Discount'])
        ->create();
```

### Voucher Facade Methods

| Method                       | Description                                                                                                                |
|------------------------------|----------------------------------------------------------------------------------------------------------------------------|
| `discount(type, value)`      | If `type` is `percentage`, `value` should be between 0 and 100. If `type` is `fixed`, `value` should be a positive number. |
| `maximumRedeems(int $max)`   | This limits how many times the voucher can be redeemed in total.                                                           |
| `date(start, end)`           | Both dates should be in `Y-m-d` format. The voucher will be valid between these dates.                                     |
| `minimumQualifyingAmount(n)` | This is the minimum amount that must be spent to apply the voucher.                                                        |
| `maximumDiscountAmount(n)`   | This limits the discount to a maximum value. For percentage discounts, this is the maximum amount that can be discounted.  |
| `maxUsagesPerModel(n)`       | This limits how many times a single model can use the voucher.                                                             |
| `data(array $data)`          | This can be any additional information you want to store with the voucher, such as a description or terms and conditions.  |
| `prefix(string $prefix)`     | This allows you to specify a prefix for the voucher code dynamically.                                                      |
| `suffix(string $suffix)`     | This allows you to specify a suffix for the voucher code dynamically.                                                      |
| `separator(string $sep)`     | This allows you to specify a custom separator for the voucher code dynamically.                                            |
| `create()`                   | This method will generate the voucher code and save it to the database. (MUST BE CALLED LAST)                              |

## Applying Vouchers
To apply a voucher, you can use the `checkVoucher` and `redeemVoucher` methods provided by the `CanRedeemVouchers` trait.

### Check Voucher Validity
This is useful for UI validation before applying the voucher so the user can see if the voucher is valid or not.:
```php
auth()->user()->checkVoucher('SUMMER2023', 100);
```
This will return an object with the following properties if the voucher is valid:
```php
[
    'valid' => true,
    'final_amount' => 80, // The amount after applying the voucher
    'voucher_id' => 1 // The ID of the voucher
]
```
If the voucher is not valid, it will return an object with the following properties:
```php
[
    'valid' => false,
    'message' => 'Voucher has expired' // The reason why the voucher is not valid
]
```

### Redeem Voucher
To redeem the voucher and apply the discount, you can use the `redeemVoucher` method:
```php
auth()->user()->redeemVoucher('SUMMER2023', 100);
// Will return true if the voucher was successfully redeemed, or false if it was not.
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

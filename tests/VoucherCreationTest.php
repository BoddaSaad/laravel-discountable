<?php

use BoddaSaad\Voucher\Enums\DiscountType;
use BoddaSaad\Voucher\Facades\Voucher;
use BoddaSaad\Voucher\VoucherGenerator;
use Illuminate\Support\Carbon;

it('can generate a code with suffix, prefix and mask', function () {
    $mask = '******';
    $generator = new VoucherGenerator(mask: $mask);
    $generator->setPrefix('TEST');
    $generator->setSuffix('TEST');
    $generator->setSeparator('-');
    $code = $generator->generate();
    expect($code)->toStartWith('TEST-')
        ->and($code)->toEndWith('-TEST')
        ->and($code)->toHaveLength(strlen('TEST-') + strlen('-TEST') + strlen($mask));
});

it('can create a voucher', function () {
    $voucher = Voucher::maximumRedeems(1000)
        ->discount('percentage', 20)
        ->date('2025-01-01', '2025-12-31')
        ->minimumQualifyingAmount(50)
        ->maximumDiscountAmount(100)
        ->maxUsagesPerModel(1)
        ->data(['description' => 'Holiday Discount'])
        ->create();

    expect($voucher)->toBeInstanceOf(\BoddaSaad\Voucher\Models\Voucher::class)
        ->and($voucher->code)->toBeString()
        ->and($voucher->maximum_redeems)->toBe(1000)
        ->and($voucher->discount_type)->toBe(DiscountType::PERCENTAGE)
        ->and($voucher->discount_value)->toBe(20)
        ->and($voucher->start_date)->toEqual(Carbon::parse('2025-01-01'))
        ->and($voucher->end_date)->toEqual(Carbon::parse('2025-12-31'))
        ->and($voucher->minimum_qualifying_amount)->toBe(50)
        ->and($voucher->maximum_discount_amount)->toBe(100)
        ->and($voucher->max_usages_per_model)->toBe(1)
        ->and($voucher->data['description'])->toBe('Holiday Discount');
});

it('can create a voucher with custom prefix, suffix and separator', function () {
    $voucher = Voucher::maximumRedeems(500)
        ->discount('fixed', 10)
        ->date('2025-01-01', '2025-12-31')
        ->prefix('VOUCHER')
        ->suffix('2025')
        ->separator('_')
        ->create();

    expect($voucher)->toBeInstanceOf(\BoddaSaad\Voucher\Models\Voucher::class)
        ->and($voucher->code)->toStartWith('VOUCHER_')
        ->and($voucher->code)->toEndWith('_2025');
});

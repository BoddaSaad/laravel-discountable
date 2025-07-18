<?php

use BoddaSaad\Voucher\Enums\DiscountType;
use BoddaSaad\Voucher\Facades\Voucher;
use BoddaSaad\Voucher\Models\Voucher as VoucherModel;
use BoddaSaad\Voucher\Tests\Models\User;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->voucher = Voucher::maximumRedeems(1000)
        ->discount('percentage', 10)
        ->date('2025-01-01', '2025-12-31')
        ->minimumQualifyingAmount(50)
        ->maximumDiscountAmount(100)
        ->maxUsagesPerModel(1)
        ->create();

    $this->user = User::first();
});

it('cannot apply voucher with unqualified amount', function () {
    $response = $this->user->checkVoucher($this->voucher->code, 40);

    expect($response->valid)->toBeFalse();
});

it('cannot apply voucher before start date', function () {
    Carbon::setTestNow('2023-01-01');
    $response = $this->user->checkVoucher($this->voucher->code, 100);

    expect($response->valid)->toBeFalse();
});

it('cannot apply voucher after end date', function () {
    Carbon::setTestNow('2026-01-01');
    $response = $this->user->checkVoucher($this->voucher->code, 100);

    expect($response->valid)->toBeFalse();
});

it('cannot apply voucher when maximum redeems is zero', function () {
    $this->voucher->maximum_redeems = 0;
    $this->voucher->save();

    $response = $this->user->checkVoucher($this->voucher->code, 100);

    expect($response->valid)->toBeFalse();
});

it('cannot apply inactive voucher', function () {
    $this->voucher->is_active = false;
    $this->voucher->save();

    $response = $this->user->checkVoucher($this->voucher->code, 100);

    expect($response->valid)->toBeFalse();
});

it('cannot apply voucher for the same user more than the allowed times', function () {
    $this->user->voucherUsages()->create([
        'voucher_id' => $this->voucher->id,
        'original_amount' => 100,
        'final_amount' => 90,
    ]);

    $response = $this->user->checkVoucher($this->voucher->code, 100);

    expect($response->valid)->toBeFalse();
});

it('cannot apply voucher with amount less than fixed discount', function () {
    $this->voucher->discount_type = DiscountType::FIXED;
    $this->voucher->discount_value = 50;
    $this->voucher->minimum_qualifying_amount = null;
    $this->voucher->save();

    $response = $this->user->checkVoucher($this->voucher->code, 40);

    expect($response->valid)->toBeFalse();
});

it('can apply voucher with valid conditions', function () {
    $response = $this->user->checkVoucher($this->voucher->code, 100);

    expect($response->valid)->toBeTrue()
        ->and((int) $response->final_amount)->toBe(90);
});

it('redeems voucher successfully and records usage for valid and decrement maximum_redeems', function () {
    $response = $this->user->redeemVoucher($this->voucher->code, 100);

    expect($response)->toBeTrue()
        ->and($this->user->voucherUsages()->count())->toBe(1)
        ->and(VoucherModel::find($this->voucher->id)->maximum_redeems)->toBe(999);
});

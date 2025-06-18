<?php

use BoddaSaad\Voucher\Facades\Voucher;

it('can test', function () {
    $voucher = Voucher::quantity(10)
        ->discount('percentage', 20)
        ->date('2023-01-01', '2023-12-31')
        ->maximumDiscountAmount(100)
        ->create();
});

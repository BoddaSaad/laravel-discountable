<?php

namespace BoddaSaad\Voucher\Traits;

use BoddaSaad\Voucher\DiscountContext;
use BoddaSaad\Voucher\Models\VoucherUsage;
use BoddaSaad\Voucher\Facades\Voucher;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use BoddaSaad\Voucher\Models\Voucher as VoucherModel;

trait CanRedeemVouchers
{
    public function voucherUsages(): MorphMany
    {
        return $this->morphMany(VoucherUsage::class, 'model');
    }

    public function checkVoucher($code, $amount): object
    {
        $voucher = VoucherModel::whereCode($code)->first();

        if (! $voucher) {
            throw new \Exception('Voucher does not exist');
        }

        $discountContext = new DiscountContext($voucher, $this, $amount);

        return Voucher::checkVoucher($discountContext);
    }
}

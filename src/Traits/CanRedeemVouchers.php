<?php

namespace BoddaSaad\Voucher\Traits;

use BoddaSaad\Voucher\DiscountContext;
use BoddaSaad\Voucher\Facades\Voucher;
use BoddaSaad\Voucher\Models\Voucher as VoucherModel;
use BoddaSaad\Voucher\Models\VoucherUsage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function redeemVoucher($code, $amount): bool
    {
        $validity = $this->checkVoucher($code, $amount);

        if (! $validity->valid) {
            return false;
        }

        $this->voucherUsages()->create([
            'voucher_id' => $validity->voucher_id,
            'original_amount' => $amount,
            'final_amount' => $validity->final_amount,
        ]);

        VoucherModel::find($validity->voucher_id)->decrement('maximum_redeems');

        return true;
    }
}

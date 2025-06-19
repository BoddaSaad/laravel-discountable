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

    public function redeemVoucher($code, $amount): bool
    {
        $validity = $this->checkVoucher($code, $amount);

        if (!$validity->status) {
            return false;
        }

        $this->voucherUsages()->create([
            'voucher_id' => $validity->voucher_id,
            'original_amount' => $amount,
            'final_amount' => $validity->final_amount,
        ]);

        return true;
    }
}

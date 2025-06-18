<?php

namespace BoddaSaad\Voucher\Traits;

use BoddaSaad\Voucher\Models\VoucherUsage;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait CanRedeemVouchers
{
    public function voucherUsages(): MorphMany
    {
        return $this->morphMany(VoucherUsage::class, 'model');
    }
}

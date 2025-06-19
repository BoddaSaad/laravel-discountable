<?php

namespace BoddaSaad\Voucher\Traits;

use BoddaSaad\Voucher\DiscountContext;
use BoddaSaad\Voucher\Models\Voucher;
use BoddaSaad\Voucher\Models\VoucherUsage;
use BoddaSaad\Voucher\Pipelines\ApplyDiscount;
use BoddaSaad\Voucher\Pipelines\IsAmountQualified;
use BoddaSaad\Voucher\Pipelines\ModelVoucherUsages;
use BoddaSaad\Voucher\Pipelines\VoucherIsActive;
use BoddaSaad\Voucher\Pipelines\VoucherQuantityValidity;
use BoddaSaad\Voucher\Pipelines\VoucherDateValidity;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Pipeline\Pipeline;

trait CanRedeemVouchers
{
    public function voucherUsages(): MorphMany
    {
        return $this->morphMany(VoucherUsage::class, 'model');
    }

    public function canRedeem($code, $amount)
    {
        $voucher = Voucher::whereCode($code)->first();

        if (! $voucher) {
            throw new \Exception("Voucher does not exist");
        }

        $discountContext = new DiscountContext($voucher, $this, $amount);

        try {
            return app(Pipeline::class)
                ->send($discountContext)
                ->through([
                    VoucherIsActive::class,
                    VoucherDateValidity::class,
                    VoucherQuantityValidity::class,
                    IsAmountQualified::class,
                    ModelVoucherUsages::class,
                    ApplyDiscount::class
                ])
                ->then(function ($context) {;
                    return [
                        'status' => true,
                        'final_amount' => $context->discountAmount,
                    ];
                });
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

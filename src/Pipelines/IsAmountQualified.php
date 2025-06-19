<?php

namespace BoddaSaad\Voucher\Pipelines;

use BoddaSaad\Voucher\DiscountContext;
use BoddaSaad\Voucher\Enums\DiscountType;
use Closure;

final readonly class IsAmountQualified
{
    public function handle(DiscountContext $discountContext, Closure $next)
    {
        if ($discountContext->amount < $discountContext->voucher->minimum_qualifying_amount) {
            throw new \Exception('Amount is not qualified for this voucher');
        }

        if ($discountContext->voucher->discount_type === DiscountType::FIXED &&
            $discountContext->amount < $discountContext->voucher->discount_value) {
            throw new \Exception('Amount is less than the fixed discount value');
        }

        return $next($discountContext);
    }
}

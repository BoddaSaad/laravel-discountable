<?php

namespace BoddaSaad\Voucher\Pipelines;

use BoddaSaad\Voucher\DiscountContext;
use BoddaSaad\Voucher\Enums\DiscountType;
use Closure;

final readonly class ApplyDiscount
{
    public function handle(DiscountContext $discountContext, Closure $next)
    {
        if ($discountContext->voucher->discount_type === DiscountType::PERCENTAGE) {
            $discountAmount = ($discountContext->amount * $discountContext->voucher->discount_value) / 100;
        } else {
            $discountAmount = $discountContext->voucher->discount_value;
        }

        $discountContext->discountAmount = $discountContext->amount - $discountAmount;

        return $next($discountContext);
    }
}

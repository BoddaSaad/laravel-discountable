<?php

namespace BoddaSaad\Voucher\Pipelines;

use BoddaSaad\Voucher\DiscountContext;
use Closure;

final readonly class VoucherIsActive
{
    public function handle(DiscountContext $discountContext, Closure $next)
    {
        if (!$discountContext->voucher->is_active) {
            throw new \Exception("Voucher is inactive");
        }

        return $next($discountContext);
    }
}

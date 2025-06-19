<?php

namespace BoddaSaad\Voucher\Pipelines;

use BoddaSaad\Voucher\DiscountContext;
use Closure;

final readonly class VoucherQuantityValidity
{
    public function handle(DiscountContext $discountContext, Closure $next)
    {
        if ($discountContext->voucher->quantity === 0) {
            throw new \Exception('Voucher is no longer available for use');
        }

        return $next($discountContext);
    }
}

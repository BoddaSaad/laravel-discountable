<?php

namespace BoddaSaad\Voucher\Pipelines;

use BoddaSaad\Voucher\DiscountContext;
use Closure;

final readonly class VoucherDateValidity
{
    public function handle(DiscountContext $discountContext, Closure $next)
    {
        if ($discountContext->voucher->start_date > now()) {
            throw new \Exception('Voucher is not valid yet');
        }

        if ($discountContext->voucher->end_date && $discountContext->voucher->end_date < now()) {
            throw new \Exception('Voucher has expired');
        }

        return $next($discountContext);
    }
}

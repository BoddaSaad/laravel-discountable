<?php

namespace BoddaSaad\Voucher\Pipelines;

use BoddaSaad\Voucher\DiscountContext;
use Closure;

final readonly class ModelVoucherUsages
{
    public function handle(DiscountContext $discountContext, Closure $next)
    {
        $usages = $discountContext->model->voucherUsages()->where('voucher_id', $discountContext->voucher->id)->count();

        if ($discountContext->voucher->max_usages_per_model && $usages >= $discountContext->voucher->max_usages_per_model) {
            throw new \Exception(' Maximum voucher usages reached for this model.');
        }

        return $next($discountContext);
    }
}

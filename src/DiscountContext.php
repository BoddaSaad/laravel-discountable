<?php

namespace BoddaSaad\Voucher;

use Illuminate\Database\Eloquent\Model;
use BoddaSaad\Voucher\Models\Voucher;

class DiscountContext
{
    public function __construct(
        public Voucher $voucher,
        public Model $model,
        public float $amount,
        public ?float $discountAmount = null
    ) {}
}

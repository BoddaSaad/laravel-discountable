<?php

namespace BoddaSaad\Voucher;

use BoddaSaad\Voucher\Models\Voucher;
use Illuminate\Database\Eloquent\Model;

class DiscountContext
{
    public function __construct(
        public Voucher $voucher,
        public Model $model,
        public float $amount,
        public ?float $discountAmount = null
    ) {}
}

<?php

namespace BoddaSaad\Voucher\Tests\Models;

use BoddaSaad\Voucher\Traits\CanRedeemVouchers;

class User extends \Illuminate\Foundation\Auth\User
{
    use CanRedeemVouchers;
}

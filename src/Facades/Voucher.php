<?php

namespace BoddaSaad\Voucher\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @mixin \BoddaSaad\Voucher\Voucher
 */
class Voucher extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \BoddaSaad\Voucher\Voucher::class;
    }
}

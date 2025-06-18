<?php

namespace BoddaSaad\Voucher\Commands;

use Illuminate\Console\Command;

class VoucherCommand extends Command
{
    public $signature = 'laravel-discountable';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}

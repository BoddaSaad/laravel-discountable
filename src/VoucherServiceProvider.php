<?php

namespace BoddaSaad\Voucher;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use BoddaSaad\Voucher\Commands\VoucherCommand;

class VoucherServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-discountable')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_discountable_table')
            ->hasCommand(VoucherCommand::class);
    }
}

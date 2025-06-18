<?php

namespace BoddaSaad\Voucher;

use BoddaSaad\Voucher\Commands\VoucherCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class VoucherServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-discountable')
            ->hasConfigFile()
            ->hasMigration('create_laravel_discountable_table')
            ->hasCommand(VoucherCommand::class);
    }

    public function packageRegistered()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/discountable.php', 'discountable');

        $this->app->singleton('voucher', function ($app) {
            $generator = new VoucherGenerator(config('discountable.characters'), config('discountable.mask'));
            $generator->setPrefix(config('discountable.prefix'));
            $generator->setSuffix(config('discountable.suffix'));
            $generator->setSeparator(config('discountable.separator'));

            return new Voucher($generator);
        });
    }
}

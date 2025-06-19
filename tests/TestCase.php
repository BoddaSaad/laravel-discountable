<?php

namespace BoddaSaad\Voucher\Tests;

use BoddaSaad\Voucher\Facades\Voucher;
use BoddaSaad\Voucher\VoucherServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;
use Illuminate\Foundation\Auth\User;

#[WithMigration()]
class TestCase extends Orchestra
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BoddaSaad\\Voucher\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        User::forceCreate([
            'name' => 'User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            VoucherServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Voucher' => Voucher::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $migration = include __DIR__.'/../database/migrations/create_discountable_table.php.stub';
        $migration->up();
    }
}

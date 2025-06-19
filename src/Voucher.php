<?php

namespace BoddaSaad\Voucher;

use BoddaSaad\Voucher\Enums\DiscountType;
use BoddaSaad\Voucher\Models\Voucher as VoucherModel;
use BoddaSaad\Voucher\Pipelines\ApplyDiscount;
use BoddaSaad\Voucher\Pipelines\IsAmountQualified;
use BoddaSaad\Voucher\Pipelines\ModelVoucherUsages;
use BoddaSaad\Voucher\Pipelines\VoucherDateValidity;
use BoddaSaad\Voucher\Pipelines\VoucherIsActive;
use BoddaSaad\Voucher\Pipelines\VoucherMaximumRedeemsValidity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;

class Voucher
{
    private VoucherGenerator $generator;

    private VoucherModel $voucherModel;

    public function __construct(VoucherGenerator $generator)
    {
        $this->generator = $generator;
        $this->voucherModel = new VoucherModel;
    }

    protected function getUniqueVoucher(): string
    {
        $voucher = $this->generator->generate();

        while ($this->voucherModel->whereCode($voucher)->count() > 0) {
            $voucher = $this->generator->generate();
        }

        return $voucher;
    }

    public function maximumRedeems(int $max): self
    {
        $this->voucherModel->maximum_redeems = $max;

        return $this;
    }

    public function date(?string $startDate, ?string $endDate): self
    {
        $this->voucherModel->start_date = $startDate ?? now()->toDateTimeString();
        $this->voucherModel->end_date = $endDate;

        return $this;
    }

    public function maxUsagesPerModel(int $maxUsages): self
    {
        $this->voucherModel->max_usages_per_model = $maxUsages;

        return $this;
    }

    public function discount(string $type, $value): self
    {
        $type = DiscountType::from($type);
        $this->voucherModel->discount_type = $type;
        $this->voucherModel->discount_value = $value;

        return $this;
    }

    public function maximumDiscountAmount($amount): self
    {
        $this->voucherModel->maximum_discount_amount = $amount;

        return $this;
    }

    public function minimumQualifyingAmount($amount): self
    {
        $this->voucherModel->minimum_qualifying_amount = $amount;

        return $this;
    }

    public function data(array $data): self
    {
        $this->voucherModel->data = $data;

        return $this;
    }

    public function separator(string $separator): self
    {
        $this->generator->setSeparator($separator);

        return $this;
    }

    public function prefix(string $prefix): self
    {
        $this->generator->setPrefix($prefix);

        return $this;
    }

    public function suffix(string $suffix): self
    {
        $this->generator->setSuffix($suffix);

        return $this;
    }

    public function create(): Model
    {
        $this->voucherModel->code = $this->getUniqueVoucher();
        $this->voucherModel->save();

        return $this->voucherModel;
    }

    public function checkVoucher(DiscountContext $discountContext): object
    {
        try {
            return app(Pipeline::class)
                ->send($discountContext)
                ->through([
                    VoucherIsActive::class,
                    VoucherDateValidity::class,
                    VoucherMaximumRedeemsValidity::class,
                    IsAmountQualified::class,
                    ModelVoucherUsages::class,
                    ApplyDiscount::class,
                ])
                ->then(function ($context) {
                    return (object) [
                        'valid' => true,
                        'final_amount' => $context->discountAmount,
                        'voucher_id' => $context->voucher->id,
                    ];
                });
        } catch (\Exception $e) {
            return (object) [
                'valid' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}

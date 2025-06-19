<?php

namespace BoddaSaad\Voucher\Models;

use BoddaSaad\Voucher\Enums\DiscountType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * \BoddaSaad\Voucher\Models\Voucher
 *
 * @property int $id
 * @property string $code
 * @property int|null $maximum_redeems
 * @property string|null $start_date
 * @property string|null $end_date
 * @property bool $is_active
 * @property array|null $data
 * @property \BoddaSaad\Voucher\Enums\DiscountType $discount_type
 * @property float|int $discount_value
 * @property float|int|null $maximum_discount_amount
 * @property float|int|null $minimum_qualifying_amount
 * @property int|null $max_usages_per_model
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Voucher extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'data' => 'collection',
        'discount_type' => DiscountType::class,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('discountable.table', 'vouchers');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class, 'voucher_id');
    }
}

<?php

return [
    /*
     * Database table name that will be used to store vouchers.
     */
    'table' => 'vouchers',

    /*
     * Database table name that will be used to store voucher usages.
     */
    'usage_table' => 'voucher_usages',

    /*
     * List of characters that will be used for voucher code generation.
     */
    'characters' => '23456789ABCDEFGHJKLMNPQRSTUVWXYZ',

    /*
     * Voucher code prefix.
     *
     * Example: foo
     * Generated Code: foo-AGXF-1NH8
     */
    'prefix' => null,

    /*
     * Voucher code suffix.
     *
     * Example: foo
     * Generated Code: AGXF-1NH8-foo
     */
    'suffix' => null,

    /*
     * Code mask.
     * All asterisks will be removed by random characters.
     */
    'mask' => '****-****',

    /*
     * Separator to be used between prefix, code and suffix.
     */
    'separator' => '-',
];

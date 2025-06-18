<?php

namespace BoddaSaad\Voucher;

use Illuminate\Support\Str;

class VoucherGenerator
{
    protected $characters;

    protected $mask;

    protected $prefix;

    protected $suffix;

    protected $separator = '-';

    protected $generatedCodes = [];

    public function __construct(string $characters = 'ABCDEFGHJKLMNOPQRSTUVWXYZ234567890', string $mask = '****-****')
    {
        $this->characters = $characters;
        $this->mask = $mask;
    }

    public function setPrefix(?string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function setSuffix(?string $suffix): void
    {
        $this->suffix = $suffix;
    }

    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }

    public function generate(): string
    {
        $length = substr_count($this->mask, '*');

        $mask = $this->mask;
        $characters = collect(str_split($this->characters));

        for ($i = 0; $i < $length; $i++) {
            $mask = Str::replaceFirst('*', $characters->random(1)->first(), $mask);
        }

        return $this->getPrefix().$mask.$this->getSuffix();
    }

    protected function getPrefix(): string
    {
        return $this->prefix !== null ? $this->prefix.$this->separator : '';
    }

    protected function getSuffix(): string
    {
        return $this->suffix !== null ? $this->separator.$this->suffix : '';
    }
}

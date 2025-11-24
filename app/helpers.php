<?php

use App\Helpers\CurrencyHelper;

if (!function_exists('format_rupiah')) {
    /**
     * Format number as Rupiah currency
     * 
     * @param float|int $amount
     * @return string
     */
    function format_rupiah(float|int $amount): string
    {
        return CurrencyHelper::formatRupiah($amount);
    }
}

if (!function_exists('usd_to_idr')) {
    /**
     * Convert USD to IDR and format as Rupiah
     * 
     * @param float|int $amountUSD
     * @return string
     */
    function usd_to_idr(float|int $amountUSD): string
    {
        return CurrencyHelper::usdToIdr($amountUSD);
    }
}

if (!function_exists('format_number')) {
    /**
     * Format number with Indonesian thousand separators
     * 
     * @param float|int $number
     * @param int $decimals
     * @return string
     */
    function format_number(float|int $number, int $decimals = 0): string
    {
        return CurrencyHelper::formatNumber($number, $decimals);
    }
}

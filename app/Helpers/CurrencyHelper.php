<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Kurs USD ke IDR (bisa diupdate sesuai kurs terkini)
     */
    private const USD_TO_IDR_RATE = 15500;
    
    /**
     * Convert USD to IDR and format as Rupiah
     * 
     * @param float|int $amountUSD Amount in USD
     * @return string Formatted amount in Rupiah (e.g., "Rp 15.500.000")
     */
    public static function usdToIdr(float|int $amountUSD): string
    {
        if ($amountUSD <= 0) {
            return 'Rp 0';
        }
        
        $amountIDR = $amountUSD * self::USD_TO_IDR_RATE;
        return self::formatRupiah($amountIDR);
    }
    
    /**
     * Format number as Rupiah currency
     * 
     * @param float|int $amount Amount to format
     * @return string Formatted amount in Rupiah
     */
    public static function formatRupiah(float|int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
    
    /**
     * Format number with thousand separators (Indonesian format)
     * 
     * @param float|int $number Number to format
     * @param int $decimals Number of decimal places
     * @return string Formatted number
     */
    public static function formatNumber(float|int $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, ',', '.');
    }
}

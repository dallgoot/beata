<?php

namespace Stef\Beata\Calculations;

use Stef\Beata\Providers\BinLookup\BinListNet;
use Stef\Beata\Providers\CurrenciesRates\ExchangeRatesIo;

/**
 * Computes Commission and displays it for each line in a file
 * Note: line in file are expected to be in a JSON format
 */
class Commissions
{
    const EU_COMMISSION = 0.01;
    const NON_EU_COMMISSION = 0.02;
    const EU_COUNTRIES = [
        'AT','BE','BG','CY','CZ','DE','DK','EE','ES','FI','FR','GR','HR','HU','IE','IT','LT','LU','LV','MT','NL','PO','PT','RO','SE','SI','SK'
    ];

    /**
     * Read, parse a file where lines are JSON formatted and display the resulting commission for each line
     *
     * @param string $fileName Can be a full path
     * @return void
     */
    public static function showFromFile(string $fileName): void
    {
        if(!file_exists($fileName)) {
            throw new \ErrorException("Error '$fileName' is NOT a valid file", 1);
        }
        $handle = fopen($fileName, "r");

        for ($i=1; !feof($handle); $i++) {
            $contents = fgets($handle);
            $entry = json_decode($contents);
            if(json_last_error() !== \JSON_ERROR_NONE) {
                echo "\n" . json_last_error_msg() . "in line $i";
                continue;
            }
            $countryCode = BinListNet::getAlpha2CountryCodeByBin($entry->bin);
            $exchangeRate = ExchangeRatesIo::getEuroRateFromCurrency($entry->currency);         
            $commission = self::getCommissionByAlpha2CountryCode($countryCode);
            /**
             * UNCOMMENT THESE FOR LONG VERSION DEBUG INFOS
             * echo "\n$contents";
             * echo  sprintf('amount:%f, rate:%f, inEuro:%f, commission:%f, rounded:%.2f', 
             * $entry->amount ,
             * $exchangeRate,
             * $entry->amount / $exchangeRate,
             * $entry->amount / $exchangeRate * $commission,
             * round($entry->amount / $exchangeRate * $commission, 2, PHP_ROUND_HALF_UP)
             * );
             */
            echo "\n" . round($entry->amount / $exchangeRate * $commission, 2, PHP_ROUND_HALF_UP);
        }
        fclose($handle);
    }

    /**
     * Return the Commission factor according to provided country code (alpha2 format)
     *
     * @param string $alpha2CountryCode
     * @return float
     */
    public static function getCommissionByAlpha2CountryCode(string $alpha2CountryCode): float
    {
        if(empty($alpha2CountryCode) || strlen($alpha2CountryCode) > 2) {
            throw new \ValueError('Country code must be a valid 2 letters country code');
        }
        return in_array(strtoupper($alpha2CountryCode), self::EU_COUNTRIES) ? self::EU_COMMISSION : self::NON_EU_COMMISSION;
    }
}

<?php

namespace Stef\Beata\Providers\CurrenciesRates;

use Stef\Beata\Providers\CurrenciesRates\CurrenciesRatesInterface;

/**
 * Provides a method to get Euro rate for provided Currency : eg: 1€ -> 0.98$
 * Uses the webservice @ https://api.apilayer.com/
 */
class ExchangeRatesIo implements CurrenciesRatesInterface
{
    private static $MEMOIZATION = null;
    const DATA_TTL = 60;// = minutes
    static $LAST_REQUEST_TIME = 0;

    // const API_URL = 'https://api.exchangeratesapi.io/latest';// NOTE : inaccessible when using free membership
    const API_URL = "https://api.apilayer.com/exchangerates_data/latest";
    const API_KEY='qktQV17xTUXG4c3HKytQbZeE340wijKg';

    /**
     * Ask the webservice for exchange rate based on provided currency
     * Note: this method memorises ALL available currencies on first call to prevent network latencies
     * It automatically restart a request when DATA_TTL is reached
     *
     * @param string $currency
     * @return float
     */
    public static function getEuroRateFromCurrency(string $currency): float
    {
        if(empty($currency) || strlen($currency) < 2) {
            throw new \ValueError("Error Processing Request", 1);
        }
        if ((time() - self::$LAST_REQUEST_TIME) > (self::DATA_TTL * 60)) {
            $ch = curl_init(self::API_URL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: '.self::API_KEY]);
            $content = curl_exec($ch);
            curl_close($ch);
            if(is_bool($content)) {
                throw new \RuntimeException('Error: Could not request from api.exchangeratesapi.io:');
            }
            $result = json_decode($content);
            if(json_last_error() !== \JSON_ERROR_NONE || property_exists($result, 'error')) {
                throw new \RuntimeException('Error: Could not read content from api.exchangeratesapi.io:'. json_last_error_msg(). $result->error->info);
            }
            self::$MEMOIZATION = $result;
            self::$LAST_REQUEST_TIME = time();
        }
        return (float) self::$MEMOIZATION->rates->{strtoupper($currency)};
    }

}

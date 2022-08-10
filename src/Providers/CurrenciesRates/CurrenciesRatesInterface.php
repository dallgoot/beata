<?php

namespace Stef\Beata\Providers\CurrenciesRates;

/**
 * Provides method that a Currencies Rate Provider must implement
 */
interface CurrenciesRatesInterface 
{
    // to provide a time in minutes after which the provider data is considered obsolete
    const DATA_TTL = 30;
    static function getEuroRateFromCurrency(string $currency): float;
}
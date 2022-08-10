<?php

namespace Stef\Beata\Providers\BinLookup;

/**
 * Defines methods that a BinLookup provider must implement
 */
interface BinLookupInterface
{
    public static function getAlpha2CountryCodeByBin(int $bin): string;
}
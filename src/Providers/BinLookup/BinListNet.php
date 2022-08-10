<?php

namespace Stef\Beata\Providers\BinLookup;

use Stef\Beata\Providers\BinLookup\BinLookupInterface;


/**
 * Provides a method to get a country code based on a BIN number
 * Use the web service @ https://lookup.binlist.net/
 */
class BinListNet implements BinLookupInterface
{
    private static $MEMOIZATION = [];

    /**
     * Ask the webservice for the provided BIN number and extract only the alpha2 country code
     * (others infos are provided but not used currently)
     * Note: each call for a BIN is memorized to prevent multiple network requests
     *
     * @param integer $bin
     * @return string
     */
    static public function getAlpha2CountryCodeByBin(int $bin): string
    {
        if(in_array($bin, array_keys(self::$MEMOIZATION))) {
            return self::$MEMOIZATION[$bin];
        }
        $binResults = file_get_contents('https://lookup.binlist.net/' . ((string) $bin));
        if($binResults === FALSE) {
          throw new \RuntimeException('Error: Could not contact BinListNet');
        }
        $alpha2CountryCode = json_decode($binResults)->country->alpha2;
        self::$MEMOIZATION[$bin] = $alpha2CountryCode;
        return $alpha2CountryCode;
    }
}

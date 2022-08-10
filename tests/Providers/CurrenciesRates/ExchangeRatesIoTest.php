<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Stef\Beata\Providers\CurrenciesRates\ExchangeRatesIo;

final class ExchangeRatesIoTest extends TestCase
{
    /**
     * @covers Stef\Beata\Providers\CurrenciesRates\ExchangeRatesIo::getEuroRateFromCurrency
     */
    public function testGetEuroRateFromCurrency()
    {
      $this->assertClassHasStaticAttribute('MEMOIZATION', ExchangeRatesIo::class);
      $memoizationProperty = new \ReflectionProperty(ExchangeRatesIo::class, 'MEMOIZATION');
      $memoizationProperty->setAccessible(true);
      $currency = 'EUR';
      $rate = ExchangeRatesIo::getEuroRateFromCurrency($currency);
      $this->assertIsFloat($rate);
      $this->assertIsObject($memoizationProperty->getValue());
      $this->assertObjectHasAttribute('rates', $memoizationProperty->getValue());
      $this->assertObjectHasAttribute($currency, $memoizationProperty->getValue()->rates);
      $this->assertEquals($rate, $memoizationProperty->getValue()->rates->{$currency});
    }
    /**
     * @covers Stef\Beata\Providers\CurrenciesRates\ExchangeRatesIo::getEuroRateFromCurrency
     */
    public function testInvalidGetEuroRateFromCurrency()
    {
      $this->expectError();
      ExchangeRatesIo::getEuroRateFromCurrency('');
    }
}
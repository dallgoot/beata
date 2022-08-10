<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Stef\Beata\Providers\BinLookup\BinListNet;

final class BinListNetTest extends TestCase
{
    /**
     * @covers Stef\Beata\Providers\BinLookup\BinListNet::getAlpha2CountryCodeByBin
     */
    public function testInvalidGetAlpha2CountryCodeByBin()
    {
      $this->expectError();
      BinListNet::getAlpha2CountryCodeByBin(666);
    }
    /**
     * @covers Stef\Beata\Providers\BinLookup\BinListNet::getAlpha2CountryCodeByBin
     */
    public function testGetAlpha2CountryCodeByBin()
    {
      $this->assertClassHasStaticAttribute('MEMOIZATION', BinListNet::class);
      $memoizationProperty = new \ReflectionProperty(BinListNet::class, 'MEMOIZATION');
      $memoizationProperty->setAccessible(true);
      $this->assertIsArray($memoizationProperty->getValue());
      $bin = 516793;
      $code = BinListNet::getAlpha2CountryCodeByBin($bin);
      $this->assertArrayHasKey($bin, $memoizationProperty->getValue());
      $this->assertEquals($code, $memoizationProperty->getValue()[$bin]);
    }

}
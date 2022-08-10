<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Stef\Beata\Calculations\Commissions;

final class CommissionsTest extends TestCase
{
    /**
     * @covers Stef\Beata\Calculations\Commissions::getCommissionByAlpha2CountryCode
     */
    public function testGetCommissionByAlpha2CountryCode()
    {
        foreach (Commissions::EU_COUNTRIES as $key => $countryCode) {
            $this->assertEquals(Commissions::EU_COMMISSION, Commissions::getCommissionByAlpha2CountryCode($countryCode));
        }
        $this->assertEquals(Commissions::NON_EU_COMMISSION, Commissions::getCommissionByAlpha2CountryCode('JP'));
        $this->assertEquals(Commissions::NON_EU_COMMISSION, Commissions::getCommissionByAlpha2CountryCode('US'));
    }

    /**
     * @covers Stef\Beata\Calculations\Commissions::getCommissionByAlpha2CountryCode
     */
    public function testEmptyGetCommissionByAlpha2CountryCode()
    {
        $this->expectError(\ValueError::class);
        Commissions::getCommissionByAlpha2CountryCode('');
    }

    /**
     * @covers Stef\Beata\Calculations\Commissions::getCommissionByAlpha2CountryCode
     */
    public function testInvalidGetCommissionByAlpha2CountryCode()
    {
        $this->expectError(\ValueError::class);
        Commissions::getCommissionByAlpha2CountryCode('XXX');
    }

    /**
     * @covers Stef\Beata\Calculations\Commissions::showFromFile
     */
    public function testShowFromFile()
    {
        $this->expectOutputRegex('/(?:\d+(\.\d+)?\s?){5}/');
        $this->assertEquals(null, Commissions::showFromFile(__DIR__.'/../../data/input.txt'));
    }

    /**
     * @covers Stef\Beata\Calculations\Commissions::showFromFile
     */
    public function testInvalidShowFromFile()
    {
        $this->expectException(\ErrorException::class);
        Commissions::showFromFile('non_existent_file.txt');
    }
}
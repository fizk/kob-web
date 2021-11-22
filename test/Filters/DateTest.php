<?php

namespace App\Filters;

use PHPUnit\Framework\TestCase;
use App\Filters\Date;
use DateTime;

class DateTest extends TestCase
{
    public function testYear()
    {
        $expected = '2001';
        $actual = (new Date())->year(new DateTime('2001-01-01'));

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testDate($date, $language, $expected)
    {
        $this->assertEquals(
            $expected,
            (new Date())->date($date, $language)
        );
    }

    public function dateProvider()
    {
        return [
            [new DateTime('2001-01-01'), 'is', ' 1.janúar 2001'],
            [new DateTime('2001-01-01'), 'en', ' 1.January 2001'],
        ];
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testDateTime($date, $language, $expected)
    {
        $this->assertEquals(
            $expected,
            (new Date())->datetime($date, $language)
        );
    }

    public function dateTimeProvider()
    {
        return [
            [new DateTime('2001-01-01'), 'is', '2001 01 01 00:01'],
            [new DateTime('2001-01-01'), 'en', '2001 01 01 00:01'],
        ];
    }

    /**
     * @dataProvider rfc822Provider
     */
    public function testRFC822($date, $expected)
    {
        $this->assertEquals(
            $expected,
            (new Date())->RFC822($date)
        );
    }

    public function rfc822Provider()
    {
        return [
            [new DateTime('2001-01-01'), 'Mon, 01 Jan 2001 00:00:00 +0000'],
        ];
    }

    public function testGetFilters()
    {
        $actual = (new Date())->getFilters();

        $this->assertEquals('date', $actual[0]->getName());
        $this->assertEquals('datetime', $actual[1]->getName());
        $this->assertEquals('year', $actual[2]->getName());
        $this->assertEquals('RFC822', $actual[3]->getName());
    }
}

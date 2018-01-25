<?php
namespace Wangjian\Ranking\Test;

use PHPUnit\Framework\TestCase;
use Wangjian\Ranking\TimeUtil\TimeUtil;

class TimeUtilTest extends TestCase
{
    use TimeUtil;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        date_default_timezone_set('PRC');
    }

    public function testGetMinTimestampOfDay()
    {
        $data = [
            [1516859697, 1516809600]
        ];

        foreach($data as $item) {
            $this->assertEquals($item[1], $this->getMinTimestampOfDay($item[0]));
        }
    }

    public function testGetMaxTimestampOfDay()
    {
        $data = [
            [1516859697, 1516895999]
        ];

        foreach($data as $item) {
            $this->assertEquals($item[1], $this->getMaxTimestampOfDay($item[0]));
        }
    }

    public function testGetMinTimestampOfWeek()
    {
        $data = [
            [1516580125, 1516550400],
            [1516859697, 1516550400],
            [1517098525, 1516550400]
        ];

        foreach($data as $item) {
            $this->assertEquals($item[1], $this->getMinTimestampOfWeek($item[0]));
        }
    }

    public function testGetMaxTimestampOfWeek()
    {
        $data = [
            [1516594332, 1517155199],
            [1516853532, 1517155199],
            [1517112732, 1517155199]
        ];

        foreach($data as $item) {
            $this->assertEquals($item[1], $this->getMaxTimestampOfWeek($item[0]));
        }
    }

    public function testGetMinTimestampOfMonth()
    {
        $data = [
            [1514779932, 1514736000],
            [1515989532, 1514736000],
            [1517371932, 1514736000],
            [1517458332, 1517414400],
            [1518667932, 1517414400],
            [1519791132, 1517414400]
        ];

        foreach($data as $item) {
            $this->assertEquals($item[1], $this->getMinTimestampOfMonth($item[0]));
        }
    }

    public function testGetMaxTimestampOfMonth()
    {
        $data = [
            [1514779932, 1517414399],
            [1515989532, 1517414399],
            [1517371932, 1517414399],
            [1517458332, 1519833599],
            [1518667932, 1519833599],
            [1519791132, 1519833599],
            [1512101532, 1514735999],
            [1513311132, 1514735999],
            [1514693532, 1514735999]
        ];

        foreach($data as $item) {
            $this->assertEquals($item[1], $this->getMaxTimestampOfMonth($item[0]));
        }
    }
}
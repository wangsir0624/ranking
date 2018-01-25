<?php
namespace Wangjian\Ranking\TimeUtil;

trait TimeUtil
{
    /**
     * get the minimum timestamp of a day
     * @param int $timestamp
     * @return int
     */
    public function getMinTimestampOfDay($timestamp = null)
    {
        $timestamp = is_null($timestamp) ? time() : $timestamp;

        return strtotime(date('Y-m-d 00:00:00', $timestamp));
    }

    /**
     * get the maximum timestamp of a day
     * @param int $timestamp
     * @return int
     */
    public function getMaxTimestampOfDay($timestamp = null)
    {
        $timestamp = is_null($timestamp) ? time() : $timestamp;

        return strtotime(date('Y-m-d 23:59:59', $timestamp));
    }

    /**
     * get the minimum timestamp of a week
     * @param int $timestamp
     * @return int
     */
    public function getMinTimestampOfWeek($timestamp = null)
    {
        $timestamp = is_null($timestamp) ? time() : $timestamp;

        $dateinfo = getdate($timestamp);
        $minstamp = $timestamp - $dateinfo['hours'] * 3600 - $dateinfo['minutes'] * 60 - $dateinfo['seconds'];

        if($dateinfo['wday'] == 0) {
            $dateinfo['wday'] = 7;
        }

        $minstamp -= ($dateinfo['wday'] - 1) * 86400;

        return $minstamp;
    }

    /**
     * get the maximum timestamp of a week
     * @param int $timestamp
     * @return int
     */
    public function getMaxTimestampOfWeek($timestamp = null)
    {
        $timestamp = is_null($timestamp) ? time() : $timestamp;

        $dateinfo = getdate($timestamp);
        $minstamp = $timestamp - $dateinfo['hours'] * 3600 - $dateinfo['minutes'] * 60 - $dateinfo['seconds'];

        if($dateinfo['wday'] == 0) {
            $dateinfo['wday'] = 7;
        }

        $minstamp += (8 - $dateinfo['wday']) * 86400 - 1;

        return $minstamp;
    }

    /**
     * get the minimum timestamp of a month
     * @param int $timestamp
     * @return int
     */
    public function getMinTimestampOfMonth($timestamp = null)
    {
        $timestamp = is_null($timestamp) ? time() : $timestamp;

        $dateinfo = getdate($timestamp);
        $minstamp = $timestamp - $dateinfo['hours'] * 3600 - $dateinfo['minutes'] * 60 - $dateinfo['seconds'];

        $minstamp -= ($dateinfo['mday'] - 1) * 86400;

        return $minstamp;
    }

    /**
     * get the minimum timestamp of a month
     * @param int $timestamp
     * @return int
     */
    public function getMaxTimestampOfMonth($timestamp = null)
    {
        $timestamp = is_null($timestamp) ? time() : $timestamp;

        $dateinfo = getdate($timestamp);
        $nextMonth = $dateinfo['mon'] + 1;
        if($nextMonth > 12) {
            $nextMonth -= 12;
        }

        return strtotime(date("Y-$nextMonth-01 00:00:00")) - 1;
    }
}
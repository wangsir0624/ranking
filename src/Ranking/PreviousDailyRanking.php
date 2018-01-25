<?php
namespace Wangjian\Ranking\Ranking;

class PreviousDailyRanking extends AbstractRanking
{
    public function getRankingName()
    {
        return 'previous_daily';
    }

    public function needRefresh()
    {
        if(($initTimestamp = $this->getInitTime()) === false) {
            return true;
        }

        $previousDayTimestamp = $this->getMinTimestampOfDay() - 3600;

        return $initTimestamp < $this->getMinTimestampOfDay($previousDayTimestamp) || $initTimestamp > $this->getMaxTimestampOfDay($previousDayTimestamp);
    }

    public function isRealTime()
    {
        return false;
    }
}
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

        return $initTimestamp < $this->getMinTimestampOfDay() || $initTimestamp > $this->getMaxTimestampOfDay();
    }

    public function isRealTime()
    {
        return false;
    }
}
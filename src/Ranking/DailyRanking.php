<?php
namespace Wangjian\Ranking\Ranking;

class DailyRanking extends AbstractRanking
{
    public function getRankingName()
    {
        return 'daily';
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
        return true;
    }
}
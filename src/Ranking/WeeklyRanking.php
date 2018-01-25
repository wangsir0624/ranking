<?php
namespace Wangjian\Ranking\Ranking;

class WeeklyRanking extends AbstractRanking
{
    public function getRankingName()
    {
        return 'weekly';
    }

    public function needRefresh()
    {
        if(($initTimestamp = $this->getInitTime()) === false) {
            return true;
        }

        return $initTimestamp < $this->getMinTimestampOfWeek() || $initTimestamp > $this->getMaxTimestampOfWeek();
    }

    public function isRealTime()
    {
        return true;
    }
}
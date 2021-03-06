<?php
namespace Wangjian\Ranking\Ranking;

class PreviousWeeklyRanking extends AbstractRanking
{
    public function getRankingName()
    {
        return 'previous_weekly';
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
        return false;
    }
}
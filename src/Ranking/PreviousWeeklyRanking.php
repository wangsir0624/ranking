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

        $previousWeekTimestamp = $this->getMinTimestampOfWeek() - 3600;

        return $initTimestamp < $this->getMinTimestampOfWeek($previousWeekTimestamp) || $initTimestamp > $this->getMaxTimestampOfWeek($previousWeekTimestamp);
    }

    public function isRealTime()
    {
        return false;
    }
}
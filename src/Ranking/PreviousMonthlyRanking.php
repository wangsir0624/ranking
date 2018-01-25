<?php
namespace Wangjian\Ranking\Ranking;

class PreviousMonthlyRanking extends AbstractRanking
{
    public function getRankingName()
    {
        return 'previous_monthly';
    }

    public function needRefresh()
    {
        if(($initTimestamp = $this->getInitTime()) === false) {
            return true;
        }

        $previousMonthTimestamp = $this->getMinTimestampOfMonth() - 3600;

        return $initTimestamp < $this->getMinTimestampOfMonth($previousMonthTimestamp) || $initTimestamp > $this->getMaxTimestampOfMonth($previousMonthTimestamp);
    }

    public function isRealTime()
    {
        return false;
    }
}
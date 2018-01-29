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

        return $initTimestamp < $this->getMinTimestampOfMonth() || $initTimestamp > $this->getMaxTimestampOfMonth();
    }

    public function isRealTime()
    {
        return false;
    }
}
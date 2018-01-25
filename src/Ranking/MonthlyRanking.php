<?php
namespace Wangjian\Ranking\Ranking;

class MonthlyRanking extends AbstractRanking
{
    public function getRankingName()
    {
        return 'monthly';
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
        return true;
    }
}

<?php
namespace Wangjian\Ranking\Ranking;

class TotalRanking extends AbstractRanking
{
    protected function getRankingName()
    {
        return 'total';
    }

    protected function needRefresh()
    {
        return $this->getInitTime() === false;
    }

    protected function isRealTime()
    {
        return true;
    }
}

<?php
namespace Wangjian\Ranking\Ranking;

use Wangjian\Ranking\Provider\AbstractProvider;
use Wangjian\Ranking\RedisAdapter\AbstractRedisAdapter;

abstract class AbstractRanking
{
    protected $redisAdapter;

    protected $provider;

    protected $prefix;

    public function __construct(AbstractRedisAdapter $redisAdapter, AbstractProvider $provider, $prefix)
    {
        $this->redisAdapter = $redisAdapter;
        $this->provider = $provider;
        $this->prefix = $prefix;

        $this->init();
    }

    public function rank($member, $withScores = false)
    {

    }

    public function top($offset, $limit, $withScores = false)
    {

    }

    public function addItem($member, $score)
    {
        if($this->isRealTime()) {
            $this->redisAdapter->zincrby($this->getRankKey(), $score, $member);
        }
    }

    protected function getInitKey()
    {
        return $this->prefix . ':' . $this->getRankingName() . '_' . 'init';
    }

    protected function getRankKey()
    {
        return $this->prefix . ':' . $this->getRankingName() . '_' . 'rank';
    }

    protected function init()
    {
        if($this->needRefresh()) {
            $tmpInitKey = $this->getInitKey() . '_temp';
            $tmpRankKey = $this->getInitKey() . '_temp';

            if($this->redisAdapter->setnx($tmpInitKey, time())) {
                foreach($this->provider->provide() as $item) {
                    $this->redisAdapter->zincrby($tmpRankKey, $item['score'], $item['member']);
                }
            }

            $this->redisAdapter->rename($tmpInitKey, $this->getInitKey());
            $this->redisAdapter->rename($tmpRankKey, $this->getRankKey());
        }
    }

    protected function getInitTime() {
        return (int)$this->redisAdapter->get($this->getInitKey());
    }

    abstract protected function getRankingName();

    abstract protected function needRefresh();

    abstract protected function isRealTime();
}
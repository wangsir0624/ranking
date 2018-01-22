<?php
namespace Wangjian\Ranking\Ranking;

use Wangjian\Ranking\Provider\AbstractProvider;
use Predis\Client;

abstract class AbstractRanking
{
    protected $client;

    protected $provider;

    protected $prefix;

    public function __construct(Client $client, AbstractProvider $provider, $prefix)
    {
        $this->client = $client;
        $this->provider = $provider;
        $this->prefix = $prefix;

        $this->init();
    }

    public function rank($member, $withScores = false)
    {
        $result = [];

        $rank = $this->client->zrevrank($this->getRankKey(), $member);
        $result['rank'] = $rank === null ? $rank : $rank + 1;

        if($withScores) {
            $result['score'] = $this->client->zscore($this->getRankKey(), $member);
        }

        return $result;
    }

    public function top($offset, $limit, $withScores = false)
    {
    }

    public function addItem($member, $score)
    {
        if($this->isRealTime()) {
            $this->client->zincrby($this->getRankKey(), $score, $member);
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
            $tmpRankKey = $this->getRankKey() . '_temp';

            if($this->client->setnx($tmpInitKey, time())) {
                foreach($this->provider->provide() as $item) {
                    $this->client->zincrby($tmpRankKey, $item['score'], $item['member']);
                }
            }

            $this->client->rename($tmpInitKey, $this->getInitKey());
            $this->client->rename($tmpRankKey, $this->getRankKey());
        }
    }

    protected function getInitTime() {
        return (int)$this->client->get($this->getInitKey());
    }

    abstract protected function getRankingName();

    abstract protected function needRefresh();

    abstract protected function isRealTime();
}
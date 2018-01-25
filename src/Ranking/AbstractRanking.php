<?php
namespace Wangjian\Ranking\Ranking;

use Wangjian\Ranking\Provider\AbstractProvider;
use Predis\Client;

abstract class AbstractRanking
{
    /**
     * Predis client
     * @var Client
     */
    protected $client;

    /**
     * data provider
     * @var AbstractProvider
     */
    protected $provider;

    /**
     * rank name
     * @var
     */
    protected $prefix;

    /**
     * AbstractRanking constructor
     * @param Client $client
     * @param AbstractProvider $provider
     * @param string $prefix
     */
    public function __construct(Client $client, AbstractProvider $provider, $prefix)
    {
        $this->client = $client;
        $this->provider = $provider;
        $this->prefix = $prefix;

        //init the rank
        $this->init();
    }

    /**
     * get the rank of a member
     * @param string $member
     * @param bool $withScores
     * @return array
     */
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

    /**
     * get the rank list
     * @param int $offset  the rank offset
     * @param int $limit
     * @param bool $withScores
     * @return array
     */
    public function top($offset, $limit, $withScores = false)
    {
        return $this->client->zrevrange($this->getRankKey(), $offset-1, $offset+$limit-2, ['withscores' => $withScores]);
    }

    /**
     * add an item to the rank. this function only works when the rank is real-time
     * @param string $member
     * @param int $score
     * @return bool
     */
    public function addItem($member, $score)
    {
        if($this->isRealTime()) {
            return $this->client->zincrby($this->getRankKey(), $score, $member) > 0;
        }

        return false;
    }

    /**
     * get the rank init key which stores the timestamp when the rank is init
     * @return string
     */
    protected function getInitKey()
    {
        return $this->prefix . ':' . $this->getRankingName() . '_' . 'init';
    }

    /**
     * get the rank key which stores the rank data as a sorted set
     * @return string
     */
    protected function getRankKey()
    {
        return $this->prefix . ':' . $this->getRankingName() . '_' . 'rank';
    }

    /**
     * init the rank
     */
    protected function init()
    {
        if($this->needRefresh()) {
            $rand = rand(1000000, 999999);
            $tmpInitKey = $this->getInitKey() . '_temp' . $rand;
            $tmpRankKey = $this->getRankKey() . '_temp' . $rand;

            if($this->client->setnx($tmpInitKey, time())) {
                foreach($this->provider->provide() as $item) {
                    $this->client->zincrby($tmpRankKey, $item['score'], $item['member']);
                }

                if($this->client->exists($tmpInitKey)) {
                    $this->client->rename($tmpInitKey, $this->getInitKey());
                }

                if($this->client->exists($tmpRankKey)) {
                    $this->client->rename($tmpRankKey, $this->getRankKey());
                }
            }
        }
    }

    /**
     * get the init timestamp
     * @return false|int  return false when the init key does not exist
     */
    protected function getInitTime() {
        if(!$this->client->exists($this->getInitKey())) {
            return false;
        }

        return (int)$this->client->get($this->getInitKey());
    }

    /**
     * get the rank name
     * @return string
     */
    abstract protected function getRankingName();

    /**
     * whether the rank need refresh
     * @return bool
     */
    abstract protected function needRefresh();

    /**
     * whether the rank is real-time
     * @return bool
     */
    abstract protected function isRealTime();
}
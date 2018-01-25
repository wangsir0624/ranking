<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\DailyRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class DailyRankingTest extends RankingTestBase
{
    public function testAddItem()
    {
        $dailyRanking = new DailyRanking($this->client, new AllProviderExample(), 'test');
        $dailyRanking->addItem('wangwu', 30);
        $this->assertEquals(['wangwu', 'zhangsan', 'lisi'], $dailyRanking->top(1, 10));
    }

    public function testRankTooOld()
    {
        $this->prepareDataTooOld();

        $dailyRanking = new DailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $dailyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $dailyRanking->rank('wangwu'));
    }

    public function testTopTooOld()
    {
        $this->prepareDataTooOld();

        $dailyRanking = new DailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $dailyRanking->top(1, 10));
    }

    public function testRankTooNew()
    {
        $this->prepareDataTooNew();

        $dailyRanking = new DailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $dailyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $dailyRanking->rank('wangwu'));
    }

    public function testTopTooNew()
    {
        $this->prepareDataTooNew();

        $dailyRanking = new DailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $dailyRanking->top(1, 10));
    }

    protected function prepareDataTooOld()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:daily_init', time() - 86400);
        foreach($data as $item) {
            $this->client->zincrby('test:daily_rank', $item['score'], $item['member']);
        }
    }

    protected function prepareDataTooNew()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:daily_init', time() + 86400);
        foreach($data as $item) {
            $this->client->zincrby('test:daily_rank', $item['score'], $item['member']);
        }
    }
}
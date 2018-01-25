<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\WeeklyRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class WeeklyRankingTest extends RankingTestBase
{
    public function testAddItem()
    {
        $weeklyRanking = new WeeklyRanking($this->client, new AllProviderExample(), 'test');
        $weeklyRanking->addItem('wangwu', 30);
        $this->assertEquals(['wangwu', 'zhangsan', 'lisi'], $weeklyRanking->top(1, 10));
    }

    public function testRankTooOld()
    {
        $this->prepareDataTooOld();

        $weeklyRanking = new WeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $weeklyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $weeklyRanking->rank('wangwu'));
    }

    public function testTopTooOld()
    {
        $this->prepareDataTooOld();

        $weeklyRanking = new WeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $weeklyRanking->top(1, 10));
    }

    public function testRankTooNew()
    {
        $this->prepareDataTooNew();

        $weeklyRanking = new WeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $weeklyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $weeklyRanking->rank('wangwu'));
    }

    public function testTopTooNew()
    {
        $this->prepareDataTooNew();

        $weeklyRanking = new WeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $weeklyRanking->top(1, 10));
    }

    protected function prepareDataTooOld()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:weekly_init', time() - 86400 * 7);
        foreach($data as $item) {
            $this->client->zincrby('test:weekly_rank', $item['score'], $item['member']);
        }
    }

    protected function prepareDataTooNew()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:weekly_init', time() + 86400 * 7);
        foreach($data as $item) {
            $this->client->zincrby('test:weekly_rank', $item['score'], $item['member']);
        }
    }
}
<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\PreviousDailyRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class PreviousDailyRankingTest extends RankingTestBase
{
    public function testAddItem()
    {
        $previousDailyRanking = new PreviousDailyRanking($this->client, new AllProviderExample(), 'test');
        $previousDailyRanking->addItem('wangwu', 30);
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousDailyRanking->top(1, 10));
    }

    public function testRankTooOld()
    {
        $this->prepareDataTooOld();

        $previousDailyRanking = new PreviousDailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $previousDailyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $previousDailyRanking->rank('wangwu'));
    }

    public function testTopTooOld()
    {
        $this->prepareDataTooOld();

        $previousDailyRanking = new PreviousDailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousDailyRanking->top(1, 10));
    }

    public function testRankTooNew()
    {
        $this->prepareDataTooNew();

        $previousDailyRanking = new PreviousDailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $previousDailyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $previousDailyRanking->rank('wangwu'));
    }

    public function testTopTooNew()
    {
        $this->prepareDataTooNew();

        $previousDailyRanking = new PreviousDailyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousDailyRanking->top(1, 10));
    }

    protected function prepareDataTooOld()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:previous_daily_init', time() - 86400);
        foreach($data as $item) {
            $this->client->zincrby('test:previous_daily_rank', $item['score'], $item['member']);
        }
    }

    protected function prepareDataTooNew()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:previous_daily_init', time() + 86400);
        foreach($data as $item) {
            $this->client->zincrby('test:previous_daily_rank', $item['score'], $item['member']);
        }
    }
}
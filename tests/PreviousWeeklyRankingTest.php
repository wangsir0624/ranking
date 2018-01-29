<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\PreviousWeeklyRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class PreviousWeeklyRankingTest extends RankingTestBase
{
    public function testAddItem()
    {
        $previousWeeklyRanking = new PreviousWeeklyRanking($this->client, new AllProviderExample(), 'test');
        $previousWeeklyRanking->addItem('wangwu', 30);
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousWeeklyRanking->top(1, 10));
    }

    public function testRankTooOld()
    {
        $this->prepareDataTooOld();

        $previousWeeklyRanking = new PreviousWeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $previousWeeklyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $previousWeeklyRanking->rank('wangwu'));
    }

    public function testTopTooOld()
    {
        $this->prepareDataTooOld();

        $previousWeeklyRanking = new PreviousWeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousWeeklyRanking->top(1, 10));
    }

    public function testRankTooNew()
    {
        $this->prepareDataTooNew();

        $previousWeeklyRanking = new PreviousWeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $previousWeeklyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $previousWeeklyRanking->rank('wangwu'));
    }

    public function testTopTooNew()
    {
        $this->prepareDataTooNew();

        $previousWeeklyRanking = new PreviousWeeklyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousWeeklyRanking->top(1, 10));
    }

    protected function prepareDataTooOld()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:previous_weekly_init', time() - 86400 * 7);
        foreach($data as $item) {
            $this->client->zincrby('test:previous_weekly_rank', $item['score'], $item['member']);
        }
    }

    protected function prepareDataTooNew()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:previous_weekly_init', time() + 86400 * 7);
        foreach($data as $item) {
            $this->client->zincrby('test:previous_weekly_rank', $item['score'], $item['member']);
        }
    }
}
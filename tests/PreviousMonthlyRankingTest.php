<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\PreviousMonthlyRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class PreviousMonthlyRankingTest extends RankingTestBase
{
    public function testAddItem()
    {
        $previousMonthlyRanking = new PreviousMonthlyRanking($this->client, new AllProviderExample(), 'test');
        $previousMonthlyRanking->addItem('wangwu', 30);
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousMonthlyRanking->top(1, 10));
    }

    public function testRankTooOld()
    {
        $this->prepareDataTooOld();

        $previousMonthlyRanking = new PreviousMonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $previousMonthlyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $previousMonthlyRanking->rank('wangwu'));
    }

    public function testTopTooOld()
    {
        $this->prepareDataTooOld();

        $previousMonthlyRanking = new PreviousMonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousMonthlyRanking->top(1, 10));
    }

    public function testRankTooNew()
    {
        $this->prepareDataTooNew();

        $previousMonthlyRanking = new PreviousMonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $previousMonthlyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $previousMonthlyRanking->rank('wangwu'));
    }

    public function testTopTooNew()
    {
        $this->prepareDataTooNew();

        $previousMonthlyRanking = new PreviousMonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $previousMonthlyRanking->top(1, 10));
    }

    protected function prepareDataTooOld()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:previous_monthly_init', time() - 86400 * 62);
        foreach($data as $item) {
            $this->client->zincrby('test:previous_monthly_rank', $item['score'], $item['member']);
        }
    }

    protected function prepareDataTooNew()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:previous_monthly_init', time());
        foreach($data as $item) {
            $this->client->zincrby('test:previous_monthly_rank', $item['score'], $item['member']);
        }
    }
}
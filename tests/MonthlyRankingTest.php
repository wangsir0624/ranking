<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\MonthlyRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class MonthlyRankingTest extends RankingTestBase
{
    public function testAddItem()
    {
        $monthlyRanking = new MonthlyRanking($this->client, new AllProviderExample(), 'test');
        $monthlyRanking->addItem('wangwu', 30);
        $this->assertEquals(['wangwu', 'zhangsan', 'lisi'], $monthlyRanking->top(1, 10));
    }

    public function testRankTooOld()
    {
        $this->prepareDataTooOld();

        $monthlyRanking = new MonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $monthlyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $monthlyRanking->rank('wangwu'));
    }

    public function testTopTooOld()
    {
        $this->prepareDataTooOld();

        $monthlyRanking = new MonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $monthlyRanking->top(1, 10));
    }

    public function testRankTooNew()
    {
        $this->prepareDataTooNew();

        $monthlyRanking = new MonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $monthlyRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $monthlyRanking->rank('wangwu'));
    }

    public function testTopTooNew()
    {
        $this->prepareDataTooNew();

        $monthlyRanking = new MonthlyRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $monthlyRanking->top(1, 10));
    }

    protected function prepareDataTooOld()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:monthly_init', time() - 86400 * 31);
        foreach($data as $item) {
            $this->client->zincrby('test:monthly_rank', $item['score'], $item['member']);
        }
    }

    protected function prepareDataTooNew()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:monthly_init', time() + 86400 * 31);
        foreach($data as $item) {
            $this->client->zincrby('test:monthly_rank', $item['score'], $item['member']);
        }
    }
}
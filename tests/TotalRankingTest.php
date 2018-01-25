<?php
namespace Wangjian\Ranking\Test;

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Wangjian\Ranking\Ranking\TotalRanking;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class TotalRankingTest extends TestCase
{
    protected $client;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $client = new Client([
            'schema' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6380,
            'password' => 'ranking',
            'database' => 0
        ]);

        $this->client = $client;
    }

    public function testRank()
    {
        $totalRanking = new TotalRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 1], $totalRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 3], $totalRanking->rank('wangwu'));
        $this->assertEquals(['rank' => null], $totalRanking->rank('nonexist'));

        //test with scores
        $this->assertEquals(['rank' => 1, 'score' => 80], $totalRanking->rank('zhangsan', true));
        $this->assertEquals(['rank' => 3, 'score' => 65], $totalRanking->rank('wangwu', true));
        $this->assertEquals(['rank' => null, 'score' => null], $totalRanking->rank('nonexist', true));
    }

    public function testTop()
    {
        $totalRanking = new TotalRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['lisi'], $totalRanking->top(2, 1));
        $this->assertEquals(['zhangsan', 'lisi', 'wangwu'], $totalRanking->top(1, 10));

        //test with scores
        $this->assertEquals(['lisi' => 72], $totalRanking->top(2, 1, true));
        $this->assertEquals(['zhangsan' => 80, 'lisi' => 72, 'wangwu' => 65], $totalRanking->top(1, 10, true));
    }

    public function testRankAlreadyExist()
    {
        $this->prepareData();

        $totalRanking = new TotalRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['rank' => 3], $totalRanking->rank('zhangsan'));
        $this->assertEquals(['rank' => 1], $totalRanking->rank('wangwu'));
        $this->assertEquals(['rank' => null], $totalRanking->rank('nonexist'));

        //test with scores
        $this->assertEquals(['rank' => 3, 'score' => 65], $totalRanking->rank('zhangsan', true));
        $this->assertEquals(['rank' => 1, 'score' => 90], $totalRanking->rank('wangwu', true));
        $this->assertEquals(['rank' => null, 'score' => null], $totalRanking->rank('nonexist', true));
    }

    public function testTopAlreadyExist()
    {
        $this->prepareData();

        $totalRanking = new TotalRanking($this->client, new AllProviderExample(), 'test');
        $this->assertEquals(['lisi'], $totalRanking->top(2, 1));
        $this->assertEquals(['wangwu', 'lisi', 'zhangsan'], $totalRanking->top(1, 10));

        //test with scores
        $this->assertEquals(['lisi' => 70], $totalRanking->top(2, 1, true));
        $this->assertEquals(['wangwu' => 90, 'lisi' => 70, 'zhangsan' => 65], $totalRanking->top(1, 10, true));
    }

    protected function prepareData()
    {
        $data = [
            ['score' => 65, 'member' => 'zhangsan'],
            ['score' => 70, 'member' => 'lisi'],
            ['score' => 90, 'member' => 'wangwu']
        ];

        $this->client->set('test:total_init', time());
        foreach($data as $item) {
            $this->client->zincrby('test:total_rank', $item['score'], $item['member']);
        }
    }

    public function setUp()
    {
        $this->client->flushall();
    }
}
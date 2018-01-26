<?php
namespace Wangjian\Ranking\Test;

use Wangjian\Ranking\Ranking\AbstractRanking;
use Wangjian\Ranking\Ranking\DailyRanking;
use Wangjian\Ranking\Ranking\TotalRanking;
use Wangjian\Ranking\RankingManager;
use Exception;
use Wangjian\Ranking\Test\Providers\AllProviderExample;

class RankingManagerTest extends RankingTestBase {
    public function testNormal()
    {
        $manager = new RankingManager($this->client, 'test');
        $manager->addRanking(TotalRanking::class, new AllProviderExample());
        $manager->addRanking(DailyRanking::class, new AllProviderExample(), 'daily');

        $this->assertNull($manager->totalRanking);
        $this->assertNull($manager->daily);

        $manager->init();

        $this->assertInstanceOf(AbstractRanking::class, $manager->totalRanking);
        $this->assertInstanceOf(AbstractRanking::class, $manager->daily);
    }

    /**
     * @expectedException Exception
     */
    public function testClassNotExist()
    {
        $manager = new RankingManager($this->client, 'test');
        $manager->addRanking('RankingNotExist', new AllProviderExample());
        $manager->init();
    }

    /**
     * @expectedException Exception
     */
    public function testClassNotRanking()
    {
        $manager = new RankingManager($this->client, 'test');
        $manager->addRanking("\\Exception", new AllProviderExample());
        $manager->init();
    }
}
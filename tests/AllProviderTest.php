<?php
namespace Wangjian\Ranking\Test;

use PHPUnit\Framework\TestCase;
use Wangjian\Ranking\Test\Providers\AllProviderExample;
use Wangjian\Ranking\Test\Providers\GeneratorProviderExample;
use Wangjian\Ranking\Test\Providers\PageProviderExample;

class AllProviderTest extends TestCase
{
    public function testProvide() {
        $expected = [
            ['score' => 80, 'member' => 'zhangsan'],
            ['score' => 72, 'member' => 'lisi'],
            ['score' => 65, 'member' => 'wangwu']
        ];

        $providers = [
            new AllProviderExample(),
            new PageProviderExample(),
            new GeneratorProviderExample()
        ];

        foreach($providers as $provider) {
            $result = [];
            foreach($provider->provide() as $item) {
                $result[] = $item;
            }

            $this->assertEquals($result, $expected);
        }
    }
}
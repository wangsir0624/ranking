<?php
namespace Wangjian\Ranking\Test\Providers;

use Wangjian\Ranking\Provider\GeneratorProvider;

class GeneratorProviderExample extends GeneratorProvider
{
    public function generate()
    {
        yield ['score' => 80, 'member' => 'zhangsan'];
        yield ['score' => 72, 'member' => 'lisi'];
        yield ['score' => 65, 'member' => 'wangwu'];
    }
}
<?php
namespace Wangjian\Ranking\Test\Providers;

use Wangjian\Ranking\Provider\GeneratorProvider;

class GeneratorProviderExample extends GeneratorProvider
{
    public function generate()
    {
        yield ['score' => 80, 'zhangsan'];
        yield ['score' => 72, 'lisi'];
        yield ['score' => 65, 'wangwu'];
    }
}
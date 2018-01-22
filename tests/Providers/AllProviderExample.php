<?php
namespace Wangjian\Ranking\Test\Providers;

use Wangjian\Ranking\Provider\AllProvider;

class AllProviderExample extends AllProvider
{
    public function getAll()
    {
        return [
            ['score' => 80, 'zhangsan'],
            ['score' => 72, 'lisi'],
            ['score' => 65, 'wangwu'],
        ];
    }
}
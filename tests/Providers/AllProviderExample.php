<?php
namespace Wangjian\Ranking\Test\Providers;

use Wangjian\Ranking\Provider\AllProvider;

class AllProviderExample extends AllProvider
{
    public function getAll()
    {
        return [
            ['score' => 80, 'member' => 'zhangsan'],
            ['score' => 72, 'member' => 'lisi'],
            ['score' => 65, 'member' => 'wangwu'],
        ];
    }
}
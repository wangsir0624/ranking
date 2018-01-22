<?php
namespace Wangjian\Ranking\Test\Providers;

use Wangjian\Ranking\Provider\PageProvider;

class PageProviderExample extends PageProvider
{
    public function getPage($page)
    {
        $data = [
            ['score' => 80, 'member' => 'zhangsan'],
            ['score' => 72, 'member' => 'lisi'],
            ['score' => 65, 'member' => 'wangwu']
        ];

        return array_slice($data, $page-1, 1);
    }
}
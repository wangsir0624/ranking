<?php
namespace Wangjian\Ranking\Test\Providers;

use Wangjian\Ranking\Provider\PageProvider;

class PageProviderExample extends PageProvider
{
    public function getPage($page)
    {
        $data = [
            ['score' => 80, 'zhangsan'],
            ['score' => 72, 'lisi'],
            ['score' => 65, 'wangwu']
        ];

        return array_slice($data, $page-1, 1);
    }
}
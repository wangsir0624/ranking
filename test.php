<?php
require_once __DIR__ . '/vendor/autoload.php';

class AllProvider extends \Wangjian\Ranking\Provider\AllProvider
{
    public function getAll()
    {
        $items = [];

        for($i = 0; $i < 10; $i++) {
            $items[] = [
                'score' => rand(0, 100),
                'member' => $this->getRandomMember()
            ];
        }

        return $items;
    }

    protected function getRandomMember($length = 6)
    {
        $tokens = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $tokenCount = strlen($tokens);

        $str = '';
        for($i = 0; $i < $length; $i++) {
            $str .= $tokens[rand(0, $tokenCount-1)];
        }

        return $str;
    }
}

class PageProvider extends \Wangjian\Ranking\Provider\PageProvider
{
    public function getPage($page)
    {
        return [];
    }
}

class GeneratorProvider extends \Wangjian\Ranking\Provider\GeneratorProvider
{
    public function generate()
    {
        if(false) {
            yield [];
        }
    }
}

$provider1 = new AllProvider();
$provider2 = new PageProvider();
$provider3 = new GeneratorProvider();
$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => 'root'
]);
$totalRanking = new \Wangjian\Ranking\Ranking\TotalRanking($client, $provider1, 'test:ranking');
var_dump($totalRanking->top(1, 6));
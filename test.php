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

$provider = new AllProvider();
$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host' => '127.0.0.1',
    'port' => 6379,
    'password' => 'root'
]);
$totalRanking = new \Wangjian\Ranking\Ranking\TotalRanking($client, $provider, 'test:ranking');
var_dump($totalRanking->top(1, 6));
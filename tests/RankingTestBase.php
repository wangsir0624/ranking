<?php
namespace Wangjian\Ranking\Test;

use PHPUnit\Framework\TestCase;
use Predis\Client;
use Wangjian\Ranking\TimeUtil\TimeUtil;

class RankingTestBase extends TestCase
{
    use TimeUtil;

    protected $client;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $client = new Client([
            'schema' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0
        ]);

        $this->client = $client;
    }

    protected function setUp()
    {
        $this->client->flushall();
    }
}

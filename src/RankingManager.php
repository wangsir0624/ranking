<?php
namespace Wangjian\Ranking;

use Predis\Client;
use Wangjian\Ranking\Provider\AbstractProvider;
use Exception;
use Wangjian\Ranking\Ranking\AbstractRanking;

class RankingManager
{
    /**
     * predis client
     * @var Client
     */
    protected $client;

    /**
     * ranking prefix
     * @var string
     */
    protected $prefix;

    /**
     * the ranking configs
     * @var array
     */
    protected $rankingConfigs = [];

    /**
     * the ranking instances
     * @var array
     */
    protected $rankingInstances = [];

    /**
     * RankingManager constructor
     * @param Client $client
     * @param string $prefix
     */
    public function __construct(Client $client, $prefix)
    {
        $this->client = $client;
        $this->prefix = $prefix;
    }

    /**
     * add a ranking to the manager
     * @param string $className  the fullname of the ranking class
     * @param AbstractProvider $provider  the data provider instance
     * @param string $alias  the alias
     * @return $this
     */
    public function addRanking($className, AbstractProvider $provider, $alias = null)
    {
        if(is_null($alias)) {
            $tokens = explode("\\", $className);
            $alias = lcfirst(end($tokens));
        }

        $this->rankingConfigs[$className] = ['provider' => $provider, 'alias' => $alias];

        return $this;
    }

    /**
     * initialize the ranking instances
     * @throws Exception
     */
    public function init()
    {
        foreach($this->rankingConfigs as $class => $config) {
            if(!class_exists($class)) {
                throw new Exception("the $class class doesn't exist");
            }

            if(!is_subclass_of($class, AbstractRanking::class)) {
                throw new Exception("the $class is not a ranking class");
            }

            $this->rankingInstances[$config['alias']] = new $class($this->client, $config['provider'], $this->prefix);
        }
    }

    /**
     * add an item to all ranks. only works for real-time ranks
     * @param string $member
     * @param int $score
     * @return $this
     */
    public function addItem($member, $score)
    {
        foreach($this->rankingInstances as $instance) {
            $instance->addItem($member, $score);
        }

        return $this;
    }

    /**
     * get the ranking instance by the alias
     * @param string $name
     * @return AbstractRanking
     */
    public function __get($name)
    {
        if(!isset($this->rankingInstances[$name])) {
            return null;
        }

        return $this->rankingInstances[$name];
    }
}
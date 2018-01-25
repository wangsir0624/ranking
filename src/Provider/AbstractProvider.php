<?php
namespace Wangjian\Ranking\Provider;

use Generator;

abstract class AbstractProvider
{
    /**
     * provider data
     * @return Generator
     */
    abstract public function provide();
}
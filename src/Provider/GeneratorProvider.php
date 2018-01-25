<?php
namespace Wangjian\Ranking\Provider;

use Generator;

abstract class GeneratorProvider extends AbstractProvider
{
    public function provide()
    {
        return $this->generate();
    }

    /**
     * get data using a generator
     * @return Generator
     */
    abstract public function generate();
}